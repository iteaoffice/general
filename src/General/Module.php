<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    SoloDB
 * @package     General
 * @subpackage  Module
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 * @version     4.0
 */
namespace General;

use Zend\ModuleManager\Feature; //Makes the module class more strict
use Zend\EventManager\EventInterface;

/**
 *
 */
class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\BootstrapListenerInterface,
    Feature\ViewHelperProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Go to the service configuration
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/../../config/services.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return include __DIR__ . '/../../config/viewhelpers.config.php';
    }

    /**
     * @return array
     */
    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof FormServiceAwareInterface) {
                        $sm          = $sm->getServiceLocator();
                        $formService = $sm->get('general_form_service');
                        $instance->setFormService($formService);
                    }
                },
            ),
        );
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        // TODO: Implement onBootstrap() method.
    }
}
