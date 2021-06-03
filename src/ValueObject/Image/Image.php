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

final class Image
{
    private ImageRoute $imageRoute;
    private ImageDecoration $imageDecoration;

    public function __construct(ImageRoute $imageRoute, ImageDecoration $imageDecoration)
    {
        $this->imageRoute      = $imageRoute;
        $this->imageDecoration = $imageDecoration;
    }

    public static function fromArray(array $params): Image
    {
        return new self(ImageRoute::fromArray($params), ImageDecoration::fromArray($params));
    }

    public function parse(RouteStackInterface $router, ModuleOptions $moduleOptions): string
    {
        if (! $this->imageRoute::isThumbor()) {
            return $this->imageDecoration->parse($this->imageRoute->parse($router, $moduleOptions));
        }

        return $this->imageDecoration->parseBuilder($this->imageRoute->parseBuilder($router, $moduleOptions));
    }
}
