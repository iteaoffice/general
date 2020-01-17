<?php

/**
 * You do not need to edit below this line
 */

return [
    'email' => [
        'defaults' => [
            'from_email' => 'noreply@itea3.org',
            'from_name' => 'ITEA Office',
            'reply_to' => 'info@itea3.org',
            'reply_to_name' => 'Reply to',
        ],
        'emails' => [
            'support' => 'info@itea3.org',
            'admin' => 'info@japaveh.nl',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view/email/',
        ],
    ],
    'general_option' => [
        'serverUrl' => 'https://itea3.org',
        'thumborServer' => 'https://image.itea3.org',
        'thumborSecret' => 'secret',
        'assets' => __DIR__ . '/../../../../styles/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test') . '/img'
    ]
];
