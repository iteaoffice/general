<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

use General\Controller;

return [
    'router' => [
        'routes' => [
            'image'         => [
                'child_routes' => [
                    'asset'    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/a/[:name]',
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => Controller\ImageController::class,
                                'action'     => 'asset',
                            ],
                        ],
                    ],
                    'country-flag'    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/f/[:id].[:ext]',
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => Controller\ImageController::class,
                                'action'     => 'flag',
                            ],
                        ],
                    ],
                    'challenge-icon'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/ci/[:id]-[:last-update].[:ext]',
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => Controller\ImageController::class,
                                'action'     => 'challenge-icon',
                            ],
                        ],
                    ],
                    'challenge-image' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/cim/[:id]-[:last-update].[:ext]',
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => Controller\ImageController::class,
                                'action'     => 'challenge-image',
                            ],
                        ],
                    ],
                ],
            ],
            'assets'        => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/assets/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test'),
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                    ],
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'style-image' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => "/style/image/[:source]",
                            'defaults' => [
                                //Explicitly add the controller here as the assets are collected
                                'controller' => Controller\StyleController::class,
                                'action'     => 'display',
                            ],
                        ],
                    ],

                ],
            ],
            'country'       => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/country',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
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
                ],
            ],
            'impact-stream' => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/impact-stream',
                    'defaults' => [
                        'controller' => Controller\ImpactStreamController::class,
                        'action'     => 'impact-stream',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'download'          => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/download.html',
                            'defaults' => [
                                'action' => 'download',
                            ],
                        ],
                    ],
                    'download-selected' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/download/selected.html',
                            'defaults' => [
                                'action' => 'download-selected',
                            ],
                        ],
                    ],
                    'download-single'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/download/[:docRef].pdf',
                            'defaults' => [
                                'action' => 'download-single',
                            ],
                        ],
                    ],
                ],
            ],
            'content-type'  => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/content-type',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
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
                ],
            ],
            'email'         => [
                'type'          => 'Literal',
                'priority'      => 1000,
                'options'       => [
                    'route'    => '/email',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'event' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/event.json',
                            'defaults' => [
                                'action' => 'event',
                            ],
                        ],
                    ],
                ],
            ],

        ],
    ],
];
