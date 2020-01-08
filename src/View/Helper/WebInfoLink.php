<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\WebInfo;
use General\ValueObject\Link\Link;

/**
 * Class WebInfoLink
 * @package General\View\Helper
 */
final class WebInfoLink extends AbstractLink
{
    public function __invoke(
        WebInfo $webInfo = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $webInfo ??= new WebInfo();

        $routeParams = [];
        $showOptions = [];
        if (! $webInfo->isEmpty()) {
            $routeParams['id'] = $webInfo->getId();
            $showOptions['name'] = $webInfo->getInfo();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fa-plus',
                    'route' => 'zfcadmin/web-info/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-web-info')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'fa-pencil-square-o',
                    'route' => 'zfcadmin/web-info/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-web-info')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fa-link',
                    'route' => 'zfcadmin/web-info/view',
                    'text' => $showOptions[$show] ?? $webInfo->getInfo()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
