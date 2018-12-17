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
 *
 * @package Challenge\View\Helper
 */
final class ChallengeIcon extends ImageAbstract
{
    public function __invoke(
        Challenge $challenge,
        int $width = null,
        bool $responsive = true,
        array $classes = [],
        bool $onlyUrl = false
    ): string {
        $icon = $challenge->getIcon();

        if (null === $icon) {
            return '';
        }

        $this->setRouter('image/challenge-icon');
        $this->addRouterParam('id', $icon->getId());
        $this->addRouterParam('ext', $icon->getContentType()->getExtension());
        $this->addRouterParam('last-update', $icon->getDateUpdated()->getTimestamp());
        $this->setImageId('challenge_icon_' . $icon->getId());

        $this->setWidth($width);

        return $this->createImageUrl($onlyUrl);
    }
}
