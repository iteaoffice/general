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

use General\Entity\VatType;
use General\ValueObject\Link\Link;

/**
 * Class VatTypeLink
 * @package General\View\Helper
 */
final class VatTypeLink extends AbstractLink
{
    public function __invoke(
        VatType $vatType = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $vatType ??= new VatType();

        $routeParams = [];
        $showOptions = [];
        if (! $vatType->isEmpty()) {
            $routeParams['id'] = $vatType->getId();
            $showOptions['name'] = $vatType->getType();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fas fa-plus',
                    'route' => 'zfcadmin/vat-type/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-vat-type')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'far fa-edit',
                    'route' => 'zfcadmin/vat-type/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-vat-type')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fas fa-link',
                    'route' => 'zfcadmin/vat-type/view',
                    'text' => $showOptions[$show] ?? $vatType->getType()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
