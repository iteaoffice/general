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

use General\Entity\Currency;

/**
 * Create a link to an currency.
 *
 * @category   General
 */
class CurrencyLink extends LinkAbstract
{
    public function __invoke(
        Currency $currency = null,
        $action = 'view',
        $show = 'name',
        $alternativeShow = null
    ): string {
        $this->setCurrency($currency);
        $this->setAction($action);
        $this->setShow($show);
        $this->setAlternativeShow($alternativeShow);
        $this->addRouterParam('id', $this->getCurrency()->getId());

        $this->setShowOptions(
            [
                'name'    => $this->getCurrency()->getName(),
                'symbol'  => $this->getCurrency()->getSymbol(),
                'iso4217' => $this->getCurrency()->getIso4217(),
            ]
        );

        return $this->createLink();
    }

    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/currency/list');
                $this->setText(sprintf($this->translate('txt-currency-list')));
                break;
            case 'new':
                $this->setRouter('zfcadmin/currency/new');
                $this->setText(sprintf($this->translate('txt-create-new-currency')));
                break;
            case 'view':
                $this->setRouter('zfcadmin/currency/view');
                $this->setText(sprintf($this->translate('txt-view-currency-%s'), $this->getCurrency()));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/currency/edit');
                $this->setText(sprintf($this->translate('txt-edit-currency-%s'), $this->getCurrency()));
                break;
        }
    }
}
