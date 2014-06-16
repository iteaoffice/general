<?php
namespace General;

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c] 2004-2014 ITEA Office (http://itea3.org]
 */

use General\Controller\ControllerInitializer;
use General\Service\ServiceInitializer;
use General\View\Helper\ViewHelperInitializer;
use Zend\Stdlib\ArrayUtils;

$config = [
    'controllers'     => [
        'initializers' => [
            ControllerInitializer::class
        ],
        'invokables'   => [
            'general-index' => 'General\Controller\IndexController',
            'general-style' => 'General\Controller\StyleController',
        ],
    ],
    'view_manager'    => [
        'template_map' => include __DIR__ . '/../template_map.php',
    ],
    'view_helpers'    => [
        'initializers' => [
            ViewHelperInitializer::class
        ],
        'invokables'   => [
            'generalServiceProxy' => 'General\View\Helper\GeneralServiceProxy',
            'countryHandler'      => 'General\View\Helper\CountryHandler',
            'challengeHandler'    => 'General\View\Helper\ChallengeHandler',
            'countryMap'          => 'General\View\Helper\CountryMap',
            'countryFlag'         => 'General\View\Helper\CountryFlag',
            'countryLink'         => 'General\View\Helper\CountryLink',
            'challengeLink'       => 'General\View\Helper\ChallengeLink',
            'contentTypeIcon'     => 'General\View\Helper\ContentTypeIcon',
        ]
    ],
    'service_manager' => [
        'initializers' => [
            ServiceInitializer::class
        ],
        'factories'    => [
            'general-assertion' => 'General\Acl\Assertion\General',
        ],
        'invokables'   => [
            'general_general_service' => 'General\Service\GeneralService',
            'general_form_service'    => 'General\Service\FormService',

        ]
    ],
    'asset_manager'   => [
        'resolver_configs' => [
            'collections' => [
                'assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') . '/js/jvectormap.js' => [
                    'js/jquery/jquery.mousewheel.min.js',
                    'js/jquery/jquery-jvectormap-1.1.1.min.js',
                    'js/jquery/jquery-jvectormap-europe-mill-en.js',
                ],
            ],
            'paths'       => [
                __DIR__ . '/../public',
            ],
            'caching'     => [
                'assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') . '/js/jvectormap.js' => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => [
                        'dir' => __DIR__ . '/../../../public',
                    ],
                ],
            ],
        ],
    ],
    'doctrine'        => [
        'driver'       => [
            'general_annotation_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/General/Entity/'
                ]
            ],
            'orm_default'               => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => 'general_annotation_driver',
                ]
            ]
        ],
        'eventmanager' => [
            'orm_general' => [
                'subscribers' => [
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\Sluggable\SluggableListener',
                ]
            ],
        ],
    ]
];

$configFiles = [
    __DIR__ . '/module.config.routes.php',
    __DIR__ . '/module.config.general.php',
    __DIR__ . '/module.config.navigation.php',
    __DIR__ . '/module.config.authorize.php',
];

foreach ($configFiles as $configFile) {
    $config = ArrayUtils::merge($config, include $configFile);
}

return $config;
