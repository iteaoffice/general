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
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcTwig\View\TwigRenderer;

/**
 * Class EmailServiceFactory
 *
 * @package General\Factory
 */
class EmailServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EmailService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): EmailService
    {
        $config = $container->get('Config');
        /**
         * @var $emailService EmailService
         */
        $emailService = new $requestedName($config["email"]);

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get('Application\Authentication\Service');
        $emailService->setAuthenticationService($authenticationService);

        /** @var GeneralService $generalService */
        $generalService = $container->get(GeneralService::class);
        $emailService->setGeneralService($generalService);

        /** @var TwigRenderer $renderer */
        $renderer = $container->get('ZfcTwigRenderer');
        $emailService->setRenderer($renderer);

        /** @var ContactService $contactService */
        $contactService = $container->get(ContactService::class);
        $emailService->setContactService($contactService);

        return $emailService;
    }
}
