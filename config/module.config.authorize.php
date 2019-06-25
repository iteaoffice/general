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

use BjyAuthorize\Guard\Route;

return [
    'bjyauthorize' => [
        /* Currently, only controller and route guards exist
         */
        'guards' => [
            /* If this guard is specified here (i.e. it is enabled], it will block
             * access to all routes unless they are specified here.
             */
            Route::class => [
                ['route' => 'image/country-flag', 'roles' => []],
                ['route' => 'image/challenge-icon', 'roles' => []],
                ['route' => 'image/challenge-image', 'roles' => []],

                ['route' => 'country/code', 'roles' => []],
                ['route' => 'impact-stream/download', 'roles' => []],
                ['route' => 'impact-stream/download-selected', 'roles' => []],
                ['route' => 'impact-stream/download-single', 'roles' => []],

                ['route' => 'email/event', 'roles' => []],
                ['route' => 'zfcadmin/email/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/email/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/log/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/log/view', 'roles' => ['office'],],
                ['route' => 'home', 'roles' => []],
                ['route' => 'zfcadmin/web-info/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/web-info/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/web-info/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/web-info/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/content-type/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/content-type/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/content-type/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/content-type/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/country/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/country/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/country/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/country/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/currency/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/currency/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/currency/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/currency/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/currency/exchange-rate/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/currency/exchange-rate/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/password/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/password/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/password/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/password/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/download-pdf', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/type/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/type/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/type/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/challenge/type/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat-type/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat-type/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat-type/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/vat-type/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/gender/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/gender/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/gender/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/gender/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/title/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/title/new', 'roles' => ['office'],],
                ['route' => 'zfcadmin/title/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/title/view', 'roles' => ['office'],],
            ],
        ],
    ],
];
