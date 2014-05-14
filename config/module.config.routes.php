<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
return array(
    'router' => array(
        'routes' => array(
            'assets'       => array(
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => array(
                    'route'    => '/assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test'),
                    'defaults' => array(
                        'controller' => 'general-index',
                    ),
                ),
                'may_terminate' => false,
                'child_routes'  => array(
                    'country-flag'      => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => "/country-flag/[:iso3].[:ext]",
                            'defaults' => array(
                                //Explicitly add the controller here as the assets are collected
                                'controller' => 'general-index',
                                'action'     => 'country-flag',
                            ),
                        ),
                    ),
                    'content-type-icon' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => "/content-type-icon/[:hash].gif",
                            'defaults' => array(
                                //Explicitly add the controller here as the assets are collected
                                'controller' => 'general-index',
                                'action'     => 'content-type-icon',
                            ),
                        ),
                    ),
                    'style-image'       => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => "/style/image/[:source]",
                            'defaults' => array(
                                //Explicitly add the controller here as the assets are collected
                                'controller' => 'general-style',
                                'action'     => 'display',
                            ),
                        ),
                    ),
                ),
            ),
            'country'      => array(
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => array(
                    'route'    => '/country',
                    'defaults' => array(
                        'controller' => 'general-index',
                        'action'     => 'country',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'code' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/code/[:cd]',
                            'defaults' => array(
                                'action' => 'code',
                            ),
                        ),
                    ),
                )
            ),
            'content-type' => array(
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => array(
                    'route'    => '/content-type',
                    'defaults' => array(
                        'controller' => 'general-index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'icon' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/icon/[:id].gif',
                            'defaults' => array(
                                'action' => 'content-type-icon',
                            ),
                        ),
                    ),
                )
            ),
            'zfcadmin'     => array(
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
