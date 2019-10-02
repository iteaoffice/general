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

namespace General\Form;

use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Form\Element\EntityMultiCheckbox;
use General\Entity;
use Program\Entity\Call\Call;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\Form\Form;

/**
 * Class ChallengeFilter
 *
 * @package General\Form
 */
final class ChallengeFilter extends Form
{
    public function __construct(EntityManager $entityManager)
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

        $filterFieldset->add(
            [
                'type'    => EntityMultiCheckbox::class,
                'name'    => 'type',
                'options' => [
                    'target_class'   => Entity\Challenge\Type::class,
                    'inline'         => true,
                    'object_manager' => $entityManager,
                    'label'          => _('txt-challenge-type'),
                    'allow_empty'    => true,
                    'find_method'    => [
                        'name'   => 'findAll',
                        'params' => [
                            'criteria' => [],
                            'orderBy'  => ['type' => 'ASC'],
                        ],
                    ],
                ],
            ]
        );

        $filterFieldset->add(
            [
                'type'    => EntityMultiCheckbox::class,
                'name'    => 'call',
                'options' => [
                    'target_class'   => Call::class,
                    'inline'         => true,
                    'object_manager' => $entityManager,
                    'label'          => _('txt-program-call'),
                    'allow_empty'    => true,
                    'find_method'    => [
                        'name'   => 'findAll',
                        'params' => [
                            'criteria' => [],
                            'orderBy'  => ['country' => 'ASC'],
                        ],
                    ],
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
