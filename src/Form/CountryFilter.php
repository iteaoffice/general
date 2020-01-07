<?php

/**
 * Jield copyright message placeholder.
 *
 * @category    Contact
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Form;

use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;

/**
 * Class CountryFilter
 *
 * @package General\Form
 */
final class CountryFilter extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'get');
        $this->setAttribute('action', '');

        $filterFieldset = new Fieldset('filter');

        $filterFieldset->add(
            [
                'type'       => Text::class,
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
                'type'       => Radio::class,
                'name'       => 'eu',
                'options'    => [
                    'value_options' => $yesNo,
                    'inline'        => true,
                ],
                'attributes' => [
                    'label' => _('txt-eu'),
                ],
            ]
        );

        $filterFieldset->add(
            [
                'type'       => Radio::class,
                'name'       => 'eureka',
                'options'    => [
                    'value_options' => $yesNo,
                    'inline'        => true,
                ],
                'attributes' => [
                    'label' => _('txt-eureka'),
                ],
            ]
        );

        $filterFieldset->add(
            [
                'type'       => Radio::class,
                'name'       => 'itac',
                'options'    => [
                    'value_options' => $yesNo,
                    'inline'        => true,
                ],
                'attributes' => [
                    'label' => _('txt-itac-form-label'),
                ],
            ]
        );


        $this->add($filterFieldset);

        $this->add(
            [
                'type'       => Submit::class,
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
                'type'       => Submit::class,
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
