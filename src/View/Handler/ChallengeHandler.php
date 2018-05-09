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
use Content\Navigation\Service\UpdateNavigationService;
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
    protected $generalService;
    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * ChallengeHandler constructor.
     *
     * @param Application             $application
     * @param HelperPluginManager     $helperPluginManager
     * @param TwigRenderer            $renderer
     * @param AuthenticationService   $authenticationService
     * @param UpdateNavigationService $updateNavigationService
     * @param TranslatorInterface     $translator
     * @param GeneralService          $generalService
     * @param ProjectService          $projectService
     */
    public function __construct(
        Application $application,
        HelperPluginManager $helperPluginManager,
        TwigRenderer $renderer,
        AuthenticationService $authenticationService,
        UpdateNavigationService $updateNavigationService,
        TranslatorInterface $translator,
        GeneralService $generalService,
        ProjectService $projectService
    ) {
        parent::__construct(
            $application,
            $helperPluginManager,
            $renderer,
            $authenticationService,
            $updateNavigationService,
            $translator
        );

        $this->generalService = $generalService;
        $this->projectService = $projectService;
    }

    /**
     * @param Content $content
     *
     * @return null|string
     * @throws \Exception
     */
    public function __invoke(Content $content): ?string
    {
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

    /**
     * @param array $params
     *
     * @return Challenge|null
     */
    private function getChallengeByParams(array $params): ?Challenge
    {
        $challenge = null;

        if (null !== $params['id']) {
            /** @var Challenge $challenge */
            $challenge = $this->generalService->findChallengeById((int)$params['id']);
        }

        if (null !== $params['docRef']) {
            /** @var Challenge $challenge */
            $challenge = $this->generalService->findEntityByDocRef(Challenge::class, $params['docRef']);
        }

        return $challenge;
    }

    /**
     * @param Challenge $challenge
     *
     * @return string
     */
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

    /**
     * @return string
     */
    private function parseChallengeList(): string
    {
        $challenge = $this->generalService->findAll(Challenge::class);

        return $this->renderer->render('cms/challenge/list', ['challenge' => $challenge]);
    }
}
