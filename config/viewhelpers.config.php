<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Application
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */

return array(
    'factories'  => array(
        'challengeHandler' => function ($sm) {
                return new \General\View\Helper\ChallengeHandler($sm);
            },
    ),
    'invokables' => array(
        'countryMap'    => 'General\View\Helper\CountryMap',
        'countryLink'   => 'General\View\Helper\CountryLink',
        'challengeLink' => 'General\View\Helper\ChallengeLink',
    )
);
