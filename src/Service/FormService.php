<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Service;

use Doctrine\ORM\EntityManager;
use General\Entity\AbstractEntity;
use General\Form\CreateObject;
use Interop\Container\ContainerInterface;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Class FormService
 *
 * @package General\Service
 */
class FormService
{
    private ContainerInterface $container;
    private EntityManager $entityManager;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function prepare($classNameOrEntity, array $data = [], array $options = []): Form
    {
        /**
         * The form can be created from an empty element, we then expect the $formClassName to be filled
         * This should be a string, indicating the class
         *
         * But if the class a class is injected, we will change it into the className but hint the user to use a string
         */
        if (! $classNameOrEntity instanceof AbstractEntity) {
            $classNameOrEntity = new $classNameOrEntity();
        }

        $form = $this->getForm($classNameOrEntity, $options);
        $form->setData($data);

        return $form;
    }

    private function getForm(AbstractEntity $entity, array $options = []): Form
    {
        $formName = $entity->get('entity_form_name');
        $filterName = $entity->get('entity_inputfilter_name');

        /**
         * The filter and the form can dynamically be created by pulling the form from the serviceManager
         * if the form or filter is not give in the serviceManager we will create it by default
         */
        if ($this->container->has($formName)) {
            $form = $this->container->build($formName, $options);
        } else {
            $form = new CreateObject($this->entityManager, $entity, $this->container);
        }

        if ($this->container->has($filterName)) {
            /** @var InputFilter $filter */
            $filter = $this->container->get($filterName);
            $form->setInputFilter($filter);
        }

        $form->setAttribute('role', 'form');
        $form->setAttribute('action', '');
        $form->setAttribute('class', 'form-horizontal');

        $form->bind($entity);

        return $form;
    }
}
