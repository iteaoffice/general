<?php

/**
 * Jield copyright message placeholder.
 *
 * @category    Contact
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Form;

use Zend\Form\Fieldset;
use Zend\Form\Form;

/**
 * Class CountryFilter
 * @package General\Form
 */
class CountryFilter extends Form
{
    /**
     * CountryFilter constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'get');
        $this->setAttribute('action', '');

        $filterFieldset = new Fieldset('filter');

        $filterFieldset->add(
            [
                'type'       => 'Zend\Form\Element\Text',
                'name'       => 'search',
                'attributes' => [
                    'class'       => 'form-control',
                    'placeholder' => _('txt-search'),
                ],
            ]
        );

        $yesNo = [1 => 'YES', 0 => 'NO'];

        $filterFieldset->add(
            [
                'type'       => 'Zend\Form\Element\Radio',
                'name'       => 'eu',
                'options'    => [
                    'value_options' => $yesNo,
                    'inline'        => true,
                ],
                'attributes' => [
                    'label' => _("txt-eu"),
                ],
            ]
        );

        $filterFieldset->add(
            [
                'type'       => 'Zend\Form\Element\Radio',
                'name'       => 'eureka',
                'options'    => [
                    'value_options' => $yesNo,
                    'inline'        => true,
                ],
                'attributes' => [
                    'label' => _("txt-eureka"),
                ],
            ]
        );

        $filterFieldset->add(
            [
                'type'       => 'Zend\Form\Element\Radio',
                'name'       => 'itac',
                'options'    => [
                    'value_options' => $yesNo,
                    'inline'        => true,
                ],
                'attributes' => [
                    'label' => _("txt-itac-form-label"),
                ],
            ]
        );


        $this->add($filterFieldset);

        $this->add(
            [
                'type'       => 'Zend\Form\Element\Submit',
                'name'       => 'submit',
                'attributes' => [
                    'id'    => 'submit',
                    'class' => 'btn btn-primary',
                    'value' => _('txt-filter'),
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Zend\Form\Element\Submit',
                'name'       => 'clear',
                'attributes' => [
                    'id'    => 'cancel',
                    'class' => 'btn btn-warning',
                    'value' => _('txt-cancel'),
                ],
            ]
        );
    }
}
