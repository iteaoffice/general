<?php
namespace General;

use Zend\Stdlib\ArrayUtils;

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
$config = array(
    'controllers'     => array(
        'invokables' => array(
            'general-index' => 'General\Controller\IndexController',
            'general-style' => 'General\Controller\StyleController',
        ),
    ),
    'view_helpers'    => array(
        'invokables' => array(
            'country-map' => 'General\View\Helper\CountryMap'
        )
    ),
    'service_manager' => array(
        'factories'  => array(
            'general-assertion' => 'General\Acl\Assertion\General',
        ),
        'invokables' => array(
            'general_generic_service' => 'General\Service\GeneralService',
            'general_form_service'    => 'General\Service\FormService',

        )
    ),
    'asset_manager'   => array(
        'resolver_configs' => array(
            'collections' => array(
                'assets/js/jvectormap.js'  => array(
                    'js/jquery/jquery-jvectormap-1.1.1.min.js',
                    'js/jquery/jquery-jvectormap-europe-mill-en.js',
                    'js/jquery/jquery.mousewheel.min.js'
                ),
                'assets/css/bootstrap.css' => array(
                    'css/bootstrap-less.css'
                ),
            ),
            'paths'       => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'doctrine'        => array(
        'driver'       => array(
            'general_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../src/General/Entity/'
                )
            ),
            'orm_default'               => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => 'general_annotation_driver',
                )
            )
        ),
        'eventmanager' => array(
            'orm_general' => array(
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
    $config = ArrayUtils::merge($config, include $configFile);
}

return $config;
