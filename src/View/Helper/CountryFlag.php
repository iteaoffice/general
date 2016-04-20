<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use General\Entity\Country;

/**
 * Create a link to an project.
 *
 * @category   General
 */
class CountryFlag extends ImageAbstract
{
    /**
     * @param Country $country
     * @param int     $width
     *
     * @return string
     */
    public function __invoke(Country $country, $width = 20)
    {
        $flag = $country->getFlag();
        if (is_null($flag)) {
            return '';
        }

        /*
         * Reset the classes
         */
        $this->setClasses([]);

        $this->setRouter('assets/country-flag');
        $this->addRouterParam('iso3', strtolower($country->getIso3()));
        $this->addRouterParam('ext', 'png');
        $this->setImageId('country_flag_' . $country->getIso3());
        $this->setWidth($width);

        return $this->createImageUrl();
    }
}
