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
use General\Entity\Challenge;
use General\Service\GeneralService;
use Project\Service\ProjectService;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Response;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Application;
use Zend\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class ProjectHandler
 *
 * @package Project\View\Handler
 */
final class ChallengeHandler extends AbstractHandler
{
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

        $this->generalService = $generalService;
        $this->projectService = $projectService;
    }

    public function __invoke(Content $content = null)
    {
        if (null === $content) {
            return $this;
        }

        $params = $this->extractContentParam($content);

        $challenge = $this->getChallengeByParams($params);

        switch ($content->getHandler()->getHandler()) {
            case 'challenge':
                if (null === $challenge) {
                    $this->response->setStatusCode(Response::STATUS_CODE_404);

                    return 'The selected challenge cannot be found';
                }

                $this->getHeadTitle()->append($this->translate("txt-challenge"));
                $this->getHeadTitle()->append($challenge->getChallenge());

                if (!empty($challenge->getCss())) {
                    $this->getHeadStyle()->appendStyle($challenge->getCss());
                }


                return $this->parseChallenge($challenge);
            case 'challenge_list':
                return $this->parseChallengeList();
            default:
                return sprintf(
                    "No handler available for <code>%s</code> in class <code>%s</code>",
                    $content->getHandler()->getHandler(),
                    __CLASS__
                );
        }
    }

    private function getChallengeByParams(array $params): ?Challenge
    {
        $challenge = null;

        if (null !== $params['id']) {
            /** @var Challenge $challenge */
            $challenge = $this->generalService->findChallengeById((int)$params['id']);
        }

        if (null !== $params['docRef']) {
            $challenge = $this->generalService->findChallengeByDocRef($params['docRef']);
        }

        return $challenge;
    }

    private function parseChallenge(Challenge $challenge): string
    {
        return $this->renderer->render(
            'cms/challenge/challenge',
            [
                'challenge' => $challenge,
                'projects'  => $this->projectService->findProjectByChallenge($challenge)
            ]
        );
    }

    private function parseChallengeList(): string
    {
        $challenge = $this->generalService->findAll(Challenge::class);

        return $this->renderer->render('cms/challenge/list', ['challenge' => $challenge]);
    }

    public function parseChallengeListFrontpage(): string
    {
        $challenge = $this->generalService->findAll(Challenge::class);

        return $this->renderer->render('cms/challenge/list-frontpage', ['challenge' => $challenge]);
    }
}
