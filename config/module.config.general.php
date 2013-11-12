<?php
/**
 * ZfcUser Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    'style_locations' => array(
        __DIR__ . '/../../../../styles/common',
        __DIR__ . '/../../../../styles/' . DEBRANOVA_HOST
    ),
    'image_not_found' => 'image_not_found.jpg',


);

/**
 * You do not need to edit below this line
 */
return array(
    'email'   => array(
        "active"              => true,
        "defaults"            => array(
            "html_layout_name" => "template",
            "text_layout_name" => "template_text",
            "from_email"       => "no-reply@debranova.org",
            "from_name"        => "DebraNova Project",
            "reply_to"         => "info@debranova.org",
            "reply_to_name"    => "Reply to"
        ),
        "emails"              => array(
            "support" => "webmaster@debranova.org",
            "admin"   => "webmaster@debranova.org"
        ),
        'template_vars'       => array(
            "company"        => "ITEA 3",
            "slogan"         => "",
            "baseUrl"        => "http://itea2.org",
            "cache_location" => __DIR__ . '/../../../../data/mail/template',
        ),
        'relay'               => array(
            'active'   => true,
            'host'     => 'in.mailjet.com',
            'port'     => '', // it could be empty
            'username' => '8fcfdb11aaeebed89828fdee92ca4d3d',
            'password' => '85938a789be340706a1198ec06c296a2',
            'ssl'      => '' // it could be empty
        ),
        "template_path_stack" => array(
            __DIR__ . "/../view/email/"
        ),
    ),
    'general' => $settings,
);