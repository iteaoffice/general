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

use Content\Entity\Route;
use General\Entity\Country;

/**
 * Class CountryLink
 *
 * @package General\View\Helper
 */
final class CountryLink extends LinkAbstract
{
    public function __invoke(
        Country $country = null,
        $action = 'view',
        $show = 'name',
        $alternativeShow = null
    ): string {
        $this->setCountry($country);
        $this->setAction($action);
        $this->setShow($show);
        $this->setAlternativeShow($alternativeShow);
        $this->addRouterParam('id', $this->getCountry()->getId());
        $this->addRouterParam('docRef', $this->getCountry()->getDocRef());
        $this->setShowOptions(
            [
                'name' => $this->getCountry(),
                'iso3' => $this->getCountry()->getIso3(),
            ]
        );

        return $this->createLink();
    }

    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'view':
                $this->setRouter(Route::parseRouteName(Route::DEFAULT_ROUTE_COUNTRY));
                $this->setText(sprintf($this->translate("txt-view-country-%s"), $this->getCountry()));
                break;
            case 'list':
                $this->setRouter('zfcadmin/country/list');
                $this->setText(sprintf($this->translate('txt-country-list')));
                break;
            case 'new':
                $this->setRouter('zfcadmin/country/new');
                $this->setText(sprintf($this->translate('txt-create-new-country')));
                break;
            case 'view-admin':
                $this->setRouter('zfcadmin/country/view');
                $this->setText(sprintf($this->translate('txt-view-country-%s'), $this->getCountry()));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/country/edit');
                $this->setText(sprintf($this->translate('txt-edit-country-%s'), $this->getCountry()));
                break;
        }
    }
}
