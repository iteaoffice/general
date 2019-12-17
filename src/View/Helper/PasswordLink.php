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

use General\Entity\Password;
use General\ValueObject\Link\Link;

/**
 * Class PasswordLink
 * @package General\View\Helper
 */
final class PasswordLink extends AbstractLink
{
    public function __invoke(
        Password $password = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $password ??= new Password();

        $routeParams = [];
        $showOptions = [];
        if (!$password->isEmpty()) {
            $routeParams['id'] = $password->getId();
            $showOptions['name'] = $password->getDescription();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fa-plus',
                    'route' => 'zfcadmin/password/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-password')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'fa-pencil-square-o',
                    'route' => 'zfcadmin/password/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-password')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fa-lock',
                    'route' => 'zfcadmin/password/view',
                    'text' => $showOptions[$show] ?? $password->getDescription()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
