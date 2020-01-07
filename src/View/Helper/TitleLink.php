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

use General\Entity\Title;
use General\ValueObject\Link\Link;

/**
 * Class TitleLink
 * @package General\View\Helper
 */
final class TitleLink extends AbstractLink
{
    public function __invoke(
        Title $title = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $title ??= new Title();

        $routeParams = [];
        $showOptions = [];
        if (! $title->isEmpty()) {
            $routeParams['id'] = $title->getId();
            $showOptions['name'] = $title->getName();
            $showOptions['attention'] = $title->getAttention();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fa-plus',
                    'route' => 'zfcadmin/title/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-title')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'fa-pencil-square-o',
                    'route' => 'zfcadmin/title/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-title')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fa-link',
                    'route' => 'zfcadmin/title/view',
                    'text' => $showOptions[$show] ?? $title->getName()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
