<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Program
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2018 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace General;

use Contact\Service\ContactService;
use Content\Navigation\Service\UpdateNavigationService;
use Content\Service\ArticleService;
use Doctrine\ORM\EntityManager;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\GeneralService;
use Organisation\Service\OrganisationService;
use Program\Service\ProgramService;
use Project\Options\ModuleOptions;
use Project\Search\Service\ImpactStreamSearchService;
use Project\Service\ProjectService;
use Zend\Authentication\AuthenticationService;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use ZfcTwig\View\TwigRenderer;

return [
    ConfigAbstractFactory::class => [
        Controller\Plugin\GetFilter::class        => [
            'Application'
        ],
        Controller\ChallengeController::class     => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class,
            EntityManager::class
        ],
        Controller\ChallengeTypeController::class => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\ContentTypeController::class   => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\CountryController::class       => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\CurrencyController::class      => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\EmailController::class         => [
            GeneralService::class,
            FormService::class,
            EntityManager::class,
            TranslatorInterface::class
        ],
        Controller\ExchangeRateController::class  => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\GenderController::class        => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\ImageController::class         => [
            GeneralService::class,
        ],
        Controller\ImpactStreamController::class  => [
            ProjectService::class,
            GeneralService::class,
            ImpactStreamSearchService::class
        ],
        Controller\LogController::class           => [
            GeneralService::class,
            EntityManager::class,
            TranslatorInterface::class
        ],
        Controller\PasswordController::class      => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\TitleController::class         => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\VatController::class           => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\VatTypeController::class       => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class
        ],
        Controller\WebInfoController::class       => [
            GeneralService::class,
            FormService::class,
            TranslatorInterface::class,
            EmailService::class
        ],
        Service\EmailService::class               => [
            'Config',
            ContactService::class,
            GeneralService::class,
            AuthenticationService::class,
            EntityManager::class,
            TwigRenderer::class
        ],
        Service\GeneralService::class             => [
            EntityManager::class
        ],
        InputFilter\ChallengeFilter::class        => [
            EntityManager::class
        ],
        InputFilter\Challenge\TypeFilter::class   => [
            EntityManager::class
        ],
        InputFilter\Challenge\TypeFilter::class   => [
            EntityManager::class
        ],
        InputFilter\CountryFilter::class          => [
            EntityManager::class
        ],
        InputFilter\GenderFilter::class           => [
            EntityManager::class
        ],
        InputFilter\TitleFilter::class            => [
            EntityManager::class
        ],
        InputFilter\WebInfoFilter::class          => [
            EntityManager::class
        ],
        View\Handler\ImpactStreamHandler::class   => [
            'Application',
            'ViewHelperManager',
            TwigRenderer::class,
            AuthenticationService::class,
            UpdateNavigationService::class,
            TranslatorInterface::class,
            ImpactStreamSearchService::class,
            GeneralService::class,
            ProjectService::class
        ],
        View\Handler\ChallengeHandler::class      => [
            'Application',
            'ViewHelperManager',
            TwigRenderer::class,
            AuthenticationService::class,
            UpdateNavigationService::class,
            TranslatorInterface::class,
            GeneralService::class,
            ProjectService::class
        ],
        View\Handler\CountryHandler::class        => [
            'Application',
            'ViewHelperManager',
            TwigRenderer::class,
            AuthenticationService::class,
            UpdateNavigationService::class,
            TranslatorInterface::class,
            ModuleOptions::class,
            GeneralService::class,
            ProjectService::class,
            ProgramService::class,
            OrganisationService::class,
            ArticleService::class
        ],
    ]
];