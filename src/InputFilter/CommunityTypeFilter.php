<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\InputFilter;

use Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;

/**
 * Jield webdev copyright message placeholder.
 *
 * @category    Partner
 *
 * @author      Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2015 Jield (http://jield.nl)
 */
class CommunityTypeFilter extends InputFilter
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