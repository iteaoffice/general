<?php
return array(
    'modules' => array(
        'ZfcAdmin',
        'DoctrineModule',
        'DoctrineORMModule',
        'General',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
        ),
        'module_paths' => array(
            './src',
            './vendor',
        ),
    ),
    'service_manager' => array(
        'use_defaults' => true,
        'factories' => array(),
    ),
);
