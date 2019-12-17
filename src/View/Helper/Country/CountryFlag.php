<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace General\View\Helper\Country;

use General\Entity\Country;
use General\ValueObject\Image\Image;
use General\ValueObject\Image\ImageDecoration;
use General\View\Helper\AbstractImage;

/**
 * Class CountryFlag
 * @package General\View\Helper
 */
final class CountryFlag extends AbstractImage
{
    public function __invoke(
        Country $country,
        int $width = null,
        string $show = ImageDecoration::SHOW_IMAGE
    ): string {
        $flag = $country->getFlag();

        if (null === $flag) {
            return '';
        }

        $linkParams = [];
        $linkParams['route'] = 'image/country-flag';
        $linkParams['show'] = $show;

        $routeParams = [
            'id' => $country->getId(),
            'ext' => 'png'
        ];

        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Image::fromArray($linkParams));
    }
}
