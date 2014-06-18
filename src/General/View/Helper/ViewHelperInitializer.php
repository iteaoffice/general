<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Controller
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   2004-2014 Japaveh Webdesign
 * @license     http://solodb.net/license.txt proprietary
 * @link        http://solodb.net
 */
namespace General\View\Helper;

use General\Service\GeneralServiceAwareInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Controller
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   2004-2014 Japaveh Webdesign
 * @license     http://solodb.net/license.txt proprietary
 * @link        http://solodb.net
 */
class ViewHelperInitializer implements InitializerInterface
{
    /**
     * @param                                             $instance
     * @param ServiceLocatorInterface|HelperPluginManager $serviceLocator
     *
     * @return AbstractHelper
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if (!is_object($instance)) {
            return;
        }
        $arrayCheck = [
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
