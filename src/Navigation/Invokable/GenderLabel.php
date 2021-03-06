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

use General\Entity\Gender;
use Laminas\Navigation\Page\Mvc;

/**
 * Class GenderLabel
 *
 * @package General\Navigation\Invokable
 */
final class GenderLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-view');

        if ($this->getEntities()->containsKey(Gender::class)) {
            /** @var Gender $gender */
            $gender = $this->getEntities()->get(Gender::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $gender->getId()]
                )
            );
            $label = $gender->getName();
        }
        $page->set('label', $label);
    }
}
