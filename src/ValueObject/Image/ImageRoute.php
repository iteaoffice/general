<?php

declare(strict_types=1);

namespace General\ValueObject\Image;

use General\Options\ModuleOptions;
use Thumbor\Url\Builder;
use Laminas\Router\RouteStackInterface;

final class ImageRoute
{
    private string  $route;
    private array   $routeParams;

    public function __construct(string $route, array $routeParams)
    {
        $this->route = $route;
        $this->routeParams = $routeParams;
    }

    public static function fromArray(array $params): ImageRoute
    {
        return new self(
            $params['route'] ?? '',
            $params['routeParams'] ?? []
        );
    }

    public function parse(RouteStackInterface $router, ModuleOptions $moduleOptions): Builder
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
}
