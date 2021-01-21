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
use General\Entity\Password;
use Laminas\Navigation\Page\Mvc;

/**
 * Class PasswordLabel
 *
 * @package General\Navigation\Invokable
 */
final class PasswordLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-view');

        if ($this->getEntities()->containsKey(Password::class)) {
            /** @var Password $password */
            $password = $this->getEntities()->get(Password::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $password->getId()]
                )
            );
            $label = (string)$password;
        }
        $page->set('label', $label);
    }
}
