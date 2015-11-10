<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */
use General\Entity;
use General\Form;
use General\Options;
use General\Service\EmailService;
use Zend\ServiceManager\ServiceManager;

return [
    'factories' => [
        'general_web_info_form'     => function ($sm) {
            return new Form\CreateObject($sm, new Entity\WebInfo());
        },
        'general_country_form'      => function ($sm) {
            return new Form\CreateObject($sm, new Entity\Country());
        },
        'general_content_type_form' => function ($sm) {
            return new Form\CreateObject($sm, new Entity\ContentType());
        },
        'general_module_options'    => function (ServiceManager $sm) {
            $config = $sm->get('Config');

            return new Options\ModuleOptions(isset($config['general'])
                ? $config['general'] : []);
        },
        EmailService::class         => function (ServiceManager $sm) {
            $config = $sm->get('Config');

            return new EmailService($config["email"], $sm);
        }
    ],
];
