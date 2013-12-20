<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Challenge
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

use General\Entity\Challenge;
use General\Service\GeneralService;

use Project\Service\ProjectService;

use Content\Entity\Handler;

/**
 * Class ChallengeHandler
 * @package Challenge\View\Helper
 */
class ChallengeHandler extends AbstractHelper
{
    /**
     * @var Challenge
     */
    protected $challenge;
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var ProjectService
     */
    protected $projectService;
    /**
     * @var Handler
     */
    protected $handler;
    /**
     * @var RouteMatch
     */
    protected $routeMatch = null;

    /**
     * @param HelperPluginManager $helperPluginManager
     */
    public function __construct(HelperPluginManager $helperPluginManager)
    {
        $this->generalService = $helperPluginManager->getServiceLocator()->get('general_general_service');
        $this->projectService = $helperPluginManager->getServiceLocator()->get('project_project_service');
        $this->projectService = $helperPluginManager->getServiceLocator()->get('project_project_service');
        $this->routeMatch     = $helperPluginManager->getServiceLocator()
            ->get('application')
            ->getMvcEvent()
            ->getRouteMatch();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        $translate = $this->getView()->plugin('translate');

        switch ($this->getHandler()->getHandler()) {

            case 'challenge':

                $this->getView()->headTitle()->append($translate("txt-challenge"));
                $this->getView()->headTitle()->append($this->getChallenge()->getChallenge());

                return $this->parseChallenge();
                break;
            case 'challenge_list':

                $page = $this->routeMatch->getParam('page');

                return $this->parseChallengeList($page);
                break;
            case 'challenge_project':
                return $this->parseChallengeProjectList($this->getChallenge());
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
    public function parseChallengeList()
    {
        $challenge = $this->generalService->findAll('challenge');

        return $this->getView()->render(
            'general/partial/list/challenge',
            array('challenge' => $challenge));
    }

    /**
     * @return string
     */
    public function parseChallenge()
    {
        return $this->getView()->render(
            'general/partial/entity/challenge',
            array('challenge' => $this->getChallenge()));
    }

    /**
     * @param Challenge $challenge
     *
     * @return string
     */
    public function parseChallengeProjectList(Challenge $challenge)
    {
        $projects = $this->projectService->findProjectByChallenge($challenge);

        return $this->getView()->render('general/partial/list/project-challenge.twig',
            array(
                'projects'  => $projects,
                'challenge' => $challenge
            )
        );
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
     * @param Challenge $challenge
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param $id
     *
     * @return Challenge
     */
    public function setChallengeId($id)
    {
        $this->setChallenge($this->generalService->findChallengeById($id));

        return $this->getChallenge();
    }

    /**
     * @param $docRef
     *
     * @return Challenge
     */
    public function setChallengeDocRef($docRef)
    {
        $this->setChallenge($this->generalService->findEntityByDocRef('challenge', $docRef));

        return $this->getChallenge();
    }
}
