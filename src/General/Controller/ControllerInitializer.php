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

use General\Service\FormServiceAwareInterface;
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
     * @param                         $instance
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof FormServiceAwareInterface) {
            $sm          = $serviceLocator->getServiceLocator();
            $formService = $sm->get('general_form_service');
            $instance->setFormService($formService);
        }
    }
}
