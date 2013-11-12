<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */

namespace General;

use General\View\Helper;

return array(
    'factories'  => array(
        'challengeHandler' => function ($sm) {
                return new Helper\ChallengeHandler($sm);
            },
        'countryHandler'   => function ($sm) {
                return new Helper\CountryHandler($sm);
            },
    ),
    'invokables' => array(
        'countryMap'      => 'General\View\Helper\CountryMap',
        'countryFlag'     => 'General\View\Helper\CountryFlag',
        'countryLink'     => 'General\View\Helper\CountryLink',
        'challengeLink'   => 'General\View\Helper\ChallengeLink',
        'contentTypeIcon' => 'General\View\Helper\ContentTypeIcon',
    )
);
