<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\ValueObject\Image;

use General\Options\ModuleOptions;
use Laminas\Router\RouteStackInterface;
use Thumbor\Url\Builder;

final class ImageRoute
{
    private string $route;
    private static bool $thumbor = true;
    private array $routeParams;

    public function __construct(string $route, array $routeParams)
    {
        $this->route       = $route;
        $this->routeParams = $routeParams;
    }

    public static function fromArray(array $params): ImageRoute
    {
        self::$thumbor = $params['routeParams']['ext'] !== 'svg';

        return new self(
            $params['route'] ?? '',
            $params['routeParams'] ?? []
        );
    }

    public static function isThumbor(): bool
    {
        return self::$thumbor;
    }

    public function parseBuilder(RouteStackInterface $router, ModuleOptions $moduleOptions): Builder
    {
        $imageLink = $moduleOptions->getServerUrl() . $router->assemble(
            $this->routeParams,
            ['name' => $this->route]
        );

        return Builder::construct(
            $moduleOptions->getThumborServer(),
            $moduleOptions->getThumborSecret(),
            $imageLink
        );
    }

    public function parse(RouteStackInterface $router, ModuleOptions $moduleOptions): string
    {
        return $moduleOptions->getServerUrl() . $router->assemble(
            $this->routeParams,
            ['name' => $this->route]
        );
    }
}
