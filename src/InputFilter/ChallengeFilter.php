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

use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\UniqueObject;
use General\Entity\Challenge;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\File\Extension;
use Laminas\Validator\File\IsImage;

/**
 * Class ChallengeFilter
 *
 * @package General\InputFilter
 */
final class ChallengeFilter extends InputFilter
{
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
                'name'       => 'image',
                'required'   => false,
                'validators' => [
                    [
                        'name' => IsImage::class
                    ],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'       => 'icon',
                'required'   => false,
                'validators' => [
                    [
                        'name' => IsImage::class
                    ],
                    [
                        'name'    => Extension::class,
                        'options' => [
                            'extension' => ['svg'],
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'       => 'ideaPosterimage',
                'required'   => false,
                'validators' => [
                    [
                        'name' => IsImage::class
                    ],
                ],
            ]
        );
        $inputFilter->add(
            [
                'name'       => 'ideaPosterIcon',
                'required'   => false,
                'validators' => [
                    [
                        'name' => IsImage::class
                    ],
                ],
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
                'required'   => false,
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
