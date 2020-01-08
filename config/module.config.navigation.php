<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */
return [
    'navigation' => [
        'admin' => [
            'management' => [
                'pages' => [
                    'password' => [
                        'label' => _("txt-nav-password-list"),
                        'route' => 'zfcadmin/password/list',
                        'pages' => [
                            'password-view' => [
                                'route'   => 'zfcadmin/password/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Password::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\PasswordLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'password-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/password/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Password::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'password-new'  => [
                                'label'   => _("txt-new-password"),
                                'route'   => 'zfcadmin/password/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                ],
            ],
            'config'     => [
                'pages' => [
                    'web-info'     => [
                        'label' => _("txt-nav-web-info-list"),
                        'route' => 'zfcadmin/web-info/list',
                        'pages' => [
                            'web-info-view' => [
                                'route'   => 'zfcadmin/web-info/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\WebInfo::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\WebInfoLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'web-info-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/web-info/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\WebInfo::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'web-info-new'  => [
                                'label'   => _("txt-new-web-info"),
                                'route'   => 'zfcadmin/web-info/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'country'      => [
                        'label' => _("txt-nav-country-list"),
                        'route' => 'zfcadmin/country/list',
                        'pages' => [
                            'country-view' => [
                                'route'   => 'zfcadmin/country/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Country::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\CountryLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'country-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/country/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Country::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'country-new'  => [
                                'label'   => _("txt-new-country"),
                                'route'   => 'zfcadmin/country/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'currency'     => [
                        'label' => _("txt-nav-currency-list"),
                        'route' => 'zfcadmin/currency/list',
                        'pages' => [
                            'currency-view' => [
                                'route'   => 'zfcadmin/currency/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Currency::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\CurrencyLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'currency-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/currency/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Currency::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'currency-new'  => [
                                'label'   => _("txt-new-currency"),
                                'route'   => 'zfcadmin/currency/new',
                                'visible' => false,
                            ],
                            'exchange-rate-new'  => [
                                'label'   => _("txt-new-exchange-rate"),
                                'route'   => 'zfcadmin/currency/exchange-rate/new',
                                'visible' => false,
                            ],
                            'exchange-rate-edit'  => [
                                'label'   => _("txt-edit-exchange-rate"),
                                'route'   => 'zfcadmin/currency/exchange-rate/edit',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'challenge'    => [
                        'label' => _("txt-nav-challenge-list"),
                        'route' => 'zfcadmin/challenge/list',
                        'pages' => [
                            'challenge-view' => [
                                'route'   => 'zfcadmin/challenge/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Challenge::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\ChallengeLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'challenge-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/challenge/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Challenge::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'challenge-new'  => [
                                'label'   => _("txt-new-challenge"),
                                'route'   => 'zfcadmin/challenge/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'challenge-type'          => [
                        'label' => _("txt-nav-challenge-types"),
                        'route' => 'zfcadmin/challenge/type/list',
                        'pages' => [
                            'view' => [
                                'route'   => 'zfcadmin/challenge/type/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Challenge\Type::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\ChallengeTypeLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'edit' => [
                                        'label'   => _('txt-nav-edit'),
                                        'route'   => 'zfcadmin/challenge/type/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Challenge\Type::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'new'  => [
                                'label' => _('txt-nav-new-challenge-type'),
                                'route' => 'zfcadmin/challenge/type/new',
                            ],
                        ],
                    ],
                    'content-type' => [
                        'label' => _("txt-content-type-list"),
                        'route' => 'zfcadmin/content-type/list',
                        'pages' => [
                            'content-type-view' => [
                                'route'   => 'zfcadmin/content-type/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\ContentType::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\ContentTypeLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'content-type-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/content-type/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\ContentType::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'content-type-new'  => [
                                'label'   => _("txt-new-content-type"),
                                'route'   => 'zfcadmin/content-type/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'gender'       => [
                        'label' => _("txt-nav-gender-list"),
                        'route' => 'zfcadmin/gender/list',
                        'pages' => [
                            'gender-view' => [
                                'route'   => 'zfcadmin/gender/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Gender::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\GenderLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'gender-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/gender/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Gender::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'gender-new'  => [
                                'label'   => _("txt-new-gender"),
                                'route'   => 'zfcadmin/gender/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'title'        => [
                        'label' => _("txt-nav-title-list"),
                        'route' => 'zfcadmin/title/list',
                        'pages' => [
                            'title-view' => [
                                'route'   => 'zfcadmin/title/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Title::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\TitleLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'title-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/title/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Title::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'title-new'  => [
                                'label'   => _("txt-new-title"),
                                'route'   => 'zfcadmin/title/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                ],
            ],
            'tools'      => [
                'pages' => [
                    'email' => [
                        'label' => _("txt-nav-email-list"),
                        'route' => 'zfcadmin/email/list',
                        'pages' => [
                            'email-view' => [
                                'route'   => 'zfcadmin/email/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\EmailMessage::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\EmailMessageLabel::class,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'log' => [
                        'label' => _("txt-nav-log-list"),
                        'route' => 'zfcadmin/log/list',
                        'pages' => [
                            'log-view' => [
                                'route'   => 'zfcadmin/log/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Log::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\LogLabel::class,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'invoice'    => [
                'order' => 70,
                'pages' => [
                    'vat'      => [
                        'label' => _("txt-nav-vat-list"),
                        'order' => 70,
                        'route' => 'zfcadmin/vat/list',
                        'pages' => [
                            'vat-view' => [
                                'route'   => 'zfcadmin/vat/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\Vat::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\VatLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'vat-edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/vat/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\Vat::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'vat-new'  => [
                                'label'   => _("txt-new-vat"),
                                'route'   => 'zfcadmin/vat/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'vat-type' => [
                        'label' => _("txt-vat-type-list"),
                        'route' => 'zfcadmin/vat-type/list',
                        'order' => 80,
                        'pages' => [
                            'vat-type-view' => [
                                'route'   => 'zfcadmin/vat-type/view',
                                'visible' => false,
                                'params'  => [
                                    'entities'   => [
                                        'id' => General\Entity\VatType::class,
                                    ],
                                    'invokables' => [
                                        General\Navigation\Invokable\VatTypeLabel::class,
                                    ],
                                ],
                                'pages'   => [
                                    'vat-type--edit' => [
                                        'label'   => _("txt-nav-edit"),
                                        'route'   => 'zfcadmin/vat-type/edit',
                                        'visible' => false,
                                        'params'  => [
                                            'entities' => [
                                                'id' => General\Entity\VatType::class,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'vat-type-new'  => [
                                'label'   => _("txt-new-vat-type"),
                                'route'   => 'zfcadmin/vat-type/new',
                                'visible' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];