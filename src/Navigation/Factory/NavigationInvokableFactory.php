<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Navigation\Factory;

use General\Navigation\Invokable\AbstractNavigationInvokable;
use General\Navigation\Service\NavigationService;
use Interop\Container\ContainerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class NavigationInvokableFactory
 * @package General\Navigation\Factory
 */
final class NavigationInvokableFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): AbstractNavigationInvokable {
        return new $requestedName(
            $container->get(NavigationService::class),
            $container->get(TranslatorInterface::class)
        );
    }
}
