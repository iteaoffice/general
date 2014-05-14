<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Service
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   2004-2014 Japaveh Webdesign
 * @license     http://solodb.net/license.txt proprietary
 * @link        http://solodb.net
 */
namespace General\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Service
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   2004-2014 Japaveh Webdesign
 * @license     http://solodb.net/license.txt proprietary
 * @link        http://solodb.net
 */
class ServiceInitializer implements InitializerInterface
{
    /**
     * @param                         $instance
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ServiceAbstract
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof EmailServiceAwareInterface) {
            /**
             * @var $emailService EmailService
             */
            $emailService = $serviceLocator->get('general_email_service');
            $instance->setEmailService($emailService);
        }

        if ($instance instanceof GeneralServiceAwareInterface) {
            /**
             * @var @generalService GeneralService
             */
            $generalService = $serviceLocator->get('general_general_service');
            $instance->setGeneralService($generalService);
        }
    }
}
