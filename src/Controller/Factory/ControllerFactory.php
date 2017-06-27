<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */
declare(strict_types=1);

namespace General\Controller\Factory;

use Contact\Service\ContactService;
use Doctrine\ORM\EntityManager;
use General\Controller\GeneralAbstractController;
use General\Options\ModuleOptions;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\GeneralService;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * Class ControllerFactory
 *
 * @package Project\Controller\Factory
 */
final class ControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface|ControllerManager $container
     * @param                                      $requestedName
     * @param array|null $options
     *
     * @return GeneralAbstractController
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): GeneralAbstractController {
        /** @var GeneralAbstractController $controller */
        $controller = new $requestedName($options);

        /** @var FormService $formService */
        $formService = $container->get(FormService::class);
        $controller->setFormService($formService);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        $controller->setEntityManager($entityManager);

        /** @var GeneralService $generalService */
        $generalService = $container->get(GeneralService::class);
        $controller->setGeneralService($generalService);

        /** @var EmailService $emailService */
        $emailService = $container->get(EmailService::class);
        $controller->setEmailService($emailService);

        /** @var ContactService $contactService */
        $contactService = $container->get(ContactService::class);
        $controller->setContactService($contactService);

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::class);
        $controller->setModuleOptions($moduleOptions);

        /** @var HelperPluginManager $viewHelperManager */
        $viewHelperManager = $container->get('ViewHelperManager');
        $controller->setViewHelperManager($viewHelperManager);

        return $controller;
    }
}
