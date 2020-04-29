<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c)  2019 Jield BV (https://jield.nl) (http://jield.nl)
 * @license     http://jield.nl/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Factory;

use General\Options\EmailOptions;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

final class EmailOptionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): EmailOptions
    {
        $config = $container->get('Config');

        return new EmailOptions($config['email'] ?? []);
    }
}
