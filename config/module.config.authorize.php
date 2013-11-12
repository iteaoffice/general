<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
namespace News;

use General\Entity\Country;
use General\Entity\Challenge;

$country   = new Country();
$challenge = new Challenge();

return array(
    'bjyauthorize' => array(
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'general' => array(),
            ),
        ),
        /* rules can be specified here with the format:
         * array(roles (array) , resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers'     => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"d
                    array(array(), 'general', array()),
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny'  => array( // ...
                ),
            ),
        ),
        /* Currently, only controller and route guards exist
         */
        'guards'             => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'route-' . $country->get('underscore_full_entity_name'), 'roles' => array()),
                array('route' => 'route-' . $country->get('underscore_full_entity_name') . '-project', 'roles' => array()),
                array('route' => 'route-' . $country->get('underscore_full_entity_name') . '-organisation', 'roles' => array()),
                array('route' => 'route-' . $challenge->get('underscore_full_entity_name'), 'roles' => array()),
                array('route' => 'content-type/icon', 'roles' => array()),
                array('route' => 'country/flag', 'roles' => array()),
                array('route' => 'style', 'roles' => array()),
                array('route' => 'home', 'roles' => array())

            ),
        ),
    ),
);
