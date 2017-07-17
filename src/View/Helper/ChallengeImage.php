<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Challenge
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/challenge for the canonical source repository
 */

namespace General\View\Helper;

use General\Entity\Challenge;

/**
 * Class ChallengeImage
 * @package Challenge\View\Helper
 */
class ChallengeImage extends ImageAbstract
{
    /**
     * @param Challenge $challenge
     * @param null $width
     * @param bool $responsive
     * @param array $classes
     * @return string
     */
    public function __invoke(
        Challenge $challenge,
        $width = null,
        $responsive = true,
        $classes = []
    ): string {
        $image = $challenge->getImage();

        if (is_null($image) || is_null($image->getContentType())) {
            return '';
        }
        if (null !== $classes && !is_array($classes)) {
            $classes = [$classes];
        } elseif (null === $classes) {
            $classes = [];
        }

        if ($responsive) {
            $classes[] = 'img-responsive';
        }

        $this->setRouter('assets/challenge-image');

        $this->addRouterParam('ext', $image->getContentType()->getExtension());
        $this->addRouterParam('id', $image->getId());

        $this->setImageId('challenge_image_' . $challenge->getId());

        if (!is_null($width)) {
            $this->addRouterParam('width', $width);
        }

        return $this->createImageUrl();
    }
}
