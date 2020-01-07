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

namespace General\InputFilter\Challenge;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\UniqueObject;
use General\Entity;
use Laminas\InputFilter\InputFilter;

/**
 * Class TypeFilter
 *
 * @package General\InputFilter\Challenge
 */
class TypeFilter extends InputFilter
{
    /**
     * TypeFilter constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'       => 'type',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                    [
                        'name'    => UniqueObject::class,
                        'options' => [
                            'object_repository' => $entityManager->getRepository(Entity\Challenge\Type::class),
                            'object_manager'    => $entityManager,
                            'use_context'       => true,
                            'fields'            => 'type',
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'description',
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ]
        );
        $this->add($inputFilter, 'general_entity_challenge_type');
    }
}
