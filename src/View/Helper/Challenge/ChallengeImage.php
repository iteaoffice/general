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

namespace General\View\Helper\Challenge;

use General\Entity\Challenge;
use General\ValueObject\Image\Image;
use General\ValueObject\Image\ImageDecoration;
use General\View\Helper\AbstractImage;

/**
 * Class ChallengeImage
 * @package General\View\Helper
 */
final class ChallengeImage extends AbstractImage
{
    public function __invoke(
        Challenge $challenge,
        int $width = null,
        string $show = ImageDecoration::SHOW_IMAGE
    ): string {
        $image = $challenge->getImage();

        if (null === $image) {
            return '';
        }

        $linkParams = [];
        $linkParams['route'] = 'image/challenge-image';
        $linkParams['show'] = $show;

        $routeParams = [
            'id' => $image->getId(),
            'ext' => $image->getContentType()->getExtension(),
            'last-update' => $image->getDateUpdated()->getTimestamp(),
        ];

        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Image::fromArray($linkParams));
    }
}
