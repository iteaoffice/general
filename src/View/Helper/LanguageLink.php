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

use General\Entity\Language;
use General\ValueObject\Link\Link;

/**
 * Class LanguageLink
 * @package General\View\Helper
 */
final class LanguageLink extends AbstractLink
{
    public function __invoke(
        Language $language = null,
        string $action = 'view',
        string $show = 'name'
    ): string {
        $language ??= new Language();

        $routeParams = [];
        $showOptions = [];
        if (! $language->isEmpty()) {
            $routeParams['id']       = $language->getId();
            $showOptions['language'] = $language->getLanguage();
        }

        switch ($action) {
            case 'new':
                $linkParams = [
                    'icon'  => 'fas fa-plus',
                    'route' => 'zfcadmin/language/new',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-new-language')
                ];
                break;
            case 'edit':
                $linkParams = [
                    'icon'  => 'far fa-edit',
                    'route' => 'zfcadmin/language/edit',
                    'text'  => $showOptions[$show]
                        ?? $this->translator->translate('txt-edit-language')
                ];
                break;
            case 'view':
                $linkParams = [
                    'icon'  => 'fa-lock',
                    'route' => 'zfcadmin/language/view',
                    'text'  => $showOptions[$show] ?? $language->getLanguage()
                ];
                break;
        }

        $linkParams['action']      = $action;
        $linkParams['show']        = $show;
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
