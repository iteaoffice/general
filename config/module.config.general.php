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
    'image_not_found' => 'image_not_found.jpg'

);

/**
 * You do not need to edit below this line
 */
return array(
    'general' => $settings,
);
