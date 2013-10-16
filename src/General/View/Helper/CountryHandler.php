<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Country
 * @package     View
 * @subpackage  Helper
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */

namespace General\View\Helper;

use Zend\View\HelperPluginManager;
use Zend\View\Helper\AbstractHelper;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Mvc\Router\Http\RouteMatch;

use General\Entity\Country;
use General\Service\GeneralService;

use Contact\Service\ContactService;

use Project\Service\ProjectService;

use Content\Entity\Handler;

/**
 * Class CountryHandler
 * @package Country\View\Helper
 */
class CountryHandler extends AbstractHelper
{
    /**
     * @var Country
     */
    protected $country;
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var ProjectService
     */
    protected $projectService;
    /**
     * @var ContactService
     */
    protected $contactService;
    /**
     * @var Handler
     */
    protected $handler;
    /**
     * @var CountryMap
     */
    protected $countryMap;
    /**
     * @var RouteMatch
     */
    protected $routeMatch = null;

    /**
     * @param HelperPluginManager $helperPluginManager
     */
    public function __construct(HelperPluginManager $helperPluginManager)
    {
        $this->generalService = $helperPluginManager->getServiceLocator()->get('general_generic_service');
        $this->projectService = $helperPluginManager->getServiceLocator()->get('project_project_service');
        $this->contactService = $helperPluginManager->getServiceLocator()->get('contact_contact_service');
        $this->routeMatch     = $helperPluginManager->getServiceLocator()
            ->get('application')
            ->getMvcEvent()
            ->getRouteMatch();
        $this->countryMap     = $helperPluginManager->get('countryMap');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render()
    {


        switch ($this->getHandler()->getHandler()) {

            case 'country':

                $this->getView()->headTitle()->append("Country");
                $this->getView()->headTitle()->append($this->getCountry()->getCountry());

                return $this->parseCountry();
                break;

            case 'country_map':
                $countryMap = $this->countryMap;

                return $countryMap->__invoke(array($this->getCountry()), $this->getCountry());
                break;

            case 'country_funder':

                return $this->parseCountryFunderList($this->getCountry());
                break;

            case 'country_list':

                $this->getView()->headTitle()->append('List');
                $page = $this->routeMatch->getParam('page');

                return $this->parseCountryList($page);
                break;
            case 'country_project':
                return $this->parseCountryProjectList($this->getCountryService());
                break;

            default:
                return sprintf("No handler available for <code>%s</code> in class <code>%s</code>",
                    $this->getHandler()->getHandler(),
                    __CLASS__);
        }
    }

    /**
     * @return string
     */
    public function parseCountryList()
    {
        $country = $this->generalService->findAll('country');

        return $this->getView()->render('general/partial/list/country',
            array('country' => $country));
    }

    /**
     * @return string
     */
    public function parseCountry()
    {
        return $this->getView()->render('general/partial/entity/country',
            array('country' => $this->getCountry()));
    }

    /**
     * @param CountryService $countryService
     *
     * @return string
     */
    public function parseCountryProjectList(CountryService $countryService)
    {
        $projects = $this->projectService->findProjectByCountry($countryService->getCountry());

        return $this->getView()->render('general/partial/list/project.twig', array('projects' => $projects));
    }

    /**
     * @param Country $country
     */
    public function parseCountryFunderList(Country $country)
    {
        $funder = $this->contactService->findFundersByCountry($country);
        var_dump($funder);
    }

    /**
     * @param \Content\Entity\Handler $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return \Content\Entity\Handler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $id
     *
     * @return Country
     */
    public function setCountryId($id)
    {
        $this->setCountry($this->generalService->findCountryById($id));

        return $this->getCountry();
    }

    /**
     * @param $iso3
     *
     * @return Country
     */
    public function setCountryIso3($iso3)
    {
        $this->setCountry($this->generalService->findCountryByIso3($iso3));

        return $this->getCountry();
    }
}
