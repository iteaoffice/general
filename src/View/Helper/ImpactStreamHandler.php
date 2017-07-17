<?php
/**
 * ITEA Office all rights reserved
 *
 * @category   Challenge
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\View\Helper;

use Content\Entity\Content;
use Content\Entity\Param;
use General\Service\GeneralService;
use Project\Search\Service\ImpactStreamSearchService;
use Project\Service\ProjectService;
use Search\Form\SearchResult;
use Search\Paginator\Adapter\SolariumPaginator;
use Solarium\QueryType\Select\Query\Query as SolariumQuery;
use Zend\Http\Request;
use Zend\Paginator\Paginator;

/**
 * Class ImpactStreamHandler.
 */
class ImpactStreamHandler extends AbstractViewHelper
{
    /**
     * @param Content $content
     *
     * @return string
     */
    public function __invoke(Content $content): string
    {
        $this->extractContentParam($content);

        switch ($content->getHandler()->getHandler()) {
            case 'impactstream_index':
                $this->getHelperPluginManager()->get('headtitle')->append($this->translate("txt-impact-stream"));

                return $this->parseIndex();

            default:
                return sprintf(
                    "No handler available for <code>%s</code> in class <code>%s</code>",
                    $content->getHandler()->getHandler(),
                    __CLASS__
                );
        }
    }

    /**
     * @return string
     */
    public function parseIndex(): string
    {
        $searchService = $this->getImpactStreamSearchService();
        $page = $this->getRequest()->getQuery('page', 1);
        $form = new SearchResult();
        $data = array_merge(
            [
                'order'     => '',
                'direction' => '',
                'query'     => '',
                'facet'     => [],
            ],
            $this->getRequest()->getQuery()->toArray()
        );

        if ($this->getRequest()->isGet()) {
            $searchService->setSearch($data['query'], $data['order'], $data['direction']);
            if (isset($data['facet'])) {
                foreach ($data['facet'] as $facetField => $values) {
                    $quotedValues = [];
                    foreach ($values as $value) {
                        $quotedValues[] = sprintf("\"%s\"", $value);
                    }

                    $searchService->addFilterQuery(
                        $facetField,
                        implode(' ' . SolariumQuery::QUERY_OPERATOR_OR . ' ', $quotedValues)
                    );
                }
            }

            $form->addSearchResults(
                $searchService->getQuery()->getFacetSet(),
                $searchService->getResultSet()->getFacetSet()
            );
            $form->setData($data);
        }

        $paginator = new Paginator(new SolariumPaginator($searchService->getSolrClient(), $searchService->getQuery()));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? 1000 : 25);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        // Remove order and direction from the GET params to prevent duplication
        $filteredData = array_filter(
            $data,
            function ($key) {
                return !in_array($key, ['order', 'direction'], true);
            },
            ARRAY_FILTER_USE_KEY
        );


        return $this->getRenderer()->render(
            'general/partial/impact-stream/index',
            [
                'form'               => $form,
                'order'              => $data['order'],
                'direction'          => $data['direction'],
                'query'              => $data['query'],
                'arguments'          => http_build_query($filteredData),
                'paginator'          => $paginator,
                'projectService'     => $this->getProjectService(),
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


    /**
     * @param Content $content
     */
    public function extractContentParam(Content $content): void
    {
        /**
         * Go over the handler params and try to see if it is hardcoded or just set via the route
         */
        foreach ($content->getHandler()->getParam() as $parameter) {
            switch ($parameter->getParam()) {
                case 'docRef':
                    $docRef = $this->findParamValueFromContent($content, $parameter);


                    break;
            }
        }
    }

    /**
     * @param Content $content
     * @param Param $param
     *
     * @return null|string
     */
    private function findParamValueFromContent(Content $content, Param $param): ?string
    {
        //Hardcoded is always first,If it cannot be found, try to find it from the docref (rule 2)
        foreach ($content->getContentParam() as $contentParam) {
            if ($contentParam->getParameter() === $param && !empty($contentParam->getParameterId())) {
                return $contentParam->getParameterId();
            }
        }

        //Try first to see if the param can be found from the route (rule 1)
        if (!is_null($this->getRouteMatch()->getParam($param->getParam()))) {
            return $this->getRouteMatch()->getParam($param->getParam());
        }

        //If not found, take rule 3
        return null;
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService(): GeneralService
    {
        return $this->getServiceManager()->get(GeneralService::class);
    }

    /**
     * @return ProjectService
     */
    public function getProjectService(): ProjectService
    {
        return $this->getServiceManager()->get(ProjectService::class);
    }

    /**
     * @return ImpactStreamSearchService
     */
    public function getImpactStreamSearchService(): ImpactStreamSearchService
    {
        return $this->getServiceManager()->get(ImpactStreamSearchService::class);
    }

    /**
     * Proxy to the original request object to handle form.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->getServiceManager()->get('application')->getMvcEvent()->getRequest();
    }
}
