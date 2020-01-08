<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\Currency;
use General\ValueObject\Link\Link;

/**
 * Class CurrencyLink
 * @package General\View\Helper
 */
final class CurrencyLink extends AbstractLink
{
    public function __invoke(
        Currency $currency = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $currency ??= new Currency();

        $routeParams = [];
        $showOptions = [];
        if (! $currency->isEmpty()) {
            $routeParams['id'] = $currency->getId();
            $showOptions['name'] = $currency->getIso4217();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fa-plus',
                    'route' => 'zfcadmin/currency/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-currency')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'fa-pencil-square-o',
                    'route' => 'zfcadmin/currency/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-currency')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fa-link',
                    'route' => 'zfcadmin/currency/view',
                    'text' => $showOptions[$show] ?? $currency->getIso4217()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
