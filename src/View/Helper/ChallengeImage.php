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
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        Challenge $challenge,
        $width = null,
        $responsive = true,
        $classes = []
    ): string {
        $image = $challenge->getImage();

        if (\is_null($image)) {
            return '';
        }

        $this->setRouter('image/challenge-image');
        $this->addRouterParam('id', $image->getId());
        $this->addRouterParam('ext', $image->getContentType()->getExtension());
        $this->addRouterParam('last-update', $image->getDateUpdated()->getTimestamp());
        $this->setImageId('challenge_image_' . $image->getId());

        $this->setWidth($width);

        return $this->createImageUrl();
    }
}
