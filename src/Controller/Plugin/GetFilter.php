<?php

/**
 * Jield copyright message placeholder.
 *
 * @category    Application
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Zend\Http\Request;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category    Application
 */
class GetFilter extends AbstractPlugin
{
    /**
     * @var PluginManager
     */
    protected $serviceManager;
    /**
     * @var array
     */
    protected $filter = [];

    /**
     * Instantiate the filter
     *
     * @return GetFilter
     */
    public function __invoke()
    {
        $encodedFilter = urldecode((string)$this->getRouteMatch()->getParam('encodedFilter'));

        $order = $this->getRequest()->getQuery('order');
        $direction = $this->getRequest()->getQuery('direction');

        //Take the filter from the URL
        $filter = (array) json_decode(base64_decode($encodedFilter));


        //If the form is submitted, refresh the URL
        if ($this->getRequest()->isGet() && !is_null($this->getRequest()->getQuery('submit'))) {
            $filter = $this->getRequest()->getQuery()->toArray()['filter'];
        }

        //Create a new filter if not set already
        if (!$filter) {
            $filter = [];
        }

        //Add a default order and direction if not known in the filter
        if (!isset($filter['order'])) {
            $filter['order'] = 'name';
            $filter['direction'] = 'asc';
        }

        //Overrule the order if set in the query
        if (!is_null($order)) {
            $filter['order'] = $order;
        }

        //Overrule the direction if set in the query
        if (!is_null($direction)) {
            $filter['direction'] = $direction;
        }

        $this->filter = $filter;

        return $this;
    }

    /**
     * @return RouteMatch
     */
    public function getRouteMatch(): RouteMatch
    {
        return $this->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator(): ContainerInterface
    {
        return $this->serviceManager;
    }

    /**
     * Proxy to the original request object to handle form
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->getServiceLocator()->get('application')->getMvcEvent()->getRequest();
    }

    /**
     * Return the filter
     *
     * @return array
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->filter['order'];
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->filter['direction'];
    }

    /**
     * Give the compressed version of the filter
     *
     * @return string
     */
    public function getHash(): string
    {
        return base64_encode(json_encode($this->filter));
    }

    /**
     * Set service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }
}
