<?php

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     Service
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
namespace General\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form;

use General\Service\GeneralService;

class FormService implements ServiceLocatorAwareInterface
{

    /**
     * @var \Zend\Form\Form
     */
    protected $form;
    /**
     * @var \General\Service\GeneralService
     */
    protected $generalService;
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param null $className
     * @param null $entity
     * @param bool $bind
     *
     * @return array|object
     */
    public function getForm($className = null, $entity = null, $bind = true)
    {
        if (!$entity) {
            $entity = $this->getGeneralService()->getEntity($className);
        }

        $formName = 'general_' . $entity->get('underscore_entity_name') . '_form';
        $form     = $this->getServiceLocator()->get($formName);

        $filterName = 'general_' . $entity->get('underscore_entity_name') . '_form_filter';
        $filter     = $this->getServiceLocator()->get($filterName);

        $form->setInputFilter($filter);

        if ($bind) {
            $form->bind($entity);
        }

        return $form;
    }

    /**
     * @param       $className
     * @param null  $entity
     * @param array $data
     *
     * @return array|object
     */
    public function prepare($className, $entity = null, $data = array())
    {
        $form = $this->getForm($className, $entity, true);
        $form->setData($data);

        return $form;
    }

    /**
     * @param GeneralService $generalService
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
    }

    /**
     * Get generalService.
     *
     * @return GeneralService.
     */
    public function getGeneralService()
    {
        if (null === $this->generalService) {
            $this->generalService = $this->getServiceLocator()->get('general_general_service');
        }

        return $this->generalService;
    }

    /**
     * Set the service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get the service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
