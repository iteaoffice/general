<?php

declare(strict_types=1);

namespace General\View\Helper;

use General\Options\ModuleOptions;
use General\ValueObject\Image\Image;
use Zend\Router\RouteStackInterface;

abstract class AbstractImage
{
    private RouteStackInterface $router;
    private ModuleOptions $moduleOptions;

    public function __construct(
        RouteStackInterface $router,
        ModuleOptions $moduleOptions
    ) {
        $this->router = $router;
        $this->moduleOptions = $moduleOptions;
    }

    protected function parse(?Image $image): string
    {
        return ($image === null) ? '' : $image->parse($this->router, $this->moduleOptions);
    }
}
