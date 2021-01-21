<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\ValueObject\Link;

use Laminas\Router\RouteStackInterface;

final class Link
{
    private LinkRoute $linkRoute;
    private LinkDecoration $linkDecoration;

    public function __construct(LinkRoute $linkRoute, LinkDecoration $linkDecoration)
    {
        $this->linkRoute      = $linkRoute;
        $this->linkDecoration = $linkDecoration;
    }

    public static function fromArray(array $params): Link
    {
        return new self(LinkRoute::fromArray($params), LinkDecoration::fromArray($params));
    }

    public function parse(RouteStackInterface $router, string $serverUrl = ''): string
    {
        return sprintf(
            $this->linkDecoration->parse(),
            $this->linkRoute->parse($router, $serverUrl)
        );
    }
}
