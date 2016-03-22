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

use Contact\Service\ContactService;
use General\Service\EmailService;
use General\Service\GeneralService;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcTwig\View\TwigRenderer;

/**
 * Class EmailServiceFactory
 *
 * @package General\Factory
 */
class EmailServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EmailService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $emailService = new EmailService($config["email"]);

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $serviceLocator->get('Application\Authentication\Service');
        $emailService->setAuthenticationService($authenticationService);

        /** @var GeneralService $generalService */
        $generalService = $serviceLocator->get(GeneralService::class);
        $emailService->setGeneralService($generalService);

        /** @var TwigRenderer $renderer */
        $renderer = $serviceLocator->get('ZfcTwigRenderer');
        $emailService->setRenderer($renderer);

        /** @var ContactService $contactService */
        $contactService = $serviceLocator->get(ContactService::class);
        $emailService->setContactService($contactService);

        return $emailService;
    }
}
