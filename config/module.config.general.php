<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

/**
 * You do not need to edit below this line
 */

return [
    'general_option' => [
        'serverUrl'                      => 'https://itea3.org',
        'thumborServer'                  => 'https://image.itea3.org',
        'thumborSecret'                  => 'secret',
        'assets'                         => __DIR__ . '/../../../../styles/' . (defined('ITEAOFFICE_HOST') ? ITEAOFFICE_HOST : 'test') . '/img',
        'community_navigation_container' => 'Laminas\Navigation\Community2'
    ]
];
