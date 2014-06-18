<?php

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\View\Helper;

use General\Entity\Challenge;

/**
 * Create a link to an challenge
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
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
        $this->addRouterParam('entity', 'Challenge');
        if (!is_null($challenge)) {
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
     * Parse the action
     *
     * @throws \Exception
     */
    public function parseAction()
    {
        switch ($this->getAction()) {
            case 'new':
                $this->setRouter('zfcadmin/challenge-manager/new');
                $this->setText($this->translate("txt-new-challenge"));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/challenge-manager/edit');
                $this->setText(sprintf($this->translate("txt-edit-challenge-%s"), $this->getChallenge()));
                break;
            case 'view':
                $this->setRouter('route-' . $this->getChallenge()->get("underscore_full_entity_name"));
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
