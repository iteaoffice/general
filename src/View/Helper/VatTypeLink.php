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
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\Vat;
use General\Entity\VatType;

/**
 * Create a link to an vatType.
 *
 * @category   General
 */
class VatTypeLink extends LinkAbstract
{
    /**
     * @var Vat
     */
    protected $vatType;

    /**
     * @param VatType $vatType
     * @param string $action
     * @param string $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        VatType $vatType = null,
        $action = 'view',
        $show = 'name'
    ) {
        $this->setVatType($vatType);
        $this->setAction($action);
        $this->setShow($show);

        if (!\is_null($vatType)) {
            $this->addRouterParam('id', $vatType->getId());
            $this->setShowOptions(['type' => $vatType->getType()]);
        }

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
                $this->setRouter('zfcadmin/vat-type/list');
                $this->setText($this->translate("txt-vat-type-list"));
                break;
            case 'new':
                $this->setRouter('zfcadmin/vat-type/new');
                $this->setText($this->translate("txt-new-vat-type"));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/vat-type/edit');
                $this->setText(sprintf($this->translate("txt-edit-vat-type-%s"), $this->getVatType()));
                break;
            case 'view':
                $this->setRouter('zfcadmin/vat-type/view');
                $this->setText(sprintf($this->translate("txt-view-vat-type-%s"), $this->getVatType()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }

    /**
     * @return Vat
     */
    public function getVatType()
    {
        return $this->vatType;
    }

    /**
     * @param VatType $vatType
     */
    public function setVatType($vatType)
    {
        $this->vatType = $vatType;
    }
}
