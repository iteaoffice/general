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

namespace General\Form;

use Doctrine\ORM\EntityManager;
use General\Entity\EmailMessage;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Fieldset;
use Zend\Form\Form;

/**
 * Class EmailFilter
 *
 * @package General\Form
 */
class EmailFilter extends Form
{
    /**
     * EmailFilter constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct();
        $this->setAttribute('method', 'get');
        $this->setAttribute('action', '');

        $filterFieldset = new Fieldset('filter');

        $filterFieldset->add(
            [
                'type'       => 'Zend\Form\Element\Text',
                'name'       => 'search',
                'attributes' => [
                    'class'       => 'form-control',
                    'placeholder' => _('txt-search'),
                ],
            ]
        );

        $latestEvents = [];

        /** @var \General\Repository\EmailMessage $repository */
        $repository = $entityManager->getRepository(EmailMessage::class);

        foreach ($repository->findPossibleLatestEvents() as $event) {
            $latestEvents[$event['latestEvent']] = $event['latestEvent'];
        }

        $filterFieldset->add(
            [
                'type'    => MultiCheckbox::class,
                'name'    => 'latestEvent',
                'options' => [
                    'value_options' => $latestEvents,
                    'inline'        => true,
                    'label'         => _("txt-latest-event"),
                ],
            ]
        );

        $this->add($filterFieldset);

        $this->add(
            [
                'type'       => 'Zend\Form\Element\Submit',
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
                'type'       => 'Zend\Form\Element\Submit',
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
