<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General;

use General\Navigation\Service\NavigationService;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManager;
use Laminas\ModuleManager\Feature;
use Laminas\Mvc\MvcEvent;

final class Module implements Feature\ConfigProviderInterface
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
