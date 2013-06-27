<?php

namespace GeneralText\Controller;

use Zend\Test\PhpUnit\Controller\AbstractHttpControllerTestCase;

use General\Controller\StyleController;
use General\Options;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

/**
 * Tests for {@see \DoctrineModule\Controller\CliController}
 *
 * @license MIT
 * @author Aleksandr Sandrovskiy <a.sandrovsky@gmail.com>
 *
 * @covers \DoctrineModule\Controller\CliController
 */
class StyleControllerTest extends AbstractHttpControllerTestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../config/application.config.php'
        );
        parent::setUp();

        $this->controller = new StyleController();
        $this->request = new Request();
        $this->response = null;
        $this->routeMatch = new RouteMatch(array('controller' => 'general-style'));
        $this->event = new MvcEvent();
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);

    }


    public function testDisplayActionWithInvalidImage()
    {
        //Force the required action
        $this->routeMatch->setParam('action', 'display');

        //Get the serviceLocation and inject it into the controller
        $serviceLocator = $this->getApplicationServiceLocator();
        $this->controller->setServiceLocator($serviceLocator);

        //Dispatch the controller and get the response
        $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();

        //Collect the options and check if we have the correct image (not found)
        $options = $serviceLocator->get('general_module_options');

        $this->assertEquals($response->getContent(),
            file_get_contents($options->getStyleLocations()[0] . DIRECTORY_SEPARATOR
            . $options->getImageLocation() . DIRECTORY_SEPARATOR
            . $options->getImageNotFound()));
        $this->assertResponseStatusCode(200);
    }


}

