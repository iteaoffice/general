<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\View\Helper;

use General\Entity\Vat;

/**
 * Create a link to an vat.
 *
 * @category   General
 */
class VatLink extends LinkAbstract
{
    /**
     * @var Vat
     */
    protected $vat;

    /**
     * @param Vat    $vat
     * @param string $action
     * @param string $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        Vat $vat = null,
        $action = 'view',
        $show = 'code'
    ) {
        $this->setVat($vat);
        $this->setAction($action);
        $this->setShow($show);
        $this->addRouterParam('entity', 'Vat');
        if (! is_null($vat)) {
            $this->addRouterParam('id', $vat->getId());
            $this->setShowOptions(['code' => $vat->getCode(),]);
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
                $this->setRouter('zfcadmin/vat/list');
                $this->setText($this->translate("txt-vat-list"));
                break;
            case 'new':
                $this->setRouter('zfcadmin/vat/new');
                $this->setText($this->translate("txt-new-vat"));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/vat/edit');
                $this->setText(sprintf($this->translate("txt-edit-vat-%s"), $this->getVat()));
                break;
            case 'view':
                $this->setRouter('zfcadmin/vat/view');
                $this->setText(sprintf($this->translate("txt-view-vat-%s"), $this->getVat()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }

    /**
     * @return Vat
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param Vat $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }
}
