<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
return array(
    'navigation' => array(
        'default' => array(
            'general' => array(
                'label' => _("txt-general"),
                'route' => 'general',
                'pages' => array(),
            ),
            'admin' => array(
                'pages' => array(
                    'messages' => array(
                        'label' => _('txt-messages'),
                        'route' => 'zfcadmin/general-manager/messages',
                    ),
                ),
            ),
        ),
    ),
);
