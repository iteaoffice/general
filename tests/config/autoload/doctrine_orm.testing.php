<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'phpci',
                    'password' => 'AB4VF',
                    'dbname' => 'test',
                    'driverOptions' => array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                    )
                )
            )
        ), // now you define the entity manager configuration
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default'
            ),
        ),
        //Add the dependency to the Contact namespace here for testing purposes
        'driver' => array(
            'orm_default' => array(
                'drivers' => array(
                    'Contact\Entity' => 'general_annotation_driver'
                )
            )
        )
    ),
);