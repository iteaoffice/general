<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\ContentType;
use General\ValueObject\Link\Link;

/**
 * Class ContentTypeLink
 * @package General\View\Helper
 */
final class ContentTypeLink extends AbstractLink
{
    public function __invoke(
        ContentType $contentType = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $contentType ??= new ContentType();

        $routeParams = [];
        $showOptions = [];
        if (! $contentType->isEmpty()) {
            $routeParams['id'] = $contentType->getId();
            $showOptions['name'] = $contentType->getContentType();
            $showOptions['extension'] = $contentType->getExtension();
            $showOptions['description'] = $contentType->getDescription();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon' => 'fas fa-plus',
                    'route' => 'zfcadmin/content-type/new',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-content-type')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon' => 'far fa-edit',
                    'route' => 'zfcadmin/content-type/edit',
                    'text' => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-content-type')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon' => 'fas fa-link',
                    'route' => 'zfcadmin/content-type/view',
                    'text' => $showOptions[$show] ?? $contentType->getDescription()
                ];
                break;
        }

        $linkParams['action'] = $action;
        $linkParams['show'] = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
