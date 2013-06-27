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


        'doctrine.connection.orm_crawler' => new DBALConnectionFactory('orm_crawler'),
        'doctrine.configuration.orm_crawler' => new ORMConfigurationFactory('orm_crawler'),
        'doctrine.entitymanager.orm_crawler' => new EntityManagerFactory('orm_crawler'),

        'doctrine.driver.orm_crawler' => new DriverFactory('orm_crawler'),
        'doctrine.eventmanager.orm_crawler' => new EventManagerFactory('orm_crawler'),
        'doctrine.entity_resolver.orm_crawler' => new EntityResolverFactory('orm_crawler'),
        'doctrine.sql_logger_collector.orm_crawler' => new SQLLoggerCollectorFactory('orm_crawler'),

        'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => function (ServiceLocatorInterface $sl) {
            return new AnnotationBuilder($sl->get('doctrine.entitymanager.orm_crawler'));
        },
    ),
);
