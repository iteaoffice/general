<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */
declare(strict_types=1);

namespace General;

use General\Controller;
use General\Factory;
use General\InputFilter;
use General\Navigation;
use General\Options;
use General\Service;
use General\View;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Zend\Stdlib;

$config = [
    'controllers'        => [
        'factories' => [
            Controller\ChallengeController::class     => ConfigAbstractFactory::class,
            Controller\ChallengeTypeController::class => ConfigAbstractFactory::class,
            Controller\ContentTypeController::class   => ConfigAbstractFactory::class,
            Controller\CountryController::class       => ConfigAbstractFactory::class,
            Controller\CurrencyController::class      => ConfigAbstractFactory::class,
            Controller\ExchangeRateController::class  => ConfigAbstractFactory::class,
            Controller\ImageController::class         => ConfigAbstractFactory::class,
            Controller\PasswordController::class      => ConfigAbstractFactory::class,
            Controller\GenderController::class        => ConfigAbstractFactory::class,
            Controller\ImpactStreamController::class  => ConfigAbstractFactory::class,
            Controller\EmailController::class         => ConfigAbstractFactory::class,
            Controller\LogController::class           => ConfigAbstractFactory::class,
            Controller\TitleController::class         => ConfigAbstractFactory::class,
            Controller\VatController::class           => ConfigAbstractFactory::class,
            Controller\VatTypeController::class       => ConfigAbstractFactory::class,
            Controller\WebInfoController::class       => ConfigAbstractFactory::class,
        ],
    ],
    'controller_plugins' => [
        'aliases'   => [
            'getFilter' => Controller\Plugin\GetFilter::class,
        ],
        'factories' => [
            Controller\Plugin\GetFilter::class => ConfigAbstractFactory::class,
        ],
    ],
    'view_manager'       => [
        'template_map' => include __DIR__ . '/../template_map.php',
    ],
    'view_helpers'       => [
        'aliases'   => [
            'countryHandler'      => View\Helper\CountryHandler::class,
            'impactStreamHandler' => View\Helper\ImpactStreamHandler::class,
            'challengeHandler'    => View\Helper\ChallengeHandler::class,
            'challengeIcon'       => View\Helper\ChallengeIcon::class,
            'challengeImage'      => View\Helper\ChallengeImage::class,
            'challengeTypeLink'   => View\Helper\ChallengeTypeLink::class,
            'countryMap'          => View\Helper\CountryMap::class,
            'countryFlag'         => View\Helper\CountryFlag::class,
            'countryLink'         => View\Helper\CountryLink::class,
            'currencyLink'        => View\Helper\CurrencyLink::class,
            'exchangeRateLink'    => View\Helper\ExchangeRateLink::class,
            'passwordLink'        => View\Helper\PasswordLink::class,
            'emailMessageLink'    => View\Helper\EmailMessageLink::class,
            'generalLogLink'      => View\Helper\LogLink::class,
            'vatLink'             => View\Helper\VatLink::class,
            'genderLink'          => View\Helper\GenderLink::class,
            'titleLink'           => View\Helper\TitleLink::class,
            'vatTypeLink'         => View\Helper\VatTypeLink::class,
            'challengeLink'       => View\Helper\ChallengeLink::class,
            'webInfoLink'         => View\Helper\WebInfoLink::class,
            'contentTypeLink'     => View\Helper\ContentTypeLink::class,
            'contentTypeIcon'     => View\Helper\ContentTypeIcon::class,
        ],
        'factories' => [
            View\Helper\CountryHandler::class      => View\Factory\ViewHelperFactory::class,
            View\Helper\ImpactStreamHandler::class => View\Factory\ViewHelperFactory::class,
            View\Helper\ChallengeHandler::class    => View\Factory\ViewHelperFactory::class,
            View\Helper\ChallengeIcon::class       => View\Factory\ViewHelperFactory::class,
            View\Helper\ChallengeImage::class      => View\Factory\ViewHelperFactory::class,
            View\Helper\ChallengeTypeLink::class   => View\Factory\ViewHelperFactory::class,
            View\Helper\CountryMap::class          => View\Factory\ViewHelperFactory::class,
            View\Helper\CountryFlag::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\CountryLink::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\CurrencyLink::class        => View\Factory\ViewHelperFactory::class,
            View\Helper\ExchangeRateLink::class    => View\Factory\ViewHelperFactory::class,
            View\Helper\PasswordLink::class        => View\Factory\ViewHelperFactory::class,
            View\Helper\VatLink::class             => View\Factory\ViewHelperFactory::class,
            View\Helper\GenderLink::class          => View\Factory\ViewHelperFactory::class,
            View\Helper\EmailMessageLink::class    => View\Factory\ViewHelperFactory::class,
            View\Helper\LogLink::class             => View\Factory\ViewHelperFactory::class,
            View\Helper\TitleLink::class           => View\Factory\ViewHelperFactory::class,
            View\Helper\VatTypeLink::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\ChallengeLink::class       => View\Factory\ViewHelperFactory::class,
            View\Helper\WebInfoLink::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\ContentTypeLink::class     => View\Factory\ViewHelperFactory::class,
            View\Helper\ContentTypeIcon::class     => View\Factory\ViewHelperFactory::class,

        ],
    ],
    'service_manager'    => [
        'factories'  => [
            InputFilter\ChallengeFilter::class             => ConfigAbstractFactory::class,
            InputFilter\Challenge\TypeFilter::class        => ConfigAbstractFactory::class,
            InputFilter\CountryFilter::class               => ConfigAbstractFactory::class,
            InputFilter\GenderFilter::class                => ConfigAbstractFactory::class,
            InputFilter\TitleFilter::class                 => ConfigAbstractFactory::class,
            InputFilter\WebInfoFilter::class               => ConfigAbstractFactory::class,
            Options\ModuleOptions::class                   => Factory\ModuleOptionsFactory::class,
            Service\GeneralService::class                  => ConfigAbstractFactory::class,
            Service\EmailService::class                    => ConfigAbstractFactory::class,
            Service\FormService::class                     => Factory\FormServiceFactory::class,
            Navigation\Invokable\ChallengeLabel::class     => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\ChallengeTypeLabel::class => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\ContentTypeLabel::class   => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\EmailMessageLabel::class  => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\LogLabel::class           => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\CountryLabel::class       => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\CurrencyLabel::class      => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\PasswordLabel::class      => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\GenderLabel::class        => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\TitleLabel::class         => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\VatLabel::class           => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\VatTypeLabel::class       => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\WebInfoLabel::class       => Navigation\Factory\NavigationInvokableFactory::class,
        ],
        'invokables' => [
            InputFilter\PasswordFilter::class,
        ]
    ],
    'asset_manager'      => [
        'resolver_configs' => [
            'collections' => [
                'assets/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test') . '/js/jvectormap.js'   => [
                    'js/jquery/jquery.mousewheel.min.js',
                    'js/jquery/jquery-jvectormap-2.0.2.min.js',
                    'js/jquery/jquery-jvectormap-europe-mill-en.js',
                ],
                'assets/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test') . '/css/jvectormap.css' => [
                    'css/jquery-jvectormap-2.0.2.css',
                ],
            ],
            'paths'       => [__DIR__ . '/../public',],
            'caching'     => [
                'assets/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test') . '/js/jvectormap.js?'  => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => ['dir' => __DIR__ . '/../../../public',],
                ],
                'assets/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test') . '/css/jvectormap.css' => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => ['dir' => __DIR__ . '/../../../public',],
                ],
            ],
        ],
    ],
    'doctrine'           => [
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

foreach (Stdlib\Glob::glob(__DIR__ . '/module.config.{,*}.php', Stdlib\Glob::GLOB_BRACE) as $file) {
    $config = Stdlib\ArrayUtils::merge($config, include $file);
}

return $config;
