<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c] 2004-2014 ITEA Office (http://itea3.org]
 */
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
                ['route' => 'content-type/icon', 'roles' => []],
                ['route' => 'country/flag', 'roles' => []],
                ['route' => 'country/code', 'roles' => []],
                ['route' => 'assets/style-image', 'roles' => []],
                ['route' => 'assets/country-flag', 'roles' => []],
                ['route' => 'assets/content-type-icon', 'roles' => []],
                ['route' => 'home', 'roles' => []],
                ['route' => 'zfcadmin/web-info/list', 'roles' => [strtolower(Access::ACCESS_OFFICE)]],
                ['route' => 'zfcadmin/web-info/new', 'roles' => [strtolower(Access::ACCESS_OFFICE)]],
                ['route' => 'zfcadmin/web-info/edit', 'roles' => [strtolower(Access::ACCESS_OFFICE)]],
                ['route' => 'zfcadmin/web-info/view', 'roles' => [strtolower(Access::ACCESS_OFFICE)]],
            ],
        ],
    ],
];
