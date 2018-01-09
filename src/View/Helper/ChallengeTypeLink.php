<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\Challenge\Type;

/**
 * Create a link to an type.
 *
 * @type   type
 */
class ChallengeTypeLink extends LinkAbstract
{
    /**
     * @var Type
     */
    protected $type;

    /**
     * @param Type|null $type
     * @param string $action
     * @param string $show
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        Type $type = null,
        $action = 'view',
        $show = 'name'
    ): string {
        $this->setType($type);
        $this->setAction($action);
        $this->setShow($show);

        $this->addRouterParam('id', $this->getType()->getId());
        $this->setShowOptions(['name' => $this->getType()->getType()]);

        return $this->createLink();
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        if (\is_null($this->type)) {
            $this->type = new Type();
        }

        return $this->type;
    }

    /**
     * @param Type $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @throws \Exception
     */
    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'new':
                $this->setRouter('zfcadmin/challenge/type/new');
                $this->setText($this->translate("txt-new-challenge-type"));
                break;
            case 'list':
                $this->setRouter('zfcadmin/challenge/type/list');
                $this->setText($this->translate("txt-list-challenge-types"));
                break;
            case 'view-admin':
                $this->setRouter('zfcadmin/challenge/type/view');
                $this->setText(sprintf($this->translate("txt-view-challenge-type-%s"), $this->getType()));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/challenge/type/edit');
                $this->setText(sprintf($this->translate("txt-edit-challenge-type-%s"), $this->getType()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }
}
