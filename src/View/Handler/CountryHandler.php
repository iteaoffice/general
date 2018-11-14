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
use Content\Service\ArticleService;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Country;
use General\Search\Service\CountrySearchService;
use General\Service\CountryService;
use General\View\Helper\CountryLink;
use General\View\Helper\CountryMap;
use Organisation\Service\OrganisationService;
use Program\Service\ProgramService;
use Project\Search\Service\ProjectSearchService;
use Project\Service\ProjectService;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Response;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Application;
use Zend\Paginator\Paginator;
use Zend\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class ProjectHandler
 *
 * @package Project\View\Handler
 */
final class CountryHandler extends AbstractHandler
{
    /**
     * @var CountryService
     */
    private $countryService;
    /**
     * @var CountrySearchService
     */
    private $countrySearchService;
    /**
     * @var ProjectSearchService
     */
    private $projectSearchService;
    /**
     * @var ProjectService
     */
    private $projectService;
    /**
     * @var ProgramService
     */
    private $programService;
    /**
     * @var OrganisationService
     */
    private $organisationService;
    /**
     * @var ArticleService
     */
    private $articleService;

    public function __construct(
        Application $application,
        HelperPluginManager $helperPluginManager,
        TwigRenderer $renderer,
        AuthenticationService $authenticationService,
        TranslatorInterface $translator,
        CountryService $countryService,
        CountrySearchService $countrySearchService,
        ProjectService $projectService,
        ProjectSearchService $projectSearchService,
        ProgramService $programService,
        OrganisationService $organisationService,
        ArticleService $articleService
    ) {
        parent::__construct(
            $application,
            $helperPluginManager,
            $renderer,
            $authenticationService,
            $translator
        );

        $this->countryService = $countryService;
        $this->countrySearchService = $countrySearchService;
        $this->projectService = $projectService;
        $this->projectSearchService = $projectSearchService;
        $this->programService = $programService;
        $this->organisationService = $organisationService;
        $this->articleService = $articleService;
    }

    public function __invoke(Content $content): ?string
    {
        $params = $this->extractContentParam($content);

        $country = $this->getCountryByParams($params);

        switch ($content->getHandler()->getHandler()) {
            case 'country':
                if (null === $country) {
                    $this->response->setStatusCode(Response::STATUS_CODE_404);

                    return 'The selected country cannot be found';
                }

                $this->getHeadTitle()->append($this->translate("txt-country"));
                $this->getHeadTitle()->append($country->getCountry());

                $countryLink = $this->helperPluginManager->get(CountryLink::class);
                $this->getHeadMeta()->setProperty('og:type', $this->translate("txt-country"));
                $this->getHeadMeta()->setProperty('og:title', $country->getCountry());
                $this->getHeadMeta()->setProperty('og:url', $countryLink($country, 'view', 'social'));

                return $this->parseCountry($country);
            case 'country_list':
                return $this->parseCountryList();
            case 'country_list_itac':
                $this->getHeadTitle()->append($this->translate("txt-itac-countries-in-itea"));

                return $this->parseCountryListItac();
            case 'country_organisation':
                if (null === $country) {
                    $this->response->setStatusCode(Response::STATUS_CODE_404);

                    return 'The selected country cannot be found';
                }

                $this->getHeadTitle()->append($country->getCountry());
                $this->getHeadTitle()->append($this->translate("txt-organisations"));

                return $this->parseOrganisationList($country, $params['page']);

            case 'country_project':
                if (null === $country) {
                    $this->response->setStatusCode(Response::STATUS_CODE_404);

                    return 'The selected country cannot be found';
                }
                $this->getHeadTitle()->append($country->getCountry());
                $this->getHeadTitle()->append($this->translate("txt-projects"));

                return $this->parseCountryProjectList($country);
            default:
                return sprintf(
                    "No handler available for <code>%s</code> in class <code>%s</code>",
                    $content->getHandler()->getHandler(),
                    __CLASS__
                );
        }
    }

    private function getCountryByParams(array $params): ?Country
    {
        $country = null;

        if (null !== $params['id']) {
            /** @var Country $country */
            $country = $this->countryService->find(Country::class, (int)$params['id']);
        }

        if (null !== $params['docRef']) {
            /** @var Country $country */
            $country = $this->countryService->findByCountryByDocRef($params['docRef']);
        }

        return $country;
    }

    private function parseCountry(Country $country): string
    {
        return $this->renderer->render(
            'cms/country/country',
            [
                'countrySearchResult' => $this->countrySearchService->findCountry($country),
                'country'             => $country,
                'countryService'      => $this->countryService,
                'map'                 => $this->parseMap($country),
                'funder'              => $this->programService->findFunderByCountry($country),
                'articles'            => $this->articleService->findArticlesByCountry($country, 15)
            ]
        );
    }

    private function parseMap(Country $country): string
    {
        $mapOptions = [
            'clickable' => true,
            'colorMin'  => '#005C00',
            'colorMax'  => '#00a651',
            'focusOn'   => ['x' => 0.5, 'y' => 0.5, 'scale' => 1.1], // Slight zoom
            'height'    => '340px',
        ];

        $countryMap = $this->helperPluginManager->get(CountryMap::class);

        return $countryMap([$country], null, $mapOptions);
    }

    private function parseCountryList(): string
    {
        return $this->renderer->render(
            'cms/country/list',
            [
                'countries'      => $this->countrySearchService->findCountriesOnWebsite(),
                'countryService' => $this->countryService,
            ]
        );
    }

    private function parseCountryListItac(): string
    {
        return $this->renderer->render(
            'cms/country/list-itac',
            [
                'countries'      => $this->countrySearchService->findItacCountries(),
                'countryService' => $this->countryService,
            ]
        );
    }

    /**
     * @param Country $country
     * @param int     $page
     *
     * @return string
     * @deprecated This overview can be replaced by: https://dev1.itea4.org/project-partners.html?query=&facet%5Bcountry%5D%5B%5D=Italy
     */
    private function parseOrganisationList(Country $country, int $page = 1): string
    {
        $organisationQuery = $this->organisationService->findOrganisationByCountry($country, true, true);

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($organisationQuery)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 25);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        return $this->renderer->render(
            'cms/country/organisation',
            [
                'country'   => $country,
                'paginator' => $paginator,
            ]
        );
    }

    private function parseCountryProjectList(Country $country): string
    {
        $projectSearchResult = $this->projectSearchService->findProjectByCountry($country);

        return $this->renderer->render(
            'cms/country/project',
            [
                'country'             => $country,
                'projectService'      => $this->projectService,
                'projectSearchResult' => $projectSearchResult,
            ]
        );
    }
}
