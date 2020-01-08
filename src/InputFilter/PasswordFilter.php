<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\InputFilter;

use Laminas\InputFilter\InputFilter;

/**
 * Class ChallengeFilter
 *
 * @package General\InputFilter
 */
final class PasswordFilter extends InputFilter
{
    public function __construct()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'     => 'website',
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ]
        );
        $this->add($inputFilter, 'general_entity_password');
    }
}
