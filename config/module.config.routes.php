<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c] 2004-2014 ITEA Office (http://itea3.org]
 */
return [
    'router' => [
        'routes' => [
            'assets'       => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test'),
                    'defaults' => [
                        'controller' => 'general-index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'country-flag'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => "/country-flag/[:iso3].[:ext]",
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => 'general-index',
                                'action'     => 'country-flag',
                            ],
                        ],
                    ],
                    'content-type-icon' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => "/content-type-icon/[:hash].gif",
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => 'general-index',
                                'action'     => 'content-type-icon',
                            ],
                        ],
                    ],
                    'style-image'       => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => "/style/image/[:source]",
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => 'general-style',
                                'action'     => 'display',
                            ],
                        ],
                    ],
                ],
            ],
            'country'      => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/country',
                    'defaults' => [
                        'controller' => 'general-index',
                        'action'     => 'country',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'code' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/code/[:cd]',
                            'defaults' => [
                                'action' => 'code',
                            ],
                        ],
                    ],
                ]
            ],
            'content-type' => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/content-type',
                    'defaults' => [
                        'controller' => 'general-index',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'icon' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/icon/[:id].gif',
                            'defaults' => [
                                'action' => 'content-type-icon',
                            ],
                        ],
                    ],
                ]
            ],
            'zfcadmin'     => [
                'child_routes' => [
                    'general-manager' => [
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'priority'      => 1000,
                        'options'       => [
                            'route'    => '/general-manager',
                            'defaults' => [
                                'controller' => 'general-manager',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'messages' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/messages.html',
                                    'defaults' => [
                                        'action' => 'messages',
                                    ],
                                ],
                            ],
                            'message'  => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'       => '/message/[:id].html',
                                    'constraints' => [
                                        'id' => '\d+',
                                    ],
                                    'defaults'    => [
                                        'action' => 'message',
                                    ],
                                ],
                            ],
                            'new'      => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/new/:entity',
                                    'defaults' => [
                                        'action' => 'new',
                                    ],
                                ],
                            ],
                            'edit'     => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/edit/:entity/:id',
                                    'defaults' => [
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'delete'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/delete/:entity/:id',
                                    'defaults' => [
                                        'action' => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
