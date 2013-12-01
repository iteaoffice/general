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
use Program\Service\ProgramService;
use Contact\Service\ContactService;
use Project\Service\ProjectService;
use Organisation\Service\OrganisationService;


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
     * @var OrganisationService
     */
    protected $organisationService;
    /**
     * @var ContactService
     */
    protected $contactService;
    /**
     * @var ProgramService
     */
    protected $programService;
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
        $this->generalService      = $helperPluginManager->getServiceLocator()->get('general_general_service');
        $this->projectService      = $helperPluginManager->getServiceLocator()->get('project_project_service');
        $this->contactService      = $helperPluginManager->getServiceLocator()->get('contact_contact_service');
        $this->organisationService = $helperPluginManager->getServiceLocator()
            ->get('organisation_organisation_service');
        $this->programService      = $helperPluginManager->getServiceLocator()->get('program_program_service');
        $this->routeMatch          = $helperPluginManager->getServiceLocator()
            ->get('application')
            ->getMvcEvent()
            ->getRouteMatch();
        $this->countryMap          = $helperPluginManager->get('countryMap');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render()
    {

        $translate = $this->getView()->plugin('translate');

        switch ($this->getHandler()->getHandler()) {

            case 'country':

                $this->getView()->headTitle()->append($translate("txt-country"));
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

            case 'country_metadata':

                return $this->parseCountryMetadata($this->getCountry());
                break;

            case 'country_list':


                $this->getView()->headTitle()->append($translate("txt-countries-in-itea"));
                $page = $this->routeMatch->getParam('page');

                return $this->parseCountryList($page);
                break;

            case 'country_organisation':

                return $this->parseOrganisationList();
                break;

            case 'country_project':
                return $this->parseCountryProjectList($this->getCountry());
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
        $countries = $this->generalService->findActiveCountries();

        return $this->getView()->render('general/partial/list/country',
            array('countries' => $countries));
    }

    /**
     * @return string
     */
    public function parseCountry()
    {


        return $this->getView()->render('general/partial/entity/country',
            array(
                'country' => $this->getCountry(),

            ));
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryProjectList(Country $country)
    {
        $projects = $this->projectService->findProjectByCountry($country);

        return $this->getView()->render('general/partial/list/project.twig', array('projects' => $projects));
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryMetadata(Country $country)
    {
        $projects      = $this->projectService->findProjectByCountry($this->getCountry());
        $organisations = $this->organisationService->findOrganisationByCountry($this->getCountry());

        return $this->getView()->render('general/partial/entity/country-metadata.twig',
            array(
                'country'       => $country,
                'projects'      => $projects,
                'organisations' => $organisations
            )
        );
    }

    /**
     * @param Country $country
     *
     * @return string
     */
    public function parseCountryFunderList(Country $country)
    {
        $funder = $this->programService->findFunderByCountry($country);

        /**
         * Parse the organisationService in to have the these functions available in the view
         */

        return $this->getView()->render('program/partial/list/funder.twig', array(
            'funder' => $funder,
        ));
    }

    /**
     * Create a list of organisations
     *
     * @return string
     */
    public function parseOrganisationList()
    {
        $organisations = $this->organisationService->findOrganisationByCountry($this->getCountry());

        /**
         * Parse the organisationService in to have the these functions available in the view
         */

        return $this->getView()->render('organisation/partial/list/organisation.twig', array(
            'organisations' => $organisations,
        ));
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
     * @param $docRef
     *
     * @return Country
     */
    public function setCountryDocRef($docRef)
    {
        $country = $this->generalService->findEntityByDocRef('country', $docRef);

        if (is_null($country)) {
            return null;
        }

        $this->setCountry($country);

        return $this->getCountry();
    }
}
