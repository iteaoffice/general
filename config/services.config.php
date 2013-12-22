<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
use General\Form;

use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\ServiceManager\ServiceLocatorInterface;

use DoctrineModule\Service\DriverFactory;
use DoctrineModule\Service\EventManagerFactory;

use DoctrineORMModule\Service\DBALConnectionFactory;
use DoctrineORMModule\Service\ConfigurationFactory as ORMConfigurationFactory;
use DoctrineORMModule\Service\EntityManagerFactory;
use DoctrineORMModule\Service\EntityResolverFactory;
use DoctrineORMModule\Service\SQLLoggerCollectorFactory;

use General\Options;

return array(
    'factories' => array(

        'general_module_options' => function ($sm) {
                $config = $sm->get('Config');

                return new Options\ModuleOptions(isset($config['general']) ? $config['general'] : array());
            },

        'email'                  => function ($sm) {
                $config = $sm->get('Config');

                return new General\Service\EmailService($config["email"], $sm);
            }


    ),
);
