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

use General\Entity\Vat;
use General\ValueObject\Link\Link;

/**
 * Class VatLink
 * @package General\View\Helper
 */
final class VatLink extends AbstractLink
{
    public function __invoke(
        Vat $vat = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $vat ??= new Vat();

        $routeParams = [];
        $showOptions = [];
        if (! $vat->isEmpty()) {
            $routeParams['id'] = $vat->getId();
            $showOptions['name'] = $vat->getCode();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fas fa-plus',
                    'route' => 'zfcadmin/vat/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-vat')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'far fa-edit',
                    'route' => 'zfcadmin/vat/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-vat')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fas fa-link',
                    'route' => 'zfcadmin/vat/view',
                    'text' => $showOptions[$show] ?? $vat->getCode()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
