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
use General\Service\EmailService;
use Zend\ServiceManager\ServiceManager;

return [
    'factories' => [
        'general_module_options' => function (ServiceManager $sm) {
            $config = $sm->get('Config');

            return new Options\ModuleOptions(isset($config['general']) ? $config['general'] : []);
        },
        EmailService::class      => function (ServiceManager $sm) {
            $config = $sm->get('Config');

            return new EmailService($config["email"], $sm);
        }
    ],
];
