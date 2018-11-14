<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class AbstractViewHelper
 *
 * @package General\View\Helper
 */
abstract class AbstractViewHelper extends AbstractHelper
{
    /**
     * @var ContainerInterface
     */
    protected $serviceManager;
    /**
     * @var HelperPluginManager
     */
    protected $helperPluginManager;
    /**
     * @var RouteMatch
     */
    protected $routeMatch = null;

    /**
     * RouteInterface match returned by the router.
     * Use a test on is_null to have the possibility to overrule the serviceLocator lookup for unit tets reasons.
     *
     * @return RouteMatch.
     */
    public function getRouteMatch()
    {
        if (null === $this->routeMatch) {
            $this->routeMatch = $this->getServiceManager()->get('application')->getMvcEvent()->getRouteMatch();
        }

        return $this->routeMatch;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ContainerInterface $serviceManager
     *
     * @return AbstractViewHelper
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * @return TwigRenderer
     */
    public function getRenderer()
    {
        return $this->getServiceManager()->get('ZfcTwigRenderer');
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function translate($string): string
    {
        return $this->getHelperPluginManager()->get('translate')->__invoke($string);
    }

    /**
     * @return HelperPluginManager
     */
    public function getHelperPluginManager()
    {
        return $this->helperPluginManager;
    }

    /**
     * @param HelperPluginManager $helperPluginManager
     *
     * @return AbstractViewHelper
     */
    public function setHelperPluginManager($helperPluginManager)
    {
        $this->helperPluginManager = $helperPluginManager;

        return $this;
    }
}
