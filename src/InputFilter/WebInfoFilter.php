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
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\InputFilter;

use Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;

/**
 * ITEA Office all rights reserved
 *
 * @category    Partner
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */
class WebInfoFilter extends InputFilter
{
    /**
     * PartnerFilter constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'     => 'info',
                'required' => true,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'subject',
                'required' => true,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'content',
                'required' => true,
            ]
        );


        $this->add($inputFilter, 'general_entity_webinfo');
    }
}
