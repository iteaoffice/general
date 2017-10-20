<?php
/**
 * You do not need to edit below this line
 */
return [
    'email'   => [
        "active"              => true,
        "defaults"            => [
            "html_layout_name" => "signature_twig",
            "text_layout_name" => "template_text",
            "from_email"       => "noreply@itea3.org",
            "from_name"        => "ITEA Office [dev]",
            "reply_to"         => "info@itea3.org",
            "reply_to_name"    => "Reply to",
        ],
        "emails"              => [
            "support" => "info@itea3.org",
            "admin"   => "info@japaveh.nl",
        ],
        'template_vars'       => [
            "company" => "Development ITEA office",
            "site"    => "Development ITEA office",
            "slogan"  => "",
            "baseUrl" => "https://itea3.org",
        ],
        "template_path_stack" => [
            __DIR__ . "/../view/email/",
        ],
    ],
    'general' => [
        'style_locations' => [
            __DIR__ . '/../../../../styles/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST : 'test'),
        ],
    ],
];
