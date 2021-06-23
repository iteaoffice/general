<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace General\View\Helper\Challenge;

use General\Entity\Challenge;
use General\ValueObject\Image\Image;
use General\ValueObject\Image\ImageDecoration;
use General\View\Helper\AbstractImage;

/**
 * Class ChallengeIcon
 *
 * @package Challenge\View\Helper
 */
final class ChallengeIcon extends AbstractImage
{
    public function __invoke(
        Challenge $challenge,
        int $width = null,
        string $show = ImageDecoration::SHOW_IMAGE,
        string $type = 'gray'
    ): string {
        $icon = $challenge->getIcon();

        if (null === $icon) {
            return '';
        }

        if ($show === 'icon') {
            return str_replace(
                ['<svg', '#999999', 'st0'],
                [
                    '<svg width="' . $width . '" class="rounded-circle" style="background-color: ' . $challenge->getBackgroundColor() . '"',
                    $challenge->getFrontColor(),
                    'st_' . $challenge->getId()
                ],
                $challenge->getIcon()->parseSVG()
            );
        }

        $linkParams          = [];
        $linkParams['route'] = 'image/challenge-icon';
        $linkParams['show']  = $show;
        $linkParams['width'] = $width;

        $routeParams = [
            'id'          => $challenge->getIcon()->getId(),
            'ext'         => 'svg',
            'type'        => $type,
            'last-update' => $challenge->getIcon()->getDateUpdated()->getTimestamp(),
        ];

        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Image::fromArray($linkParams));
    }
}
