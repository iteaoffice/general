<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
return [
    'navigation' => [
        'admin' => [
            // And finally, here is where we define our page hierarchy
            'content' => [
                'pages' => [
                    'web-info' => [
                        'label' => _("txt-web-info-list"),
                        'route' => 'zfcadmin/web-info/list',
                    ],
                    'country'  => [
                        'label' => _("txt-country-list"),
                        'route' => 'zfcadmin/country/list',
                    ]
                ],
            ],
        ],
    ],
];


