<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class CommunityTypeFilter
 * @package General\InputFilter
 */
class CommunityTypeFilter extends InputFilter
{
    /**
     * CommunityTypeFilter constructor.
     */
    public function __construct()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'     => 'type',
                'required' => true,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'regularExpression',
                'required' => true,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'link',
                'required' => true,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'image',
                'required' => false,
            ]
        );


        $this->add($inputFilter, 'general_entity_communitytype');
    }
}
