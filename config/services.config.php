<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
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


        'doctrine.connection.orm_general' => new DBALConnectionFactory('orm_general'),
        'doctrine.configuration.orm_general' => new ORMConfigurationFactory('orm_general'),
        'doctrine.entitymanager.orm_general' => new EntityManagerFactory('orm_general'),

        'doctrine.driver.orm_general' => new DriverFactory('orm_general'),
        'doctrine.eventmanager.orm_general' => new EventManagerFactory('orm_general'),
        'doctrine.entity_resolver.orm_general' => new EntityResolverFactory('orm_general'),
        'doctrine.sql_logger_collector.orm_general' => new SQLLoggerCollectorFactory('orm_general'),

        'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => function (ServiceLocatorInterface $sl) {
            return new AnnotationBuilder($sl->get('doctrine.entitymanager.orm_general'));
        },
    ),
);
