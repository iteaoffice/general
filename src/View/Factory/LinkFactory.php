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

use Application\Service\AssertionService;
use BjyAuthorize\Service\Authorize;
use General\View\Helper\AbstractLink;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Router\RouteStackInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LinkFactory
 * @package General\View\Factory
 */
final class LinkFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AbstractLink
    {
        $dependencies = [
            $container->get(AssertionService::class),
            $container->get(Authorize::class),
            $container->get(RouteStackInterface::class),
            $container->get(TranslatorInterface::class),
            $container->get('config')
        ];

        return new $requestedName(...$dependencies);
    }
}
