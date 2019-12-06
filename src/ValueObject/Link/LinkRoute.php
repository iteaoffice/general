<?php

declare(strict_types=1);

namespace General\ValueObject\Link;

use Zend\Router\RouteStackInterface;

final class LinkRoute
{
    private string  $route;
    private array   $routeParams;
    private ?array  $queryParams;
    private ?string $fragment;

    public function __construct(
        string  $route,
        array   $routeParams = [],
        ?array  $queryParams = null,
        ?string $fragment = null
    ) {
        $this->route       = $route;
        $this->routeParams = $routeParams;
        $this->queryParams = $queryParams;
        $this->fragment    = $fragment;
    }

    public static function fromArray(array $params): LinkRoute
    {
        return new self(
            ($params['route'] ?? ''),
            ($params['routeParams'] ?? []),
            ($params['queryParams'] ?? null),
            ($params['fragment'] ?? null)
        );
    }

    public function parse(RouteStackInterface $router, string $serverUrl = ''): string
    {
        return $serverUrl . $router->assemble(
            $this->routeParams,
            ['name' => $this->route, 'query' => $this->queryParams, 'fragment' => $this->fragment]
        );
    }
}
