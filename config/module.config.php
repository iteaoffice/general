<?php
namespace General;

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c] 2004-2015 ITEA Office (https://itea3.org]
 */
use General\Acl\Assertion;
use General\Controller;
use General\Service\FormService;
use General\Service\GeneralService;
use General\Service\ServiceInitializer;
use General\View\Helper;
use Zend\Stdlib\ArrayUtils;

$config = [
    'controllers'     => [
        'initializers' => [
            Controller\ControllerInitializer::class
        ],
        'invokables'   => [
            Controller\IndexController::class       => Controller\IndexController::class,
            Controller\StyleController::class       => Controller\StyleController::class,
            Controller\VatController::class         => Controller\VatController::class,
            Controller\VatTypeController::class     => Controller\VatTypeController::class,
            Controller\GenderController::class      => Controller\GenderController::class,
            Controller\TitleController::class       => Controller\TitleController::class,
            Controller\WebInfoController::class     => Controller\WebInfoController::class,
            Controller\CountryController::class     => Controller\CountryController::class,
            Controller\ChallengeController::class   => Controller\ChallengeController::class,
            Controller\ContentTypeController::class => Controller\ContentTypeController::class,
        ],
    ],
    'view_manager'    => [
        'template_map' => include __DIR__ . '/../template_map.php',
    ],
    'view_helpers'    => [
        'initializers' => [
            Helper\ViewHelperInitializer::class
        ],
        'invokables'   => [
            'generalServiceProxy' => Helper\GeneralServiceProxy::class,
            'countryHandler'      => Helper\CountryHandler::class,
            'challengeHandler'    => Helper\ChallengeHandler::class,
            'countryMap'          => Helper\CountryMap::class,
            'countryFlag'         => Helper\CountryFlag::class,
            'countryLink'         => Helper\CountryLink::class,
            'vatLink'             => Helper\VatLink::class,
            'genderLink'          => Helper\GenderLink::class,
            'titleLink'           => Helper\TitleLink::class,
            'vatTypeLink'         => Helper\VatTypeLink::class,
            'challengeLink'       => Helper\ChallengeLink::class,
            'webInfoLink'         => Helper\WebInfoLink::class,
            'contentTypeLink'     => Helper\ContentTypeLink::class,
            'contentTypeIcon'     => Helper\ContentTypeIcon::class,
        ]
    ],
    'service_manager' => [
        'initializers' => [
            ServiceInitializer::class
        ],
        'invokables'   => [
            'general_web_info_form_filter'     => 'General\Form\FilterCreateObject',
            'general_country_form_filter'      => 'General\Form\FilterCreateObject',
            'general_challenge_form_filter'    => 'General\Form\FilterCreateObject',
            'general_gender_form_filter'       => 'General\Form\FilterCreateObject',
            'general_title_form_filter'        => 'General\Form\FilterCreateObject',
            'general_vat_form_filter'          => 'General\Form\FilterCreateObject',
            'general_vat_type_form_filter'     => 'General\Form\FilterCreateObject',
            'general_content_type_form_filter' => 'General\Form\FilterCreateObject',
            GeneralService::class              => GeneralService::class,
            FormService::class                 => FormService::class,
            Assertion\WebInfo::class           => Assertion\WebInfo::class,
            Assertion\Country::class           => Assertion\Country::class,
            Assertion\ContentType::class       => Assertion\ContentType::class
        ]
    ],
    'asset_manager'   => [
        'resolver_configs' => [
            'collections' => [
                'assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') . '/js/jvectormap.js'   => [
                    'js/jquery/jquery.mousewheel.min.js',
                    'js/jquery/jquery-jvectormap-2.0.2.min.js',
                    'js/jquery/jquery-jvectormap-europe-mill-en.js',
                ],
                'assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') . '/css/jvectormap.css' => [
                    'css/jquery-jvectormap-2.0.2.css',
                ],
            ],
            'paths'       => [__DIR__ . '/../public',],
            'caching'     => [
                'assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') . '/js/jvectormap.js?'  => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => ['dir' => __DIR__ . '/../../../public',],
                ],
                'assets/' . (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') . '/css/jvectormap.css' => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => ['dir' => __DIR__ . '/../../../public',],
                ],
            ],
        ],
    ],
    'doctrine'        => [
        'driver'       => [
            'general_annotation_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [__DIR__ . '/../src/General/Entity/']
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
    __DIR__ . '/module.config.routes.admin.php',
    __DIR__ . '/module.config.general.php',
    __DIR__ . '/module.config.navigation.php',
    __DIR__ . '/module.config.authorize.php',
];
foreach ($configFiles as $configFile) {
    $config = ArrayUtils::merge($config, include $configFile);
}

return $config;
