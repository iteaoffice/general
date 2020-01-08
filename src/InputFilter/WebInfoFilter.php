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
use General\Entity;
use Laminas\InputFilter\InputFilter;

/**
 * Class WebInfoFilter
 *
 * @package General\InputFilterF
 */
final class WebInfoFilter extends InputFilter
{
    public function __construct(EntityManager $entityManager)
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(
            [
                'name'       => 'info',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 150,
                        ],
                    ],
                    [
                        'name'    => UniqueObject::class,
                        'options' => [
                            'object_repository' => $entityManager->getRepository(Entity\WebInfo::class),
                            'object_manager'    => $entityManager,
                            'use_context'       => true,
                            'fields'            => ['info'],
                        ],
                    ],
                ],
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
