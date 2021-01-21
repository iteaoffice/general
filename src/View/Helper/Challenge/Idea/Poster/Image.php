<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

namespace General\View\Helper\Challenge\Idea\Poster;

use General\Entity\Challenge;
use General\ValueObject;
use General\ValueObject\Image\ImageDecoration;
use General\View\Helper\AbstractImage;

/**
 * Class ChallengeImage
 * @package General\View\Helper
 */
final class Image extends AbstractImage
{
    public function __invoke(
        Challenge $challenge,
        int $width = null,
        string $show = ImageDecoration::SHOW_RAW
    ): string {
        $image = $challenge->getIdeaPosterImage();

        if (null === $image) {
            return '';
        }

        $linkParams          = [];
        $linkParams['route'] = 'image/challenge-idea-poster-image';
        $linkParams['show']  = $show;
        $linkParams['width'] = $width;

        $routeParams = [
            'id'          => $image->getId(),
            'ext'         => $image->getContentType()->getExtension(),
            'last-update' => $image->getDateUpdated()->getTimestamp(),
        ];

        $linkParams['routeParams'] = $routeParams;

        return $this->parse(ValueObject\Image\Image::fromArray($linkParams));
    }
}
