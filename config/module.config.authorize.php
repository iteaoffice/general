<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c] 2004-2014 ITEA Office (http://itea3.org]
 */
namespace News;

return [
    'bjyauthorize' => [
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'general' => [],
            ],
        ],
        /* rules can be specified here with the format:
         * [roles (array] , resource, [privilege (array|string], assertion]]
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers'     => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    // allow guests and users (and admins, through inheritance]
                    // the "wear" privilege on the resource "pants"d
                    [[], 'general', []],
                ],
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny'  => [ // ...
                ],
            ],
        ],
        /* Currently, only controller and route guards exist
         */
        'guards'             => [
            /* If this guard is specified here (i.e. it is enabled], it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => [
                ['route' => 'content-type/icon', 'roles' => []],
                ['route' => 'country/flag', 'roles' => []],
                ['route' => 'country/code', 'roles' => []],
                ['route' => 'assets/style-image', 'roles' => []],
                ['route' => 'assets/country-flag', 'roles' => []],
                ['route' => 'assets/content-type-icon', 'roles' => []],
                ['route' => 'home', 'roles' => []]
            ],
        ],
    ],
];
