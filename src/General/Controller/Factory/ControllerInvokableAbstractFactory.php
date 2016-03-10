<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Publication
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2016 ITEA Office (http://itea3.org)
 */

namespace General\Controller\Factory;

use Contact\Service\ContactService;
use Doctrine\ORM\EntityManager;
use General\Controller\GeneralAbstractController;
use General\Options\ModuleOptions;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\GeneralService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ControllerInvokableAbstractFactory
 * @package General\Controller\Factory
 */
class ControllerInvokableAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (class_exists($requestedName)
            && in_array(GeneralAbstractController::class, class_parents($requestedName)));
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface|ControllerManager $serviceLocator
     * @param string $name
     * @param string $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        try {

            /** @var GeneralAbstractController $controller */
            $controller = new $requestedName();
            $controller->setServiceLocator($serviceLocator);

            $serviceManager = $serviceLocator->getServiceLocator();

            /** @var FormService $formService */
            $formService = $serviceManager->get(FormService::class);
            $controller->setFormService($formService);

            /** @var EntityManager $entityManager */
            $entityManager = $serviceManager->get(EntityManager::class);
            $controller->setEntityManager($entityManager);

            /** @var GeneralService $generalService */
            $generalService = $serviceManager->get(GeneralService::class);
            $controller->setGeneralService($generalService);

            /** @var EmailService $emailService */
            $emailService = $serviceManager->get(EmailService::class);
            $controller->setEmailService($emailService);

            /** @var ContactService $contactService */
            $contactService = $serviceManager->get(ContactService::class);
            $controller->setContactService($contactService);

            /** @var ModuleOptions $moduleOptions */
            $moduleOptions = $serviceManager->get(ModuleOptions::class);
            $controller->setModuleOptions($moduleOptions);

            return $controller;
        } catch (\Exception $e) {
            var_dump($e);
            die();
        }
    }
}
