<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\Currency;
use General\Entity\ExchangeRate;
use General\ValueObject\Link\Link;

/**
 * Class ExchangeRateLink
 * @package General\View\Helper
 */
final class ExchangeRateLink extends AbstractLink
{
    public function __invoke(
        ExchangeRate $exchangeRate = null,
        string $action = 'view',
        string $show = 'name',
        Currency $currency = null
    ): string {
        $exchangeRate ??= new ExchangeRate();

        $routeParams = [];
        $showOptions = [];
        if (! $exchangeRate->isEmpty()) {
            $routeParams['id']   = $exchangeRate->getId();
            $showOptions['name'] = $exchangeRate->getRate();
        }

        if (null !== $currency) {
            $routeParams['currencyId'] = $currency->getId();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon'  => 'fas fa-plus',
                    'route' => 'zfcadmin/currency/exchange-rate/new',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-exchange-rate')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon'  => 'far fa-edit',
                    'route' => 'zfcadmin/currency/exchange-rate/edit',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-exchange-rate')
                ];
                break;
        }

        $linkParams['action']      = $action;
        $linkParams['show']        = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
