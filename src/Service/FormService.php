<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2018 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */
declare(strict_types=1);

namespace General\Service;

use General\Entity\AbstractEntity;
use General\Form\CreateObject;
use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FormService
 *
 * @package Application\Service
 */
class FormService
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(ServiceLocatorInterface $serviceLocator, EntityManager $entityManager)
    {
        $this->serviceLocator = $serviceLocator;
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
        if (!$classNameOrEntity instanceof AbstractEntity) {
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
        if ($this->serviceLocator->has($formName)) {
            $form = $this->serviceLocator->build($formName, $options);
        } else {
            $form = new CreateObject($this->entityManager, $entity, $this->serviceLocator);
        }

        if ($this->serviceLocator->has($filterName)) {
            /** @var InputFilter $filter */
            $filter = $this->serviceLocator->get($filterName);
            $form->setInputFilter($filter);
        }

        $form->setAttribute('role', 'form');
        $form->setAttribute('action', '');
        $form->setAttribute('class', 'form-horizontal');

        $form->bind($entity);

        return $form;
    }
}
