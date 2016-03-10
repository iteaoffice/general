<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/main for the canonical source repository
 */
namespace General\Factory;

use Doctrine\ORM\EntityManager;
use General\Options\ModuleOptions;
use General\Service\GeneralService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class GeneralServiceFactory
 *
 * @package General\Factory
 */
class GeneralServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GeneralService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $generalService = new GeneralService();

        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);
        $generalService->setEntityManager($entityManager);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get(ModuleOptions::class);
        $generalService->setModuleOptions($moduleOptions);
        
        return $generalService;
    }
}
