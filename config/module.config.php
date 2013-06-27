<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
$config = array(
    'controllers' => array(
        'invokables' => array(
            'general-index' => 'General\Controller\IndexController',
            'general-style' => 'General\Controller\StyleController',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array()
    ),
    'service_manager' => array(
        'factories' => array(
            'general-assertion' => 'General\Acl\Assertion\General',
        ),
        'invokables' => array(
            'general_generic_service' => 'General\Service\GeneralService',
            'general_form_service' => 'General\Service\FormService',

        )
    ),
    'doctrine' => array(
        'driver' => array(
            'general_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../src/General/Entity/'
                )
            ),
            'orm_default' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'General\Entity' => 'general_annotation_driver',
                )
            )
        ),
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\Sluggable\SluggableListener',
                )
            ),
        ),
    )
);

$configFiles = array(
    __DIR__ . '/module.config.routes.php',
    __DIR__ . '/module.config.general.php',
    __DIR__ . '/module.config.navigation.php',
    __DIR__ . '/module.config.authorize.php',
);

foreach ($configFiles as $configFile) {
    $config = Zend\Stdlib\ArrayUtils::merge($config, include $configFile);
}

return $config;
