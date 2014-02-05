<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General;

use General\View\Helper;

return array(
    'factories'  => array(
        'challengeHandler'    => function ($sm) {
                return new Helper\ChallengeHandler($sm);
            },
        'countryHandler'      => function ($sm) {
                return new Helper\CountryHandler($sm);
            },
        'generalServiceProxy' => function ($sm) {
                return new Helper\GeneralServiceProxy($sm);
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
