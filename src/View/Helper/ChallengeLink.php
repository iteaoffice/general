<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use General\Entity\Challenge;

/**
 * Create a link to an challenge.
 *
 * @category   General
 */
class ChallengeLink extends LinkAbstract
{
    /**
     * @var Challenge
     */
    protected $challenge;

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
    ) {
        $this->setChallenge($challenge);
        $this->setAction($action);
        $this->setShow($show);

        if (! is_null($challenge)) {
            $this->addRouterParam('id', $challenge->getId());
            $this->addRouterParam('docRef', $challenge->getDocRef());
            $this->setShowOptions(
                [
                    'name' => $challenge,
                ]
            );
        }

        return $this->createLink();
    }

    /**
     * Parse the action.
     *
     * @throws \Exception
     */
    public function parseAction()
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
            case 'edit':
                $this->setRouter('zfcadmin/challenge/edit');
                $this->setText(sprintf($this->translate("txt-edit-challenge-%s"), $this->getChallenge()));
                break;
            case 'view-admin':
                $this->setRouter('zfcadmin/challenge/view');
                $this->setText(sprintf($this->translate("txt-view-challenge-%s"), $this->getChallenge()));
                break;
            case 'view':
                $this->setRouter('route-' . $this->getChallenge()->get("underscore_entity_name"));
                $this->setText(sprintf($this->translate("txt-view-challenge-%s"), $this->getChallenge()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
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
}
