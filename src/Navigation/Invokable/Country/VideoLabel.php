<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Navigation\Invokable\Country;

use General\Entity\Country;
use General\Entity\Country\Video;
use General\Navigation\Invokable\AbstractNavigationInvokable;
use Laminas\Navigation\Page\Mvc;

/**
 * Class VideoLabel
 * @package Project\Navigation\Invokable\Idea
 */
final class VideoLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        if ($this->getEntities()->containsKey(Video::class)) {

            /** @var Video $video */
            $video = $this->getEntities()->get(Video::class);

            $this->getEntities()->set(Country::class, $video->getCountry());

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    [
                        'id' => $video->getId(),
                    ]
                )
            );

            if (null === $page->getLabel()) {
                $page->set('label', $video->getVideo()->getName());
            }
        }
    }
}
