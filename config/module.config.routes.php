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
            'style'    => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/style/[:type]/[:source]',
                    'defaults' => array(
                        'controller' => 'general-style',
                        'action'     => 'display',
                    ),
                ),
            ),
            'assets'   => array(
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => array(
                    'route'    => '/assets',
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'country-flag' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => "/country-flag/[:iso3].[:ext]",
                            'defaults' => array(
                                'action' => 'display',
                            ),
                        ),
                    ),
                ),
            ),
            'country'  => array(
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => array(
                    'route'    => '/organisation',
                    'defaults' => array(
                        'controller' => 'organisation-index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'flag' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/flag/[:iso3].[:ext]',
                            'defaults' => array(
                                'action' => 'flag',
                            ),
                        ),
                    ),
                )
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'general-manager' => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'priority'      => 1000,
                        'options'       => array(
                            'route'    => '/general-manager',
                            'defaults' => array(
                                'controller' => 'general-manager',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes'  => array(
                            'messages' => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/messages.html',
                                    'defaults' => array(
                                        'action' => 'messages',
                                    ),
                                ),
                            ),
                            'message'  => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'       => '/message/[:id].html',
                                    'constraints' => array(
                                        'id' => '\d+',
                                    ),
                                    'defaults'    => array(
                                        'action' => 'message',
                                    ),
                                ),
                            ),
                            'new'      => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/new/:entity',
                                    'defaults' => array(
                                        'action' => 'new',
                                    ),
                                ),
                            ),
                            'edit'     => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/edit/:entity/:id',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                            ),
                            'delete'   => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/delete/:entity/:id',
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

);
