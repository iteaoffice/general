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

use General\Navigation\Invokable\AbstractNavigationInvokable;
use General\Entity\ContentType;
use Laminas\Navigation\Page\Mvc;

/**
 * Class ContentTypeLabel
 *
 * @package General\Navigation\Invokable
 */
final class ContentTypeLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-view');

        if ($this->getEntities()->containsKey(ContentType::class)) {
            /** @var ContentType $type */
            $type = $this->getEntities()->get(ContentType::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $type->getId()]
                )
            );
            $label = $type->getDescription();
        }
        $page->set('label', $label);
    }
}
