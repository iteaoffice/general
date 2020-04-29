<?php

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
