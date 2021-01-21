<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\InputFilter;

use Laminas\Filter\ToFloat;
use Laminas\InputFilter\InputFilter;

/**
 * Class ExchangeRateFilter
 * @package General\InputFilter
 */
final class ExchangeRateFilter extends InputFilter
{
    public function __construct()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'     => 'website',
                'required' => false,
                'filters'  => [
                    ['name' => ToFloat::class],
                ],
            ]
        );
        $this->add($inputFilter, 'general_entity_exchangerate');
    }
}
