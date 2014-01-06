<?php
namespace General;

use Zend\Stdlib\ArrayUtils;

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
$config = array(
    'controllers'     => array(
        'invokables' => array(
            'general-index' => 'General\Controller\IndexController',
            'general-style' => 'General\Controller\StyleController',
        ),
    ),
    'view_manager'    => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
            __DIR__ . '/../../../../data/mail/template'
        ),
        'template_map'        => include __DIR__ . '/../template_map.php',
    ),
    'service_manager' => array(
        'factories'  => array(
            'general-assertion' => 'General\Acl\Assertion\General',
        ),
        'invokables' => array(
            'general_general_service' => 'General\Service\GeneralService',
            'general_form_service'    => 'General\Service\FormService',

        )
    ),
    'asset_manager'   => array(
        //@todo refactor to keep it here
        'resolver_configs' => array(
            'collections' => array(
                'assets/js/jvectormap.js' => array(
                    'js/jquery/jquery-jvectormap-1.1.1.min.js',
                    'js/jquery/jquery-jvectormap-europe-mill-en.js',
                    'js/jquery/jquery.mousewheel.min.js'
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
