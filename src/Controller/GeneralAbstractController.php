<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Calendar
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use BjyAuthorize\Controller\Plugin\IsAllowed;
use Contact\Service\ContactService;
use Doctrine\ORM\EntityManager;
use General\Controller\Plugin\GetFilter;
use General\Options\ModuleOptions;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\GeneralService;
use Project\Service\ProjectService;
use Zend\I18n\View\Helper\Translate;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

/**
 * @method      ZfcUserAuthentication zfcUserAuthentication()
 * @method      FlashMessenger flashMessenger()
 * @method      IsAllowed isAllowed($resource, $action)
 * @method      GetFilter getGeneralFilter()
 */
abstract class GeneralAbstractController extends AbstractActionController
{
    /**
     * @var FormService
     */
    protected $formService;
    /**
     * @var ContactService
     */
    protected $contactService;
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var ProjectService
     */
    protected $projectService;
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    /**
     * @var EmailService
     */
    protected $emailService;
    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var HelperPluginManager
     */
    protected $viewHelperManager;

    /**
     * @return FormService
     */
    public function getFormService(): FormService
    {
        return $this->formService;
    }

    /**
     * @param $formService
     *
     * @return GeneralAbstractController
     */
    public function setFormService($formService): GeneralAbstractController
    {
        $this->formService = $formService;

        return $this;
    }

    /**
     * @return ContactService
     */
    public function getContactService(): ContactService
    {
        return $this->contactService;
    }

    /**
     * @param $contactService
     *
     * @return GeneralAbstractController
     */
    public function setContactService(ContactService $contactService): GeneralAbstractController
    {
        $this->contactService = $contactService;

        return $this;
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService(): GeneralService
    {
        return $this->generalService;
    }

    /**
     * @param $generalService
     *
     * @return GeneralAbstractController
     */
    public function setGeneralService(GeneralService $generalService): GeneralAbstractController
    {
        $this->generalService = $generalService;

        return $this;
    }

    /**
     * @return EmailService
     */
    public function getEmailService(): EmailService
    {
        return $this->emailService;
    }

    /**
     * @param EmailService $emailService
     *
     * @return GeneralAbstractController
     */
    public function setEmailService(EmailService $emailService): GeneralAbstractController
    {
        $this->emailService = $emailService;

        return $this;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator(): ServiceLocatorInterface
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GeneralAbstractController
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator): GeneralAbstractController
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions(): ModuleOptions
    {
        return $this->moduleOptions;
    }

    /**
     * @param ModuleOptions $moduleOptions
     *
     * @return GeneralAbstractController
     */
    public function setModuleOptions($moduleOptions): GeneralAbstractController
    {
        $this->moduleOptions = $moduleOptions;

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return GeneralAbstractController
     */
    public function setEntityManager($entityManager): GeneralAbstractController
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return ProjectService
     */
    public function getProjectService(): ProjectService
    {
        return $this->projectService;
    }

    /**
     * @param ProjectService $projectService
     * @return GeneralAbstractController
     */
    public function setProjectService(ProjectService $projectService): GeneralAbstractController
    {
        $this->projectService = $projectService;

        return $this;
    }

    /**
     * Proxy for the flash messenger helper to have the string translated earlier.
     *
     * @param $string
     *
     * @return string
     */
    protected function translate($string): string
    {
        /** @var Translate $translate */
        $translate = $this->getViewHelperManager()->get('translate');

        return $translate($string);
    }

    /**
     * @return HelperPluginManager
     */
    public function getViewHelperManager(): HelperPluginManager
    {
        return $this->viewHelperManager;
    }

    /**
     * @param HelperPluginManager $viewHelperManager
     *
     * @return GeneralAbstractController
     */
    public function setViewHelperManager(HelperPluginManager $viewHelperManager): GeneralAbstractController
    {
        $this->viewHelperManager = $viewHelperManager;

        return $this;
    }
}
