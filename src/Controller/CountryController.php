<?php
/**
 * Jield copyright message placeholder.
 *
 * @category    Admin
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use Content\Entity\Route;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Country;
use General\Form\CountryFilter;
use General\Service\FormService;
use General\Service\CountryService;
use Zend\Http\Response;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class CountryController
 *
 * @package General\Controller
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class CountryController extends AbstractActionController
{
    /**
     * @var CountryService
     */
    private $countryService;
    /**
     * @var FormService
     */
    private $formService;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        CountryService $countryService,
        FormService $formService,
        TranslatorInterface $translator
    ) {
        $this->countryService = $countryService;
        $this->formService = $formService;
        $this->translator = $translator;
    }

    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->countryService->findFiltered(Country::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new CountryFilter();
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel(
            [
                'paginator'     => $paginator,
                'form'          => $form,
                'encodedFilter' => urlencode($filterPlugin->getHash()),
                'order'         => $filterPlugin->getOrder(),
                'direction'     => $filterPlugin->getDirection(),
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
                $this->redirect()->toRoute('zfcadmin/country/list');
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
