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

namespace News;

use Admin\Entity\Access;

return [
    'bjyauthorize' => [
        /* Currently, only controller and route guards exist
         */
        'guards' => [
            /* If this guard is specified here (i.e. it is enabled], it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => [
                ['route' => 'image/country-flag', 'roles' => []],
                ['route' => 'image/challenge-icon', 'roles' => []],
                ['route' => 'image/challenge-image', 'roles' => []],
                ['route' => 'image/asset', 'roles' => []],

                ['route' => 'assets/style-image', 'roles' => []],

                ['route' => 'country/code', 'roles' => []],
                ['route' => 'impact-stream/download', 'roles' => []],
                ['route' => 'impact-stream/download-selected', 'roles' => []],
                ['route' => 'impact-stream/download-single', 'roles' => []],

                ['route' => 'email/event', 'roles' => []],
                ['route' => 'zfcadmin/email/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/email/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/log/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/log/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'home', 'roles' => []],
                ['route' => 'zfcadmin/web-info/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/web-info/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/web-info/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/web-info/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/content-type/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/content-type/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/content-type/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/content-type/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/country/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/country/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/country/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/country/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/currency/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/currency/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/currency/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/currency/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/currency/exchange-rate/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/currency/exchange-rate/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/password/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/password/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/password/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/password/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/download-pdf', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/type/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/type/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/type/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/challenge/type/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat-type/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat-type/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat-type/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/vat-type/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/gender/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/gender/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/gender/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/gender/view', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/title/list', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/title/new', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/title/edit', 'roles' => [Access::ACCESS_OFFICE],],
                ['route' => 'zfcadmin/title/view', 'roles' => [Access::ACCESS_OFFICE],],
            ],
        ],
    ],
];
