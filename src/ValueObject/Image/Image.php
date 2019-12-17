<?php

declare(strict_types=1);

namespace General\ValueObject\Image;

use General\Options\ModuleOptions;
use Zend\Router\RouteStackInterface;

final class Image
{
    private ImageRoute      $imageRoute;
    private ImageDecoration $imageDecoration;

    public function __construct(ImageRoute $imageRoute, ImageDecoration $imageDecoration)
    {
        $this->imageRoute = $imageRoute;
        $this->imageDecoration = $imageDecoration;
    }

    public static function fromArray(array $params): Image
    {
        return new self(ImageRoute::fromArray($params), ImageDecoration::fromArray($params));
    }

    public function parse(RouteStackInterface $router, ModuleOptions $moduleOptions): string
    {
        return $this->imageDecoration->parse($this->imageRoute->parse($router, $moduleOptions));
    }
}
