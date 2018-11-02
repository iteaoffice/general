<?php

/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\View\Helper;

use Content\Entity\Route;
use General\Entity\Country;

/**
 * Create a link to an country.
 *
 * @category   General
 */
class CountryLink extends LinkAbstract
{

    /**
     * @param Country $country
     * @param string  $action
     * @param string  $show
     * @param string  $alternativeShow
     *
     * @return string
     *
     * @throws \Exception
     */
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
                'name'   => $this->getCountry(),
                'iso3'   => $this->getCountry()->getIso3(),
                'more'   => $this->translate("txt-read-more"),
                'custom' => $this->getAlternativeShow(),
                'flag'   => $this->getCountryFlag($this->getCountry(), 40),
            ]
        );

        return $this->createLink();
    }

    /**
     *
     */
    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'view':
                $this->setRouter(Route::parseRouteName(Route::DEFAULT_ROUTE_COUNTRY));
                $this->setText(sprintf($this->translate("txt-view-country-%s"), $this->getCountry()));
                break;
            case 'view-project':
                $this->setRouter(Route::parseRouteName(Route::DEFAULT_ROUTE_COUNTRY_PROJECT));
                $this->setText(sprintf($this->translate("txt-view-project-for-country-%s"), $this->getCountry()));
                break;
            case 'view-organisation':
                //@todo

                $this->setRouter(Route::parseRouteName(Route::DEFAULT_ROUTE_COUNTRY_ORGANISATION));
                $this->setText(sprintf($this->translate("txt-view-organisation-for-country-%s"), $this->getCountry()));
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
            default:
                throw new \InvalidArgumentException(
                    sprintf(
                        "%s is an incorrect action for %s",
                        $this->getAction(),
                        __CLASS__
                    )
                );
        }
    }
}
