<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper\Country;

use General\Entity\Country;
use General\Entity\Country\Video;
use General\ValueObject\Link\Link;
use General\View\Helper\AbstractLink;

/**
 *
 */
final class VideoLink extends AbstractLink
{
    public function __invoke(
        Video $video = null,
        string $action = 'view',
        string $show = 'text',
        Country $country = null
    ): string {
        $video ??= (new Video())->setCountry($country ??= new Country());

        $routeParams = [];
        $showOptions = [];

        $routeParams['id'] = $video->getId();

        if (null !== $country) {
            $routeParams['country'] = $country->getId();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon'  => 'fas fa-plus',
                    'route' => 'zfcadmin/country/video/new',
                    'text'  => $showOptions[$show] ?? $this->translator->translate('txt-add-country-video')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon'  => 'far fa-edit',
                    'route' => 'zfcadmin/country/video/view',
                    'text'  => $showOptions[$show] ?? $video->getVideo()
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon'  => 'far fa-edit',
                    'route' => 'zfcadmin/country/video/edit',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-country-video')
                ];
                break;
        }

        $linkParams['action']      = $action;
        $linkParams['show']        = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
