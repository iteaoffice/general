<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper\Challenge;

use Content\Entity\Route;
use General\Entity\Challenge;
use General\ValueObject\Link\Link;
use General\View\Helper\AbstractLink;

/**
 * Class ChallengeLink
 * @package General\View\Helper
 */
final class ChallengeLink extends AbstractLink
{
    public function __invoke(
        Challenge $challenge = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $challenge ??= new Challenge();

        $routeParams = [];
        $showOptions = [];
        if (! $challenge->isEmpty()) {
            $routeParams['id'] = $challenge->getId();
            $routeParams['docRef'] = $challenge->getDocRef();
            $showOptions['name'] = $challenge->getChallenge();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fas fa-plus',
                    'route' => 'zfcadmin/challenge/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-challenge')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'far fa-edit',
                    'route' => 'zfcadmin/challenge/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-challenge')
                ];
                break;
            case 'view-admin':
                $linkParams = [
                    'icon' => 'fas fa-link',
                    'route' => 'zfcadmin/challenge/view',
                    'text' => $showOptions[$show] ?? $challenge->getChallenge()
                ];
                break;
            case 'download-pdf':
                $linkParams = [
                    'icon' => 'far fa-file-pdf',
                    'route' => 'challenge/download-pdf',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-download-pdf')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fas fa-link',
                    'route' => Route::parseRouteName(Route::DEFAULT_ROUTE_CHALLENGE),
                    'text' => $showOptions[$show] ?? $challenge->getChallenge()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
