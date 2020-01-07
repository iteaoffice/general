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

use General\Entity\Gender;
use General\ValueObject\Link\Link;

/**
 * Class GenderLink
 * @package General\View\Helper
 */
final class GenderLink extends AbstractLink
{
    public function __invoke(
        Gender $gender = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $gender ??= new Gender();

        $routeParams = [];
        $showOptions = [];
        if (! $gender->isEmpty()) {
            $routeParams['id'] = $gender->getId();
            $showOptions['name'] = $gender->getName();
            $showOptions['attention'] = $gender->getAttention();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fa-plus',
                    'route' => 'zfcadmin/gender/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-gender')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'fa-pencil-square-o',
                    'route' => 'zfcadmin/gender/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-gender')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fa-link',
                    'route' => 'zfcadmin/gender/view',
                    'text' => $showOptions[$show] ?? $gender->getName()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
