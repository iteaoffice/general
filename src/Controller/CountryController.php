<?php
/**
 * Jield copyright message placeholder.
 *
 * @category    Admin
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use Content\Entity\Route;
use General\Controller\Plugin\GetFilter;
use General\Entity\Country;
use General\Search\Service\CountrySearchService;
use General\Service\CountryService;
use General\Service\FormService;
use Search\Form\SearchResult;
use Search\Paginator\Adapter\SolariumPaginator;
use Solarium\QueryType\Select\Query\Query as SolariumQuery;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;
use function http_build_query;
use function implode;
use function sprintf;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class CountryController extends AbstractActionController
{
    private CountryService $countryService;
    private CountrySearchService $countrySearchService;
    private FormService $formService;
    private TranslatorInterface $translator;

    public function __construct(
        CountryService $countryService,
        CountrySearchService $countrySearchService,
        FormService $formService,
        TranslatorInterface $translator
    ) {
        $this->countryService = $countryService;
        $this->countrySearchService = $countrySearchService;
        $this->formService = $formService;
        $this->translator = $translator;
    }

    public function listAction(): ViewModel
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $page = $this->params('page', 1);
        $form = new SearchResult();
        $data = array_merge(
            [
                'order'     => '',
                'direction' => '',
                'query'     => '',
                'facet'     => [],
            ],
            $request->getQuery()->toArray()
        );
        $searchFields = [
            'country',
            'country_search',
            'country_iso3',
            'country_cd',
        ];

        if ($request->isGet()) {
            $this->countrySearchService->setSearch($data['query'], $searchFields, $data['order'], $data['direction']);
            if (isset($data['facet'])) {
                foreach ($data['facet'] as $facetField => $values) {
                    $quotedValues = [];
                    foreach ($values as $value) {
                        $quotedValues[] = sprintf('"%s"', $value);
                    }

                    $this->countrySearchService->addFilterQuery(
                        $facetField,
                        implode(' ' . SolariumQuery::QUERY_OPERATOR_OR . ' ', $quotedValues)
                    );
                }
            }

            $form->addSearchResults(
                $this->countrySearchService->getQuery()->getFacetSet(),
                $this->countrySearchService->getResultSet()->getFacetSet()
            );
            $form->setData($data);
        }

        $paginator = new Paginator(
            new SolariumPaginator($this->countrySearchService->getSolrClient(), $this->countrySearchService->getQuery())
        );
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? 1000 : 25);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));


        return new ViewModel(
            [
                'form'           => $form,
                'order'          => $data['order'],
                'direction'      => $data['direction'],
                'query'          => $data['query'],
                'badges'         => $form->getBadges(),
                'arguments'      => http_build_query($form->getFilteredData()),
                'paginator'      => $paginator,
                'countryService' => $this->countryService
            ]
        );
    }

    public function viewAction(): ViewModel
    {
        $country = $this->countryService->find(Country::class, (int)$this->params('id'));
        if (null === $country) {
            return $this->notFoundAction();
        }

        return new ViewModel(['country' => $country]);
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Country::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/country/list');
            }

            if ($form->isValid()) {
                /* @var $country Country */
                $country = $form->getData();

                $result = $this->countryService->save($country);
                return $this->redirect()->toRoute(
                    'zfcadmin/country/view',
                    [
                        'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var Country $country */
        $country = $this->countryService->find(Country::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($country, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/country/list');
            }

            if (isset($data['delete'])) {
                $this->countryService->delete($country);

                return $this->redirect()->toRoute('zfcadmin/country/list');
            }

            if ($form->isValid()) {
                /** @var Country $country */
                $country = $form->getData();

                $country = $this->countryService->save($country);
                return $this->redirect()->toRoute(
                    'zfcadmin/country/view',
                    [
                        'id' => $country->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'country' => $country]);
    }

    public function codeAction(): Response
    {
        $country = $this->countryService->findCountryByCD((string)$this->params('cd'));

        if (null !== $country) {
            return $this->redirect()->toRoute(
                Route::parseRouteName(Route::DEFAULT_ROUTE_COUNTRY),
                [
                    'docRef' => $country->getDocRef(),
                ]
            )->setStatusCode(301);
        }

        return $this->redirect()->toRoute('home');
    }
}
