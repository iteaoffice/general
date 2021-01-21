<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper\Country;

use Content\Entity\Route;
use General\Entity\Country;
use General\ValueObject\Link\Link;
use General\View\Helper\AbstractLink;

/**
 * Class CountryLink
 * @package General\View\Helper
 */
final class CountryLink extends AbstractLink
{
    public function __invoke(
        Country $country = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $country ??= new Country();

        $routeParams = [];
        $showOptions = [];
        if (! $country->isEmpty()) {
            $routeParams['id']     = $country->getId();
            $routeParams['docRef'] = $country->getDocRef();
            $showOptions['name']   = $country->getCountry();
            $showOptions['iso3']   = $country->getIso3();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon'  => 'fas fa-plus',
                    'route' => 'zfcadmin/country/new',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-country')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon'  => 'far fa-edit',
                    'route' => 'zfcadmin/country/edit',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-country')
                ];
                break;
            case 'view-admin':
                $linkParams = [
                    'icon'  => 'fa-globe',
                    'route' => 'zfcadmin/country/view',
                    'text'  => $showOptions[$show] ?? $country->getCountry()
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon'  => 'fa-globe',
                    'route' => Route::parseRouteName(Route::DEFAULT_ROUTE_COUNTRY),
                    'text'  => $showOptions[$show] ?? $country->getCountry()
                ];
                break;
        }

        $linkParams['action']      = $action;
        $linkParams['show']        = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
