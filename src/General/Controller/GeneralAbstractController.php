<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Calendar
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Controller;

use BjyAuthorize\Controller\Plugin\IsAllowed;
use Contact\Service\ContactService;
use Contact\Service\ContactServiceAwareInterface;
use General\Controller\Plugin\GetFilter;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\FormServiceAwareInterface;
use General\Service\GeneralService;
use General\Service\GeneralServiceAwareInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

/**
 * @method      ZfcUserAuthentication zfcUserAuthentication()
 * @method      FlashMessenger flashMessenger()
 * @method      isAllowed isAllowed($resource, $action)
 * @method      GetFilter getGeneralFilter()
 */
abstract class GeneralAbstractController extends AbstractActionController implements
    FormServiceAwareInterface,
    ServiceLocatorAwareInterface,
    ContactServiceAwareInterface,
    GeneralServiceAwareInterface
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
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    /**
     * @var EmailService
     */
    protected $emailService;

    /**
     * @return \General\Service\FormService
     */
    public function getFormService()
    {
        return $this->formService;
    }

    /**
     * @param $formService
     *
     * @return GeneralAbstractController
     */
    public function setFormService($formService)
    {
        $this->formService = $formService;

        return $this;
    }

    /**
     * @return ContactService
     */
    public function getContactService()
    {
        return $this->contactService;
    }

    /**
     * @param $contactService
     *
     * @return GeneralAbstractController
     */
    public function setContactService(ContactService $contactService)
    {
        $this->contactService = $contactService;

        return $this;
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     * @param $generalService
     *
     * @return GeneralAbstractController
     */
    public function setGeneralService(GeneralService $generalService)
    {
        $this->generalService = $generalService;

        return $this;
    }

    /**
     * @return EmailService
     */
    public function getEmailService()
    {
        return $this->emailService;
    }

    /**
     * @param EmailService $emailService
     *
     * @return GeneralAbstractController
     */
    public function setEmailService(EmailService $emailService)
    {
        $this->emailService = $emailService;

        return $this;
    }

    /**
     * Proxy for the flash messenger helper to have the string translated earlier.
     *
     * @param $string
     *
     * @return string
     */
    protected function translate($string)
    {
        /*
         * @var Translate
         */
        $translate = $this->getServiceLocator()->get('ViewHelperManager')->get('translate');

        return $translate($string);
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GeneralAbstractController
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }
}
