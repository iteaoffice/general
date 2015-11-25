<?php
/**
 * Jield copyright message placeholder.
 *
 * @category    Admin
 *
 * @author      Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2004-2015 Jield (http://jield.nl)
 */

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Web;
use General\Entity\WebInfo;
use General\Form\WebInfoFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class WebInfoController
 *
 * @package General\Controller
 */
class WebInfoController extends GeneralAbstractController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()
            ->findEntitiesFiltered('webInfo', $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator(
                $contactQuery,
                false
            )));
        $paginator->setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX
            : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount()
            / $paginator->getDefaultItemCountPerPage()));

        $form = new WebInfoFilter($this->getGeneralService());
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel([
            'paginator'     => $paginator,
            'form'          => $form,
            'encodedFilter' => urlencode($filterPlugin->getHash()),
            'order'         => $filterPlugin->getOrder(),
            'direction'     => $filterPlugin->getDirection(),
        ]);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $webInfo = $this->getGeneralService()
            ->findEntityById('webInfo', $this->params('id'));
        if (is_null($webInfo)) {
            return $this->notFoundAction();
        }

        return new ViewModel(['webInfo' => $webInfo]);
    }

    /**
     * Create a new template.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function newAction()
    {
        $data = array_merge(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()->prepare('webInfo', null, $data);
        $form->remove('delete');

        $form->setAttribute('class', 'form-horizontal');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                /* @var $webInfo WebInfo */
                $webInfo = $form->getData();
                $webInfo->setWeb($this->getGeneralService()->getEntityManager()
                    ->getReference(Web::class, 1));

                $result = $this->getGeneralService()->newEntity($webInfo);
                $this->redirect()->toRoute('zfcadmin/web-info/view', [
                    'id' => $result->getId(),
                ]);
            }
        }

        return new ViewModel(['form' => $form, 'fullVersion' => true]);
    }

    /**
     * Edit an template by finding it and call the corresponding form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $webInfo = $this->getGeneralService()
            ->findEntityById('webInfo', $this->params('id'));

        $data = array_merge(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()
            ->prepare($webInfo->get('entity_name'), $webInfo, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($webInfo);

                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                $result = $this->getGeneralService()
                    ->updateEntity($form->getData());
                $this->redirect()->toRoute('zfcadmin/web-info/view', [
                    'id' => $result->getId(),
                ]);
            } else {
                var_dump($form->getInputFilter()->getMessages());
            }
        }

        return new ViewModel(['form' => $form, 'webInfo' => $webInfo]);
    }
}
