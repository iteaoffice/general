<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Challenge
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/challenge for the canonical source repository
 */

namespace General\View\Helper\Challenge\Idea\Poster;

use General\Entity\Challenge;
use General\ValueObject\Image\Image;
use General\ValueObject\Image\ImageDecoration;
use General\View\Helper\AbstractImage;

/**
 * Class Icon
 * @package General\View\Helper\Challenge\Idea\Poster
 */
final class Icon extends AbstractImage
{
    public function __invoke(
        Challenge $challenge,
        int $width = null,
        string $show = ImageDecoration::SHOW_RAW
    ): string {
        $icon = $challenge->getIdeaPosterIcon();

        if (null === $icon) {
            return '';
        }

        $linkParams          = [];
        $linkParams['route'] = 'image/challenge-idea-poster-icon';
        $linkParams['width'] = $width;
        $linkParams['show']  = $show;

        $routeParams = [
            'id'          => $icon->getId(),
            'ext'         => $icon->getContentType()->getExtension(),
            'last-update' => $icon->getDateUpdated()->getTimestamp(),
        ];

        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Image::fromArray($linkParams));
    }
}
