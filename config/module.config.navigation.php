<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */
return [
    'navigation' => [
        'admin' => [
            // And finally, here is where we define our page hierarchy
            'content' => [
                'pages' => [
                    'web-info'     => [
                        'label' => _("txt-web-info-list"),
                        'route' => 'zfcadmin/web-info/list',
                    ],
                    'country'      => [
                        'label' => _("txt-country-list"),
                        'route' => 'zfcadmin/country/list',
                    ],
                    'challenge'    => [
                        'label' => _("txt-challenge-list"),
                        'route' => 'zfcadmin/challenge/list',
                    ],
                    'vat'          => [
                        'label' => _("txt-vat-list"),
                        'route' => 'zfcadmin/vat/list',
                    ],
                    'content-type' => [
                        'label' => _("txt-content-type-list"),
                        'route' => 'zfcadmin/content-type/list',
                    ],
                    'gender'       => [
                        'label' => _("txt-gender-list"),
                        'route' => 'zfcadmin/gender/list',
                    ],
                    'title'        => [
                        'label' => _("txt-title-list"),
                        'route' => 'zfcadmin/title/list',
                    ],
                ],
            ],
        ],
    ],
];


