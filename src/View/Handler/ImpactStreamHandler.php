<?php
/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */
declare(strict_types=1);

namespace General\View\Handler;

use Content\Entity\Content;
use General\Service\GeneralService;
use Project\Search\Service\ResultSearchService;
use Project\Service\ProjectService;
use Search\Form\SearchResult;
use Search\Paginator\Adapter\SolariumPaginator;
use Solarium\QueryType\Select\Query\Query as SolariumQuery;
use Zend\Authentication\AuthenticationService;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Application;
use Zend\Paginator\Paginator;
use Zend\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class ImpactStreamHandler
 *
 * @package General\View\Handler
 */
final class ImpactStreamHandler extends AbstractHandler
{
    /**
     * @var ResultSearchService
     */
    private $resultSearchService;
    /**
     * @var GeneralService
     */
    private $generalService;
    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(
        Application $application,
        HelperPluginManager $helperPluginManager,
        TwigRenderer $renderer,
        AuthenticationService $authenticationService,
        TranslatorInterface $translator,
        ResultSearchService $resultSearchService,
        GeneralService $generalService,
        ProjectService $projectService
    ) {
        parent::__construct(
            $application,
            $helperPluginManager,
            $renderer,
            $authenticationService,
            $translator
        );

        $this->resultSearchService = $resultSearchService;
        $this->generalService = $generalService;
        $this->projectService = $projectService;
    }

    public function __invoke(Content $content): ?string
    {
        switch ($content->getHandler()->getHandler()) {
            case 'impactstream_index':
                $this->getHeadTitle()->append($this->translate("txt-impact-stream"));

                return $this->parseIndex();

            default:
                return sprintf(
                    'No handler available for <code>%s</code> in class <code>%s</code>',
                    $content->getHandler()->getHandler(),
                    __CLASS__
                );
        }
    }

    public function parseIndex(): string
    {
        //Set the default date on now
        $today = new \DateTime();

        $lastYear = new \DateTime();
        $lastYear->sub(new \DateInterval("P12M"));

        $page = $this->request->getQuery('page', 1);
        $form = new SearchResult();
        $data = \array_merge(
            [
                'order'     => '',
                'toDate'    => [
                    'year'  => $today->format('Y'),
                    'month' => $today->format('m'),
                ],
                'fromDate'  => [
                    'year'  => $lastYear->format('Y'),
                    'month' => $lastYear->format('m'),
                ],
                'direction' => '',
                'query'     => '',
                'facet'     => [],
            ],
            $this->request->getQuery()->toArray()
        );

        $searchFields = [
            'result_search',
            'result_abstract',
            'project_search',
            'organisation_search',
            'organisation_type_search',
            'challenge_search',
            'country_search',
            'html'
        ];

        if ($this->request->isGet()) {
            $dateInterval = $this->resultSearchService->parseDateInterval($data);

            $this->resultSearchService->setSearchImpactStream(
                $data['query'],
                $searchFields,
                $data['order'],
                $data['direction'],
                $dateInterval->fromDate,
                $dateInterval->toDate
            );
            if (isset($data['facet'])) {
                foreach ($data['facet'] as $facetField => $values) {
                    $quotedValues = [];
                    foreach ($values as $value) {
                        $quotedValues[] = sprintf("\"%s\"", $value);
                    }

                    $this->resultSearchService->addFilterQuery(
                        $facetField,
                        implode(' ' . SolariumQuery::QUERY_OPERATOR_OR . ' ', $quotedValues)
                    );
                }
            }

            $form->addSearchResults(
                $this->resultSearchService->getQuery()->getFacetSet(),
                $this->resultSearchService->getResultSet()->getFacetSet()
            );
            $form->setData($data);
        }

        $paginator = new Paginator(
            new SolariumPaginator(
                $this->resultSearchService->getSolrClient(),
                $this->resultSearchService->getQuery()
            )
        );
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? 1000 : 25);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        // Remove order and direction from the GET params to prevent duplication
        $filteredData = \array_filter(
            $data,
            function ($key) {
                return !\in_array($key, ['order', 'direction'], true);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $this->renderer->render(
            'general/partial/impact-stream/index',
            [
                'form'               => $form,
                'order'              => $data['order'],
                'direction'          => $data['direction'],
                'query'              => $data['query'],
                'arguments'          => http_build_query($filteredData),
                'paginator'          => $paginator,
                'allChallenges'      => $this->generalService->findAllChallenges(),
                'projectService'     => $this->projectService,
                'highlighting'       => $paginator->getCurrentItems()->getHighlighting(),
                'highlightingFields' => [
                    'result_search',
                    'result_abstract'          => $this->translate('txt-abstract'),
                    'html'                     => $this->translate('txt-content'),
                    'project_search'           => $this->translate('txt-project'),
                    'organisation_search'      => $this->translate('txt-participating-partner'),
                    'organisation_type_search' => $this->translate('txt-organisation-type'),
                    'challenge_search'         => $this->translate('txt-challenge'),
                    'country_search'           => $this->translate('txt-country')
                ]
            ]
        );
    }
}
