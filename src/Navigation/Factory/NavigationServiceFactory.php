<?php

/**
 * ITEA Office all rights reserved
 *
 * @category    Calendar
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Navigation\Factory;

use General\Navigation\Service\NavigationService;
use General\Options\ModuleOptions;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\Navigation\Navigation;
use Laminas\Router\Http\RouteMatch;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Navigation service factory
 */
final class NavigationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions       = $container->get(ModuleOptions::class);
        /** @var Navigation $adminNavigation */
        $adminNavigation     = $container->get('Laminas\Navigation\Admin');
        /** @var Navigation $communityNavigation */
        $communityNavigation = $container->get($moduleOptions->getCommunityNavigationContainer());
        /** @var RouteMatch $routeMatch */
        $routeMatch          = $container->get('application')->getMvcEvent()->getRouteMatch();

        return new NavigationService(
            $container,
            $container->get(EntityManager::class),
            $adminNavigation,
            $communityNavigation,
            $routeMatch
        );
    }
}
