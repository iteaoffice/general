<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Controller
 * @package     Service
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   2004-2014 Japaveh Webdesign
 * @license     http://solodb.net/license.txt proprietary
 * @link        http://solodb.net
 */
namespace General\Controller;

use General\Service\EmailServiceAwareInterface;
use General\Service\FormServiceAwareInterface;
use General\Service\GeneralServiceAwareInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Controller
 * @package     Service
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   2004-2014 Japaveh Webdesign
 * @license     http://solodb.net/license.txt proprietary
 * @link        http://solodb.net
 */
class ControllerInitializer implements InitializerInterface
{
    /**
     * @param                                           $instance
     * @param ServiceLocatorInterface|ControllerManager $serviceLocator
     *
     * @return $this
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if (!is_object($instance)) {
            return;
        }
        $arrayCheck = [
            FormServiceAwareInterface::class    => 'general_form_service',
            EmailServiceAwareInterface::class   => 'general_email_service',
            GeneralServiceAwareInterface::class => 'general_general_service',
        ];
        /**
         * @var $sm ServiceLocatorInterface
         */
        $sm = $serviceLocator->getServiceLocator();
        /**
         * Go over each interface to see if we should add an interface
         */
        foreach (class_implements($instance) as $interface) {
            if (array_key_exists($interface, $arrayCheck)) {
                $this->setInterface($instance, $interface, $sm->get($arrayCheck[$interface]));
            }
        }

        return;
    }

    /**
     * @param $interface
     * @param $instance
     * @param $service
     */
    protected function setInterface($instance, $interface, $service)
    {
        foreach (get_class_methods($interface) as $setter) {
            if (strpos($setter, 'set') !== false) {
                $instance->$setter($service);
            }
        }
    }
}
