<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Publication
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Navigation\Invokable;

use Laminas\Navigation\Page\Mvc;

interface NavigationInvokableInterface
{
    public function __invoke(Mvc $page): void;
}
