<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Form;

use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;

/**
 * Class ChallengeTypeFilter
 *
 * @package General\Form
 */
final class ChallengeTypeFilter extends Form
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
