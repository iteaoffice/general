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
use General\Acl;
use General\Controller;
use General\Factory;
use General\Options;
use General\Service;
use General\View;
use Zend\Stdlib\ArrayUtils;

$config = [
    'controllers'     => [
        'abstract_factories' => [
            Controller\Factory\ControllerInvokableAbstractFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_map' => include __DIR__ . '/../template_map.php',
    ],
    'view_helpers'    => [
        'aliases'   => [
            'countryHandler'   => View\Helper\CountryHandler::class,
            'challengeHandler' => View\Helper\ChallengeHandler::class,
            'countryMap'       => View\Helper\CountryMap::class,
            'countryFlag'      => View\Helper\CountryFlag::class,
            'countryLink'      => View\Helper\CountryLink::class,
            'vatLink'          => View\Helper\VatLink::class,
            'genderLink'       => View\Helper\GenderLink::class,
            'titleLink'        => View\Helper\TitleLink::class,
            'vatTypeLink'      => View\Helper\VatTypeLink::class,
            'challengeLink'    => View\Helper\ChallengeLink::class,
            'webInfoLink'      => View\Helper\WebInfoLink::class,
            'contentTypeLink'  => View\Helper\ContentTypeLink::class,
            'contentTypeIcon'  => View\Helper\ContentTypeIcon::class,
        ],
        'factories' => [
            View\Helper\CountryHandler::class   => View\Factory\LinkInvokableFactory::class,
            View\Helper\ChallengeHandler::class => View\Factory\LinkInvokableFactory::class,
            View\Helper\CountryMap::class       => View\Factory\LinkInvokableFactory::class,
            View\Helper\CountryFlag::class      => View\Factory\LinkInvokableFactory::class,
            View\Helper\CountryLink::class      => View\Factory\LinkInvokableFactory::class,
            View\Helper\VatLink::class          => View\Factory\LinkInvokableFactory::class,
            View\Helper\GenderLink::class       => View\Factory\LinkInvokableFactory::class,
            View\Helper\TitleLink::class        => View\Factory\LinkInvokableFactory::class,
            View\Helper\VatTypeLink::class      => View\Factory\LinkInvokableFactory::class,
            View\Helper\ChallengeLink::class    => View\Factory\LinkInvokableFactory::class,
            View\Helper\WebInfoLink::class      => View\Factory\LinkInvokableFactory::class,
            View\Helper\ContentTypeLink::class  => View\Factory\LinkInvokableFactory::class,
            View\Helper\ContentTypeIcon::class  => View\Factory\LinkInvokableFactory::class,

        ]
    ],
    'service_manager' => [
        'factories'          => [
            Options\ModuleOptions::class  => Factory\ModuleOptionsFactory::class,
            Service\GeneralService::class => Factory\GeneralServiceFactory::class,
            Service\EmailService::class   => Factory\EmailServiceFactory::class,
            Service\FormService::class    => Factory\FormServiceFactory::class,
        ],
        'abstract_factories' => [
            Acl\Factory\AssertionInvokableAbstractFactory::class,
        ],
        'invokables'         => [
            'general_web_info_form_filter'     => 'General\Form\FilterCreateObject',
            'general_country_form_filter'      => 'General\Form\FilterCreateObject',
            'general_challenge_form_filter'    => 'General\Form\FilterCreateObject',
            'general_gender_form_filter'       => 'General\Form\FilterCreateObject',
            'general_title_form_filter'        => 'General\Form\FilterCreateObject',
            'general_vat_form_filter'          => 'General\Form\FilterCreateObject',
            'general_vat_type_form_filter'     => 'General\Form\FilterCreateObject',
            'general_content_type_form_filter' => 'General\Form\FilterCreateObject',
        ],
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
                'paths' => [__DIR__ . '/../src/Entity/'],
            ],
            'orm_default'               => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => 'general_annotation_driver',
                ],
            ],
        ],
        'eventmanager' => [
            'orm_general' => [
                'subscribers' => [
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\Sluggable\SluggableListener',
                ],
            ],
        ],
    ],
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
