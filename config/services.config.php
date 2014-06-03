<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

use General\Options;

return array(
    'initializers' => array(
        'general_service_initializer' => 'General\Service\ServiceInitializer'
    ),
    'factories'    => array(

        'general_module_options' => function ($sm) {
            $config = $sm->get('Config');

            return new Options\ModuleOptions(isset($config['general']) ? $config['general'] : array());
        },
        'general_email_service'  => function ($sm) {
            $config = $sm->get('Config');

            return new General\Service\EmailService($config["email"], $sm);
        }

    ),
);
