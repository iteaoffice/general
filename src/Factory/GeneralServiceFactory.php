<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/main for the canonical source repository
 */
declare(strict_types=1);

namespace General\Factory;

use Doctrine\ORM\EntityManager;
use General\Options\ModuleOptions;
use General\Service\GeneralService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class GeneralServiceFactory
 *
 * @package General\Factory
 */
final class GeneralServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return GeneralService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): GeneralService
    {
        /** @var GeneralService $generalService */
        $generalService = new $requestedName($options);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        $generalService->setEntityManager($entityManager);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::class);
        $generalService->setModuleOptions($moduleOptions);

        return $generalService;
    }
}
