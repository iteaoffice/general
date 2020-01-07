<?php
/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Factory;

use General\Options\ModuleOptions;
use General\View\Helper\AbstractImage;
use Interop\Container\ContainerInterface;
use Laminas\Router\RouteStackInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class ImageHelperFactory
 * @package General\View\Factory
 */
final class ImageHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AbstractImage
    {
        $dependencies = [
            $container->get(RouteStackInterface::class),
            $container->get(ModuleOptions::class)
        ];

        return new $requestedName(...$dependencies);
    }
}
