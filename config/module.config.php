<?php

declare(strict_types=1);

namespace General;

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */
use General\Acl;
use General\Controller;
use General\Factory;
use General\InputFilter;
use General\Navigation;
use General\Options;
use General\Service;
use General\View;
use Zend\Stdlib;

$config = [
    'controllers'        => [
        'factories' => [
            Controller\ChallengeController::class    => Controller\Factory\ControllerFactory::class,
            Controller\ContentTypeController::class  => Controller\Factory\ControllerFactory::class,
            Controller\CountryController::class      => Controller\Factory\ControllerFactory::class,
            Controller\CurrencyController::class     => Controller\Factory\ControllerFactory::class,
            Controller\PasswordController::class     => Controller\Factory\ControllerFactory::class,
            Controller\GenderController::class       => Controller\Factory\ControllerFactory::class,
            Controller\ImpactStreamController::class => Controller\Factory\ControllerFactory::class,
            Controller\IndexController::class        => Controller\Factory\ControllerFactory::class,
            Controller\StyleController::class        => Controller\Factory\ControllerFactory::class,
            Controller\EmailController::class        => Controller\Factory\ControllerFactory::class,
            Controller\TitleController::class        => Controller\Factory\ControllerFactory::class,
            Controller\VatController::class          => Controller\Factory\ControllerFactory::class,
            Controller\VatTypeController::class      => Controller\Factory\ControllerFactory::class,
            Controller\WebInfoController::class      => Controller\Factory\ControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'aliases'   => [
            'getGeneralFilter' => Controller\Plugin\GetFilter::class,
        ],
        'factories' => [
            Controller\Plugin\GetFilter::class => Controller\Factory\PluginFactory::class,
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
            'countryMap'          => View\Helper\CountryMap::class,
            'countryFlag'         => View\Helper\CountryFlag::class,
            'countryLink'         => View\Helper\CountryLink::class,
            'currencyLink'        => View\Helper\CurrencyLink::class,
            'passwordLink'        => View\Helper\PasswordLink::class,
            'emailMessageLink'    => View\Helper\EmailMessageLink::class,
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
            View\Helper\CountryMap::class          => View\Factory\ViewHelperFactory::class,
            View\Helper\CountryFlag::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\CountryLink::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\CurrencyLink::class        => View\Factory\ViewHelperFactory::class,
            View\Helper\PasswordLink::class        => View\Factory\ViewHelperFactory::class,
            View\Helper\VatLink::class             => View\Factory\ViewHelperFactory::class,
            View\Helper\GenderLink::class          => View\Factory\ViewHelperFactory::class,
            View\Helper\EmailMessageLink::class    => View\Factory\ViewHelperFactory::class,
            View\Helper\TitleLink::class           => View\Factory\ViewHelperFactory::class,
            View\Helper\VatTypeLink::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\ChallengeLink::class       => View\Factory\ViewHelperFactory::class,
            View\Helper\WebInfoLink::class         => View\Factory\ViewHelperFactory::class,
            View\Helper\ContentTypeLink::class     => View\Factory\ViewHelperFactory::class,
            View\Helper\ContentTypeIcon::class     => View\Factory\ViewHelperFactory::class,

        ],
    ],
    'service_manager'    => [
        'factories' => [
            Options\ModuleOptions::class                  => Factory\ModuleOptionsFactory::class,
            Service\GeneralService::class                 => Factory\GeneralServiceFactory::class,
            Service\EmailService::class                   => Factory\EmailServiceFactory::class,
            Service\FormService::class                    => Factory\FormServiceFactory::class,
            Acl\Assertion\ContentType::class              => Acl\Factory\AssertionFactory::class,
            Acl\Assertion\Country::class                  => Acl\Factory\AssertionFactory::class,
            Acl\Assertion\WebInfo::class                  => Acl\Factory\AssertionFactory::class,
            InputFilter\ChallengeFilter::class            => Factory\InputFilterFactory::class,
            InputFilter\CommunityTypeFilter::class        => Factory\InputFilterFactory::class,
            InputFilter\CountryFilter::class              => Factory\InputFilterFactory::class,
            InputFilter\PasswordFilter::class             => Factory\InputFilterFactory::class,
            InputFilter\GenderFilter::class               => Factory\InputFilterFactory::class,
            InputFilter\TitleFilter::class                => Factory\InputFilterFactory::class,
            InputFilter\WebInfoFilter::class              => Factory\InputFilterFactory::class,
            Navigation\Invokable\ChallengeLabel::class    => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\ContentTypeLabel::class  => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\EmailMessageLabel::class => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\CountryLabel::class      => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\CurrencyLabel::class     => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\PasswordLabel::class     => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\GenderLabel::class       => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\TitleLabel::class        => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\VatLabel::class          => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\VatTypeLabel::class      => Navigation\Factory\NavigationInvokableFactory::class,
            Navigation\Invokable\WebInfoLabel::class      => Navigation\Factory\NavigationInvokableFactory::class,
        ],
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
