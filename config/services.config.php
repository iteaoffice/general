<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Config
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */
use General\Entity;
use General\Form;
use General\Options;

return [
    'factories' => [
        'general_web_info_form'     => function ($sm) {
            return new Form\CreateObject($sm, new Entity\WebInfo());
        },
        'general_country_form'      => function ($sm) {
            return new Form\CreateObject($sm, new Entity\Country());
        },
        'general_gender_form'       => function ($sm) {
            return new Form\CreateObject($sm, new Entity\Gender());
        },
        'general_title_form'        => function ($sm) {
            return new Form\CreateObject($sm, new Entity\Title());
        },
        'general_vat_form'          => function ($sm) {
            return new Form\CreateObject($sm, new Entity\Vat());
        },
        'general_vat_type_form'     => function ($sm) {
            return new Form\CreateObject($sm, new Entity\VatType());
        },
        'general_challenge_form'    => function ($sm) {
            return new Form\CreateObject($sm, new Entity\Challenge());
        },
        'general_content_type_form' => function ($sm) {
            return new Form\CreateObject($sm, new Entity\ContentType());
        },


    ],
];
