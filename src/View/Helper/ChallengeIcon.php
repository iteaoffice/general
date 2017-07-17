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
 * Class ChallengeIcon
 * @package Challenge\View\Helper
 */
class ChallengeIcon extends ImageAbstract
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
        $icon = $challenge->getIcon();

        if (is_null($icon) || is_null($icon->getContentType())) {
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

        $this->setRouter('assets/challenge-icon');

        $this->addRouterParam('ext', $icon->getContentType()->getExtension());
        $this->addRouterParam('id', $icon->getId());

        $this->setImageId('challenge_icon_' . $challenge->getId());

        if (!is_null($width)) {
            $this->addRouterParam('width', $width);
        }

        return $this->createImageUrl();
    }
}
