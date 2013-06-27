<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'general-index',
                        'action' => 'index',
                    ),
                ),
            ),
            'general' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'general-index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(

                ),
            ),
            'style' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/style/[:type]/[:source]',
                    'defaults' => array(
                        'controller' => 'general-style',
                        'action' => 'display',
                    ),
                ),
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'general-manager' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/general-manager',
                            'defaults' => array(
                                'controller' => 'general-manager',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'messages' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/messages.html',
                                    'defaults' => array(
                                        'action' => 'messages',
                                    ),
                                ),
                            ),
                            'message' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/message/[:id].html',
                                    'constraints' => array(
                                        'id' => '\d+',
                                    ),
                                    'defaults' => array(
                                        'action' => 'message',
                                    ),
                                ),
                            ),
                            'new' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/new/:entity',
                                    'defaults' => array(
                                        'action' => 'new',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/edit/:entity/:id',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/delete/:entity/:id',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),

        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
);
