<?php

/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\View\Helper;

use Exception;
use General\Entity\Password;
use InvalidArgumentException;

/**
 * Create a link to an password.
 *
 * @category   General
 */
class PasswordLink extends LinkAbstract
{
    /**
     * @param Password $password
     * @param string   $action
     * @param string   $show
     * @param string   $alternativeShow
     *
     * @return string
     *
     * @throws Exception
     */
    public function __invoke(
        Password $password = null,
        $action = 'view',
        $show = 'name',
        $alternativeShow = null
    ): string {
        $this->setPassword($password);
        $this->setAction($action);
        $this->setShow($show);
        $this->setAlternativeShow($alternativeShow);
        $this->addRouterParam('id', $this->getPassword()->getId());

        $this->setShowOptions(
            [
                'description' => $this->getPassword()->getDescription(),
            ]
        );

        return $this->createLink();
    }

    /**
     * @return string|void
     */
    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/password/list');
                $this->setText(sprintf($this->translate('txt-password-list')));
                break;
            case 'new':
                $this->setRouter('zfcadmin/password/new');
                $this->setText(sprintf($this->translate('txt-create-new-password')));
                break;
            case 'view':
                $this->setRouter('zfcadmin/password/view');
                $this->setText(sprintf($this->translate('txt-view-password-%s'), $this->getPassword()));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/password/edit');
                $this->setText(sprintf($this->translate('txt-edit-password-%s'), $this->getPassword()));
                break;
            default:
                throw new InvalidArgumentException(
                    sprintf(
                        "%s is an incorrect action for %s",
                        $this->getAction(),
                        __CLASS__
                    )
                );
        }
    }
}
