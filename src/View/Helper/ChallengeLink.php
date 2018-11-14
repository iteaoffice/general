<?php

/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\View\Helper;

use Content\Entity\Route;
use General\Entity\Challenge;

/**
 * Create a link to an challenge.
 *
 * @category   General
 */
class ChallengeLink extends LinkAbstract
{
    /**
     * @param Challenge $challenge
     * @param string    $action
     * @param string    $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        Challenge $challenge = null,
        $action = 'view',
        $show = 'name'
    ): string {
        $this->setChallenge($challenge);
        $this->setAction($action);
        $this->setShow($show);

        $this->addRouterParam('id', $this->getChallenge()->getId());
        $this->addRouterParam('docRef', $this->getChallenge()->getDocRef());
        $this->setShowOptions(
            [
                'name'      => $this->getChallenge(),
                'read-more' => $this->translate("txt-read-more"),
            ]
        );

        return $this->createLink();
    }

    /**
     * Parse the action.
     *
     * @throws \Exception
     */
    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/challenge/list');
                $this->setText($this->translate("txt-challenge-list"));
                break;
            case 'new':
                $this->setRouter('zfcadmin/challenge/new');
                $this->setText($this->translate("txt-new-challenge"));
                break;
            case 'download-pdf':
                $this->setRouter('zfcadmin/challenge/download-pdf');
                $this->setText($this->translate("txt-download-pdf"));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/challenge/edit');
                $this->setText(sprintf($this->translate("txt-edit-challenge-%s"), $this->getChallenge()));
                break;
            case 'view-admin':
                $this->setRouter('zfcadmin/challenge/view');
                $this->setText(sprintf($this->translate("txt-view-challenge-%s"), $this->getChallenge()));
                break;
            case 'view':
                $this->setRouter(Route::parseRouteName(Route::DEFAULT_ROUTE_CHALLENGE));
                $this->setText(sprintf($this->translate("txt-view-challenge-%s"), $this->getChallenge()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }
}
