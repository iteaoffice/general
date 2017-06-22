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
     * @param int $width
     *
     * @return string
     */
    public function __invoke(Country $country, $width = 20): string
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
        $this->addRouterParam('iso3', strtolower((string) $country->getIso3()));
        $this->addRouterParam('ext', 'png');
        $this->setImageId('country_flag_' . $country->getIso3());

        $this->setWidth($width);

        return $this->createImageUrl();
    }
}
