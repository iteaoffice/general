<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace General\Navigation\Invokable;

use Admin\Navigation\Invokable\AbstractNavigationInvokable;
use General\Entity\Challenge;
use Zend\Navigation\Page\Mvc;

/**
 * Class ChallengeTypeLabel
 *
 * @package Project\Navigation\Invokable
 */
final class ChallengeTypeLabel extends AbstractNavigationInvokable
{
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-view');

        if ($this->getEntities()->containsKey(Challenge\Type::class)) {
            /** @var Challenge\Type $type */
            $type = $this->getEntities()->get(Challenge\Type::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    [
                        'id' => $type->getId(),
                    ]
                )
            );
            $label = (string)$type->getType();
        }

        $page->set('label', $label);
    }
}
