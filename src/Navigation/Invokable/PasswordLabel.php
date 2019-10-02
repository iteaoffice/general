<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/invoice for the canonical source repository
 */

declare(strict_types=1);

namespace General\Navigation\Invokable;

use Admin\Navigation\Invokable\AbstractNavigationInvokable;
use General\Entity\Password;
use Zend\Navigation\Page\Mvc;

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
