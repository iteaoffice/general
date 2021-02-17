<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Navigation\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use General\Navigation\Invokable\NavigationInvokableInterface;
use Laminas\Navigation\Navigation;
use Laminas\Navigation\Page\Mvc;
use Laminas\Router\RouteMatch;
use Psr\Container\ContainerInterface;
use RuntimeException;

use function class_exists;
use function defined;
use function is_array;

/**
 * Class NavigationService
 * @package General\Navigation\Service
 */
class NavigationService
{
    private ContainerInterface $container;
    private EntityManager $entityManager;
    private Navigation $adminNavigation;
    private Navigation $communityNavigation;
    private ?RouteMatch $routeMatch = null;
    private ArrayCollection $entities;

    public function __construct(
        ContainerInterface $container,
        EntityManager $entityManager,
        Navigation $adminNavigation,
        Navigation $communityNavigation,
        ?RouteMatch $routeMatch
    ) {
        $this->container           = $container;
        $this->entityManager       = $entityManager;
        $this->adminNavigation     = $adminNavigation;
        $this->communityNavigation = $communityNavigation;
        $this->routeMatch          = $routeMatch;
        $this->entities            = new ArrayCollection();
    }

    public function update(): void
    {
        // Only apply to Admin routes for now
        if (null !== $this->routeMatch) {
            $matchedRoute = $this->routeMatch->getMatchedRouteName();
            // Try first to find the page in the admin navigation
            /** @var Mvc $page */
            $page = $this->adminNavigation->findOneBy('route', $matchedRoute);

            // If we cannot find the file, try again in the community navigation
            if (null === $page) {
                $page = $this->communityNavigation->findOneBy('route', $matchedRoute);
            }

            //@todo: try to figure out why this does not work for the facebook but does work for the publications
            if (($page instanceof Mvc) && ($page->getRoute() !== 'community/contact/facebook/view')) {
                // Set active

                // Custom navigation params from module.config.navigation.php
                $pageCustomParams = $page->get('params');

                //We dont want to set a page to active when
                if (! $page->get('notAutoActive')) {
                    $page->setActive();
                }

                // Merge all route params with navigation params
                $routeParams = $this->routeMatch->getParams();
                $page->setParams(array_merge($routeParams, $page->getParams()));

                // Load entity instances when defined
                if (isset($pageCustomParams['entities']) && is_array($pageCustomParams['entities'])) {
                    foreach ($pageCustomParams['entities'] as $routeParam => $entityClass) {
                        // The routeParam can be aliased
                        $routeParamKey = $routeParam;
                        if (
                            isset($pageCustomParams['routeParam'])
                            && array_key_exists($routeParam, $pageCustomParams['routeParam'])
                        ) {
                            $routeParamKey = $pageCustomParams['routeParam'][$routeParam];
                        }

                        if (null !== $entityClass && class_exists($entityClass)) {
                            $repository = $this->entityManager->getRepository($entityClass);
                            $entity     = $repository->findOneBy([
                                $routeParam => $this->routeMatch->getParam($routeParamKey),
                            ]);
                            if (null === $entity) {
                                if (
                                    defined('ITEAOFFICE_ENVIRONMENT')
                                    && (ITEAOFFICE_ENVIRONMENT === 'development')
                                ) {
                                    print sprintf(
                                        "Can not load '%s' by '%s' via '%s' value(%s)",
                                        $entityClass,
                                        $routeParam,
                                        $routeParamKey,
                                        $this->routeMatch->getParam($routeParamKey)
                                    );
                                }
                            } else {
                                $this->entities->set($entityClass, $entity);
                            }
                        }
                    }
                }
                $this->updateNavigation($page);
            }
        }
    }

    private function updateNavigation(Mvc $page): void
    {
        $page->setVisible();
        $pageCustomParams = $page->get('params');

        // Provide the setter callables with the entity and route params
        if (isset($pageCustomParams['invokables']) && is_array($pageCustomParams['invokables'])) {
            foreach ($pageCustomParams['invokables'] as $invokable) {
                // Get the invokable from the service locator
                if ($this->container->has($invokable)) {
                    $instance = $this->container->get($invokable);
                    if ($instance instanceof NavigationInvokableInterface) {
                        $instance($page);
                    } else {
                        throw new RuntimeException('Can\'t invoke callable ' . $invokable);
                    }
                    // Not found
                } else {
                    throw new RuntimeException('Servicemanager can\'t find invokable ' . $invokable);
                }
            }
        }

        // Traverse up the navigation with the current entities
        $parentPage = $page->getParent();
        if ($parentPage instanceof Mvc) {
            $routeParams = $this->routeMatch->getParams();
            $parentPage->setParams(array_merge($parentPage->getParams(), $routeParams));
            $this->updateNavigation($parentPage);
        }
    }

    /**
     * @return ArrayCollection
     * @deprecated We want to pass the entities during the invocation of the invokable
     */
    public function getEntities(): ArrayCollection // Needed by AbstractNavigationInvokable
    {
        return $this->entities;
    }
}
