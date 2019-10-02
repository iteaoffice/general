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
use General\Entity\ExchangeRate;
use InvalidArgumentException;

/**
 * Class ExchangeRateLink
 *
 * @package General\View\Helper
 */
class ExchangeRateLink extends LinkAbstract
{
    /**
     * @param ExchangeRate|null $exchangeRate
     * @param string            $action
     * @param string            $show
     * @param null              $alternativeShow
     * @param Currency|null     $currency
     *
     * @return string
     */
    public function __invoke(
        ExchangeRate $exchangeRate = null,
        $action = 'view',
        $show = 'name',
        Currency $currency = null
    ): string {
        $this->setCurrency($currency);
        $this->setExchangeRate($exchangeRate);
        $this->setAction($action);
        $this->setShow($show);

        $this->addRouterParam('id', $this->getExchangeRate()->getId());
        $this->addRouterParam('currencyId', $this->getCurrency()->getId());

        return $this->createLink();
    }

    /**
     * @return string|void
     */
    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'new':
                $this->setRouter('zfcadmin/currency/exchange-rate/new');
                $this->setText(
                    sprintf($this->translate('txt-add-new-exchange-rate-for-%s'), $this->getCurrency()->getName())
                );
                break;
            case 'edit':
                $this->setRouter('zfcadmin/currency/exchange-rate/edit');
                $this->setText(
                    sprintf(
                        $this->translate('txt-exchange-rate-for-currency-%s'),
                        $this->getExchangeRate()->getCurrency()
                    )
                );
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
