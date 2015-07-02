<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category    Content
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Element\EntityMultiCheckbox;
use DoctrineORMModule\Form\Element\EntitySelect;
use General\Entity;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Radio;
use Zend\Form\Fieldset;

/**
 * Class ObjectFieldset.
 */
class ObjectFieldset extends Fieldset
{
    /**
     * @param EntityManager $entityManager
     * @param Entity\EntityAbstract $object
     */
    public function __construct(EntityManager $entityManager, Entity\EntityAbstract $object)
    {
        parent::__construct($object->get('underscore_entity_name'));
        $doctrineHydrator = new DoctrineHydrator($entityManager);
        $this->setHydrator($doctrineHydrator)->setObject($object);
        $builder = new AnnotationBuilder();
        /*
         * Go over the different form elements and add them to the form
         */
        foreach ($builder->createForm($object)->getElements() as $element) {
            /*
             * Go over each element to add the objectManager to the EntitySelect
             */
            if ($element instanceof EntitySelect || $element instanceof EntityMultiCheckbox) {
                $element->setOptions(
                    array_merge_recursive(
                        $element->getOptions(),
                        [
                            'object_manager' => $entityManager,
                        ]
                    )
                );
            }
            if ($element instanceof Radio) {
                $attributes = $element->getAttributes();
                $valueOptionsArray = 'get' . ucfirst($attributes['array']);
                $element->setOptions(
                    array_merge_recursive(
                        $element->getOptions(),
                        [
                            'value_options' => $object->$valueOptionsArray(),
                        ]
                    )
                );
            }
            //Add only when a type is provided
            if (array_key_exists('type', $element->getAttributes())) {
                $this->add($element);
            }
        }
    }
}
