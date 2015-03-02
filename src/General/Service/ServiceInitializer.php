<?php
/**
 * Japaveh Webdesign copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <info@japaveh.nl>
 * @copyright 2004-2014 Japaveh Webdesign
 * @license   http://solodb.net/license.txt proprietary
 *
 * @link      http://solodb.net
 */

namespace General\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Japaveh Webdesign copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <info@japaveh.nl>
 * @copyright 2004-2014 Japaveh Webdesign
 * @license   http://solodb.net/license.txt proprietary
 *
 * @link      http://solodb.net
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
        if (!is_object($instance)) {
            return;
        }
        $arrayCheck = [
            EmailServiceAwareInterface::class   => EmailService::class,
            GeneralServiceAwareInterface::class => GeneralService::class,
        ];
        /*
         * Go over each interface to see if we should add an interface
         */
        foreach (class_implements($instance) as $interface) {
            if (array_key_exists($interface, $arrayCheck)) {
                $this->setInterface($instance, $interface, $serviceLocator->get($arrayCheck[$interface]));
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
