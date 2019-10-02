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
use General\Entity\EmailMessage;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\Form\Form;

/**
 * Class EmailFilter
 *
 * @package General\Form
 */
final class EmailFilter extends Form
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
                    'label'         => _('txt-latest-event'),
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
