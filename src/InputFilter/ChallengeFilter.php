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

use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\UniqueObject;
use General\Entity\Challenge;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\Extension;

/**
 * Class ChallengeFilter
 *
 * @package General\InputFilter
 */
class ChallengeFilter extends InputFilter
{
    /**
     * ChallengeFilter constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'       => 'challenge',
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
                            'object_repository' => $entityManager->getRepository(Challenge::class),
                            'object_manager'    => $entityManager,
                            'use_context'       => true,
                            'fields'            => 'challenge',
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'type',
                'required' => false,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'backgroundImage',
                'required' => false,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'description',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'sources',
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'       => 'backgroundColor',
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
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'       => 'frontColor',
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
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'     => 'icon',
                'required' => true,
            ]
        );
        $inputFilter->add(
            [
                'name'     => 'call',
                'required' => false,
            ]
        );
        $inputFilter->add(
            [
                'name'       => 'pdf',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => Extension::class,
                        'options' => [
                            'extension' => ['pdf'],
                        ],
                    ],
                ],
            ]
        );

        $this->add($inputFilter, 'general_entity_challenge');
    }
}
