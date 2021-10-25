<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Navigation\Invokable;

use General\Entity\Language;
use Laminas\Navigation\Page\Mvc;

/**
 * Class LanguageLabel
 *
 * @package General\Navigation\Invokable
 */
final class LanguageLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-view');

        if ($this->getEntities()->containsKey(Language::class)) {
            /** @var Language $language */
            $language = $this->getEntities()->get(Language::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $language->getId()]
                )
            );
            $label = (string)$language;
        }

        if (null === $page->getLabel()) {
            $page->set('label', $label);
        }
    }
}
