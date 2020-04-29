<?php

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\Driver\DriverChain;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Timestampable\TimestampableListener;
use General\Controller;
use General\Factory;
use General\InputFilter;
use General\Navigation;
use General\Navigation\Factory\NavigationInvokableFactory;
use General\Options;
use General\Service;
use General\View;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\Stdlib;

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
        'aliases'    => [
            'challengeHandler'  => View\Handler\ChallengeHandler::class,
            'challengeIcon'     => View\Helper\Challenge\ChallengeIcon::class,
            'challengeImage'    => View\Helper\Challenge\ChallengeImage::class,
            'challengeTypeLink' => View\Helper\Challenge\TypeLink::class,
            'countryMap'        => View\Helper\Country\CountryMap::class,
            'countryFlag'       => View\Helper\Country\CountryFlag::class,
            'countryLink'       => View\Helper\Country\CountryLink::class,
            'currencyLink'      => View\Helper\CurrencyLink::class,
            'exchangeRateLink'  => View\Helper\ExchangeRateLink::class,
            'passwordLink'      => View\Helper\PasswordLink::class,
            'emailMessageLink'  => View\Helper\EmailMessageLink::class,
            'generalLogLink'    => View\Helper\LogLink::class,
            'vatLink'           => View\Helper\VatLink::class,
            'genderLink'        => View\Helper\GenderLink::class,
            'titleLink'         => View\Helper\TitleLink::class,
            'vatTypeLink'       => View\Helper\VatTypeLink::class,
            'challengeLink'     => View\Helper\Challenge\ChallengeLink::class,
            'webInfoLink'       => View\Helper\WebInfoLink::class,
            'contentTypeLink'   => View\Helper\ContentTypeLink::class,
            'contentTypeIcon'   => View\Helper\ContentTypeIcon::class,
            'countryselect'     => Form\View\Helper\CountrySelect::class
        ],
        'invokables' => [
            Form\View\Helper\CountrySelect::class
        ],
        'factories'  => [
            View\Helper\Challenge\ChallengeIcon::class  => View\Factory\ImageHelperFactory::class,
            View\Helper\Challenge\ChallengeImage::class => View\Factory\ImageHelperFactory::class,
            View\Helper\Country\CountryFlag::class      => View\Factory\ImageHelperFactory::class,
            View\Handler\CountryHandler::class          => ConfigAbstractFactory::class,
            View\Handler\ImpactStreamHandler::class     => ConfigAbstractFactory::class,
            View\Handler\ChallengeHandler::class        => ConfigAbstractFactory::class,
            View\Helper\Challenge\TypeLink::class       => View\Factory\LinkHelperFactory::class,
            View\Helper\Challenge\ChallengeLink::class  => View\Factory\LinkHelperFactory::class,
            View\Helper\Country\CountryLink::class      => View\Factory\LinkHelperFactory::class,
            View\Helper\CurrencyLink::class             => View\Factory\LinkHelperFactory::class,
            View\Helper\ExchangeRateLink::class         => View\Factory\LinkHelperFactory::class,
            View\Helper\PasswordLink::class             => View\Factory\LinkHelperFactory::class,
            View\Helper\VatLink::class                  => View\Factory\LinkHelperFactory::class,
            View\Helper\GenderLink::class               => View\Factory\LinkHelperFactory::class,
            View\Helper\EmailMessageLink::class         => View\Factory\LinkHelperFactory::class,
            View\Helper\LogLink::class                  => View\Factory\LinkHelperFactory::class,
            View\Helper\TitleLink::class                => View\Factory\LinkHelperFactory::class,
            View\Helper\VatTypeLink::class              => View\Factory\LinkHelperFactory::class,
            View\Helper\WebInfoLink::class              => View\Factory\LinkHelperFactory::class,
            View\Helper\ContentTypeLink::class          => View\Factory\LinkHelperFactory::class,
            View\Helper\Country\CountryMap::class       => ConfigAbstractFactory::class,
            View\Helper\ContentTypeIcon::class          => ConfigAbstractFactory::class,
        ],
    ],
    'service_manager'    => [
        'factories'  => [
            Options\ModuleOptions::class                   => Factory\ModuleOptionsFactory::class,
            Options\EmailOptions::class                    => Factory\EmailOptionsFactory::class,
            InputFilter\ChallengeFilter::class             => ConfigAbstractFactory::class,
            InputFilter\Challenge\TypeFilter::class        => ConfigAbstractFactory::class,
            InputFilter\CountryFilter::class               => ConfigAbstractFactory::class,
            InputFilter\GenderFilter::class                => ConfigAbstractFactory::class,
            InputFilter\TitleFilter::class                 => ConfigAbstractFactory::class,
            InputFilter\WebInfoFilter::class               => ConfigAbstractFactory::class,
            Service\GeneralService::class                  => ConfigAbstractFactory::class,
            Service\CountryService::class                  => ConfigAbstractFactory::class,
            Service\EmailService::class                    => ConfigAbstractFactory::class,
            Service\FormService::class                     => Factory\FormServiceFactory::class,
            Search\Service\CountrySearchService::class     => ConfigAbstractFactory::class,
            Navigation\Invokable\ChallengeLabel::class     => NavigationInvokableFactory::class,
            Navigation\Invokable\ChallengeTypeLabel::class => NavigationInvokableFactory::class,
            Navigation\Invokable\ContentTypeLabel::class   => NavigationInvokableFactory::class,
            Navigation\Invokable\EmailMessageLabel::class  => NavigationInvokableFactory::class,
            Navigation\Invokable\LogLabel::class           => NavigationInvokableFactory::class,
            Navigation\Invokable\CountryLabel::class       => NavigationInvokableFactory::class,
            Navigation\Invokable\CurrencyLabel::class      => NavigationInvokableFactory::class,
            Navigation\Invokable\PasswordLabel::class      => NavigationInvokableFactory::class,
            Navigation\Invokable\GenderLabel::class        => NavigationInvokableFactory::class,
            Navigation\Invokable\TitleLabel::class         => NavigationInvokableFactory::class,
            Navigation\Invokable\VatLabel::class           => NavigationInvokableFactory::class,
            Navigation\Invokable\VatTypeLabel::class       => NavigationInvokableFactory::class,
            Navigation\Invokable\WebInfoLabel::class       => NavigationInvokableFactory::class,
            Navigation\Service\NavigationService::class    => Navigation\Factory\NavigationServiceFactory::class,
        ],
        'invokables' => [
            InputFilter\PasswordFilter::class,
        ]
    ],
    'asset_manager'      => [
        'resolver_configs' => [
            'collections' => [
                'assets/' . (defined('ITEAOFFICE_HOST') ? ITEAOFFICE_HOST : 'test') . '/js/jvectormap.js'   => [
                    'js/jquery/jquery.mousewheel.min.js',
                    'js/jquery/jquery-jvectormap-2.0.2.min.js',
                    'js/jquery/jquery-jvectormap-europe-mill-en.js',
                    'js/jquery/jquery-jvectormap-world.js',
                ],
                'assets/' . (defined('ITEAOFFICE_HOST') ? ITEAOFFICE_HOST : 'test') . '/css/jvectormap.css' => [
                    'css/jquery-jvectormap-2.0.2.css',
                ],
            ],
            'paths'       => [__DIR__ . '/../public',],
            'caching'     => [
                'assets/' . (defined('ITEAOFFICE_HOST') ? ITEAOFFICE_HOST : 'test') . '/js/jvectormap.js?'  => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => ['dir' => __DIR__ . '/../../../public',],
                ],
                'assets/' . (defined('ITEAOFFICE_HOST') ? ITEAOFFICE_HOST : 'test') . '/css/jvectormap.css' => [
                    'cache'   => 'FilePath', //Filesystem for development
                    'options' => ['dir' => __DIR__ . '/../../../public',],
                ],
            ],
        ],
    ],
    'doctrine'           => [
        'driver'       => [
            'general_annotation_driver' => [
                'class' => AnnotationDriver::class,
                'paths' => [__DIR__ . '/../src/Entity/'],
            ],
            'orm_default'               => [
                'class'   => DriverChain::class,
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => 'general_annotation_driver',
                ],
            ],
        ],
        'eventmanager' => [
            'orm_general' => [
                'subscribers' => [
                    TimestampableListener::class,
                    SluggableListener::class,
                ],
            ],
        ],
    ],
];

foreach (Stdlib\Glob::glob(__DIR__ . '/module.config.{,*}.php', Stdlib\Glob::GLOB_BRACE) as $file) {
    $config = Stdlib\ArrayUtils::merge($config, include $file);
}

return $config;
