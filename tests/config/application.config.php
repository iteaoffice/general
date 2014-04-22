<?php
return array(
    'modules'                 => array(
        'General',
        'DoctrineModule',
        'DoctrineORMModule',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            __DIR__ . '/autoload/{,*.}{global,testing,local}.php',
        ),
        'module_paths'      => array(
            './../module',
            './vendor',
        ),
    ),
    'service_manager'         => array(
        'use_defaults' => true,
        'factories'    => array(),
    ),
);
