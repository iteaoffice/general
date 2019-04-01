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
 * Class CountryFlag
 *
 * @package General\View\Helper
 */
final class CountryFlag extends ImageAbstract
{
    public function __invoke(Country $country, $width = 20): string
    {
        $flag = $country->getFlag();
        if (null === $flag) {
            return '';
        }

        $this->setRouter('image/country-flag');
        $this->addRouterParam('id', $country->getId());
        $this->addRouterParam('ext', 'png');
        $this->setImageId('country_flag_' . $country->getIso3());

        $this->setWidth($width);

        return $this->createImageUrl();
    }
}
