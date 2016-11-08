<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category   Challenge
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use Content\Entity\Content;
use General\Entity\Challenge;
use General\Service\GeneralService;
use Project\Service\ProjectService;

/**
 * Class ChallengeHandler.
 */
class ChallengeHandler extends AbstractViewHelper
{
    /**
     * @var Challenge
     */
    protected $challenge;

    /**
     * @param Content $content
     *
     * @return string
     */
    public function __invoke(Content $content)
    {
        $this->extractContentParam($content);
        switch ($content->getHandler()->getHandler()) {
            case 'challenge':
                $this->getHelperPluginManager()->get('headtitle')->append($this->translate("txt-challenge"));
                $this->getHelperPluginManager()->get('headtitle')->append($this->getChallenge()->getChallenge());

                return $this->parseChallenge();
            case 'challenge_list':
                return $this->parseChallengeList();
            case 'challenge_project':
                return $this->parseChallengeProjectList($this->getChallenge());
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
        if (! is_null($this->getRouteMatch()->getParam('docRef'))) {
            $this->setChallengeDocRef($this->getRouteMatch()->getParam('docRef'));
        }
        foreach ($content->getContentParam() as $param) {
            /*
             * When the parameterId is 0 (so we want to get the article from the URL
             */
            switch ($param->getParameter()->getParam()) {
                case 'docRef':
                    if (! is_null($docRef = $this->getRouteMatch()->getParam($param->getParameter()->getParam()))) {
                        $this->setChallengeDocRef($docRef);
                    }
                    break;
                default:
                    $this->setChallengeId($param->getParameterId());
                    break;
            }
        }
    }

    /**
     * @param $docRef
     *
     * @return Challenge
     */
    public function setChallengeDocRef($docRef)
    {
        $this->setChallenge($this->getGeneralService()->findEntityByDocRef(Challenge::class, $docRef));
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService()
    {
        return $this->getServiceManager()->get(GeneralService::class);
    }

    /**
     * @param $id
     *
     * @return Challenge
     */
    public function setChallengeId($id)
    {
        $this->setChallenge($this->getGeneralService()->findChallengeById($id));
    }

    /**
     * @return Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param Challenge $challenge
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return string
     */
    public function parseChallenge()
    {
        return $this->getRenderer()->render('general/partial/entity/challenge', ['challenge' => $this->getChallenge()]);
    }

    /**
     * @return string
     */
    public function parseChallengeList()
    {
        $challenge = $this->getGeneralService()->findAll(Challenge::class);

        return $this->getRenderer()->render('general/partial/list/challenge', ['challenge' => $challenge]);
    }

    /**
     * @param Challenge $challenge
     *
     * @return string
     */
    public function parseChallengeProjectList(Challenge $challenge)
    {
        $projects = $this->getProjectService()->findProjectByChallenge($challenge);

        return $this->getRenderer()->render(
            'general/partial/list/project-challenge',
            [
            'projects'       => $projects,
            'projectService' => $this->getProjectService(),
            'challenge'      => $challenge,
            ]
        );
    }

    /**
     * @return ProjectService
     */
    public function getProjectService()
    {
        return $this->getServiceManager()->get(ProjectService::class);
    }
}
