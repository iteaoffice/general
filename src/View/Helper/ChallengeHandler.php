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
    public function __invoke(Content $content): string
    {
        $this->extractContentParam($content);
        switch ($content->getHandler()->getHandler()) {
            case 'challenge':
                $this->getHelperPluginManager()->get('headtitle')->append($this->translate("txt-challenge"));
                $this->getHelperPluginManager()->get('headtitle')->append($this->getChallenge()->getChallenge());

                return $this->parseChallenge($this->getChallenge());
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
    public function extractContentParam(Content $content): void
    {
        /**
         * Go over the handler params and try to see if it is hardcoded or just set via the route
         */
        foreach ($content->getHandler()->getParam() as $parameter) {
            switch ($parameter->getParam()) {
                case 'docRef':
                    $docRef = $this->findParamValueFromContent($content, $parameter);

                    if (!is_null($docRef)) {
                        $this->setChallengeDocRef($docRef);
                    }
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
     * @param $docRef
     */
    public function setChallengeDocRef($docRef): void
    {
        /** @var Challenge $challenge */
        $challenge = $this->getGeneralService()->findEntityByDocRef(Challenge::class, $docRef);

        if (!is_null($challenge)) {
            $this->setChallenge($challenge);
        }
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService(): GeneralService
    {
        return $this->getServiceManager()->get(GeneralService::class);
    }

    /**
     * @return Challenge
     */
    public function getChallenge(): Challenge
    {
        return $this->challenge;
    }

    /**
     * @param Challenge $challenge
     * @return ChallengeHandler
     */
    public function setChallenge(Challenge $challenge): ChallengeHandler
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * @param Challenge $challenge
     * @return string
     */
    public function parseChallenge(Challenge $challenge): string
    {
        return $this->getRenderer()->render('general/partial/entity/challenge', ['challenge' => $challenge]);
    }

    /**
     * @return string
     */
    public function parseChallengeList(): string
    {
        $challenge = $this->getGeneralService()->findAll(Challenge::class);

        return $this->getRenderer()->render('general/partial/list/challenge', ['challenge' => $challenge]);
    }

    /**
     * @param Challenge $challenge
     *
     * @return string
     */
    public function parseChallengeProjectList(Challenge $challenge): string
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
    public function getProjectService(): ProjectService
    {
        return $this->getServiceManager()->get(ProjectService::class);
    }
}
