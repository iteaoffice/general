<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category   Country
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use Contact\Service\ContactService;
use Content\Entity\Content;
use Content\Service\ArticleService;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Country;
use General\Options\ModuleOptions;
use General\Service\GeneralService;
use Organisation\Service\OrganisationService;
use Program\Service\ProgramService;
use Project\Service\ProjectService;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Paginator\Paginator;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class CountryHandler.
 */
class CountryHandler extends AbstractHelper
{
    /**
     * @var HelperPluginManager
     */
    protected $serviceLocator;
    /**
     * @var Country
     */
    protected $country;
    /**
     * @var int
     */
    protected $limit = 5;

    /***
     * @param Content $content
     *
     * @return string
     */
    public function __invoke(Content $content)
    {
        $this->extractContentParam($content);

        if (in_array($content->getHandler()->getHandler(), [
            'country',
            'country_map',
            'country_funder',
            'country_project',
            'country_metadata',
            'country_article',
        ])) {
            if (is_null($this->getCountry())) {
                $this->getServiceLocator()->get("response")->setStatusCode(404);

                return sprintf("The selected country cannot be found");
            }
        }

        switch ($content->getHandler()->getHandler()) {
            case 'country':
                $this->serviceLocator->get('headtitle')->append($this->translate("txt-country"));
                $this->serviceLocator->get('headtitle')->append($this->getCountry()->getCountry());
                $countryLink = $this->serviceLocator->get('countryLink');
                $this->serviceLocator->get('headmeta')->setProperty('og:type', $this->translate("txt-country"));
                $this->serviceLocator->get('headmeta')->setProperty('og:title', $this->getCountry()->getCountry());
                $this->serviceLocator->get('headmeta')
                    ->setProperty('og:url', $countryLink->__invoke($this->getCountry(), 'view', 'social'));

                return $this->parseCountry();

            case 'country_map':
                /*
                 * @var $countryMap CountryMap
                 */

                return $this->parseCountryMap();

            case 'country_funder':
                return $this->parseCountryFunderList($this->getCountry());

            case 'country_metadata':
                return $this->parseCountryMetadata($this->getCountry());


            case 'country_list':
                $this->serviceLocator->get('headtitle')->append($this->translate("txt-countries-in-itea"));

                return $this->parseCountryList();

            case 'country_list_itac':
                $this->serviceLocator->get('headtitle')->append($this->translate("txt-itac-countries-in-itea"));

                return $this->parseCountryListItac();

            case 'country_organisation':
                $page = $this->getRouteMatch()->getParam('page');

                $this->serviceLocator->get('headtitle')->append($this->translate("txt-country"));
                $this->serviceLocator->get('headtitle')->append($this->getCountry()->getCountry());

                return $this->parseOrganisationList($page);

            case 'country_project':
                $this->serviceLocator->get('headtitle')->append($this->translate("txt-country"));
                $this->serviceLocator->get('headtitle')->append($this->getCountry()->getCountry());

                return $this->parseCountryProjectList($this->getCountry());

            case 'country_article':
                return $this->parseCountryArticleList($this->getCountry());

            default:
                return sprintf(
                    "No handler available for <code>%s</code> in class <code>%s</code>",
                    $content->getHandler()->getHandler(),
                    __CLASS__
                );
        }
    }

    /**
     * @param Content $content
     */
    public function extractContentParam(Content $content)
    {
        //Give default the docRef to the handler, this does not harm
        if (!is_null($this->getRouteMatch()->getParam('docRef'))) {
            $this->setCountryByDocRef($this->getRouteMatch()->getParam('docRef'));
        }
        foreach ($content->getContentParam() as $param) {
            switch ($param->getParameter()->getParam()) {
                case 'docRef':
                    if (!is_null($docRef = $this->getRouteMatch()->getParam($param->getParameter()->getParam()))) {
                        $this->setCountryByDocRef($docRef);
                    }
                    break;
                case 'limit':
                    if ('0' === $param->getParameterId()) {
                        $limit = null;
                    } else {
                        $limit = $param->getParameterId();
                    }
                    $this->setLimit($limit);
                    break;
                default:
                    $this->setCountryById($param->getParameterId());
                    break;
            }
        }
    }

    /**
     * @return RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
    }

    /**
     * Get the service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
    }

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AbstractHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @param $docRef
     */
    public function setCountryByDocRef($docRef)
    {
        $this->setCountry($this->getGeneralService()->findEntityByDocRef(Country::class, $docRef));
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService()
    {
        return $this->getServiceLocator()->get(GeneralService::class);
    }

    /**
     * @param $id
     */
    public function setCountryById($id)
    {
        $this->setCountry($this->getGeneralService()->findEntityById(Country::class, $id));
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function translate($string)
    {
        return $this->serviceLocator->get('translate')->__invoke($string);
    }

    /**
     * @return string
     */
    public function parseCountry()
    {
        return $this->getRenderer()->render('general/partial/entity/country', [
            'country' => $this->getCountry(),
        ]);
    }

    /**
     * @return TwigRenderer
     */
    public function getRenderer()
    {
        return $this->getServiceLocator()->get('ZfcTwigRenderer');
    }

    /**
     * @return null|string
     */
    public function parseCountryMap()
    {
        $mapOptions = [
            'clickable' => true,
            'colorMin'  => $this->getModuleOptions()->getCountryColorFaded(),
            'colorMax'  => $this->getModuleOptions()->getCountryColor(),
            'focusOn'   => ['x' => 0.5, 'y' => 0.5, 'scale' => 1.1], // Slight zoom
            'height'    => '340px',
        ];
        /**
         * @var $countryMap CountryMap
         */
        $countryMap = $this->serviceLocator->get('countryMap');

        return $countryMap([$this->getCountry()], null, $mapOptions);
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryFunderList(Country $country)
    {
        $funder = $this->getProgramService()->findFunderByCountry($country);

        /*
         * Parse the organisationService in to have the these functions available in the view
         */

        return $this->getRenderer()->render('program/partial/list/funder', [
            'funder'         => $funder,
            'contactService' => $this->getContactService(),
        ]);
    }

    /**
     * @return ProgramService
     */
    public function getProgramService()
    {
        return $this->getServiceLocator()->get(ProgramService::class);
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryMetadata(Country $country)
    {
        $whichProjects = $this->getProjectModuleOptions()->getProjectHasVersions() ? ProjectService::WHICH_ONLY_ACTIVE
            : ProjectService::WHICH_ALL;

        $onlyActivePartners = $this->getProjectModuleOptions()->getProjectHasVersions() ? true : false;

        $projects = $this->getProjectService()->findProjectByCountry($this->getCountry(), $whichProjects);
        $organisations = $this->getOrganisationService()
            ->findOrganisationByCountry($this->getCountry(), $onlyActivePartners);

        return $this->getRenderer()->render('general/partial/entity/country-metadata', [
            'country'       => $country,
            'projects'      => $projects,
            'organisations' => $organisations->getResult(),
        ]);
    }

    /**
     * @return ProjectService
     */
    public function getProjectService()
    {
        return $this->getServiceLocator()->get(ProjectService::class);
    }

    /**
     * @return OrganisationService
     */
    public function getOrganisationService()
    {
        return $this->getServiceLocator()->get(OrganisationService::class);
    }

    /**
     * @return ContactService
     */
    public function getContactService()
    {
        return $this->getServiceLocator()->get(ContactService::class);
    }

    /**
     * Create a list of all countries which are active (have projects).
     *
     * @return string
     */
    public function parseCountryList()
    {
        $countries = $this->getGeneralService()->findActiveCountries();

        return $this->getRenderer()->render('general/partial/list/country', ['countries' => $countries]);
    }

    /**
     * Create a list of countries which are member of the itac.
     *
     * @return string
     */
    public function parseCountryListItac()
    {
        $countries = $this->getGeneralService()->findItacCountries();

        return $this->getRenderer()->render('general/partial/list/country-itac', ['countries' => $countries]);
    }

    /**
     * Create a list of organisations for the current country.
     *
     * @param  int $page
     *
     * @throws \InvalidArgumentException
     * @return string
     */
    public function parseOrganisationList($page)
    {
        if (is_null($this->getCountry())) {
            throw new \InvalidArgumentException("The country cannot be null");
        }
        $organisationQuery = $this->getOrganisationService()
            ->findOrganisationByCountry($this->getCountry(), true, true);
        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($organisationQuery)));
        $paginator->setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 15);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator->getDefaultItemCountPerPage()));

        return $this->getRenderer()->render('general/partial/list/organisation', [
            'country'   => $this->getCountry(),
            'paginator' => $paginator,
        ]);
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryProjectList(Country $country)
    {
        $whichProjects
            = $this->getProjectModuleOptions()->getProjectHasVersions() ? ProjectService::WHICH_ONLY_ACTIVE
            : ProjectService::WHICH_ALL;

        $projects = $this->getProjectService()->findProjectByCountry($country, $whichProjects);

        return $this->getRenderer()->render('general/partial/list/project', [
            'country'        => $country,
            'projectService' => $this->getProjectService(),
            'projects'       => $projects,
        ]);
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryArticleList(Country $country)
    {
        $articles = $this->getArticleService()->findArticlesByCountry($country, $this->getLimit());

        /*
         * Parse the organisationService in to have the these functions available in the view
         */

        return $this->getRenderer()->render('general/partial/list/article', [
            'country'  => $country,
            'articles' => $articles,
            'limit'    => $this->getLimit(),
        ]);
    }

    /**
     * @return ArticleService
     */
    public function getArticleService()
    {
        return $this->getServiceLocator()->get(ArticleService::class);
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->getServiceLocator()->get(ModuleOptions::class);
    }

    /**
     * @return \Project\Options\ModuleOptions
     */
    public function getProjectModuleOptions()
    {
        return $this->getServiceLocator()->get(\Project\Options\ModuleOptions::class);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
}