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
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Title;
use General\Form\TitleFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 *
 */
class TitleController extends GeneralAbstractController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $query = $this->getGeneralService()->findEntitiesFiltered(Title::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($query, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new TitleFilter();
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel(
            [
                'paginator'     => $paginator,
                'form'          => $form,
                'encodedFilter' => urlencode($filterPlugin->getHash()),
                'order'         => $filterPlugin->getOrder(),
                'direction'     => $filterPlugin->getDirection(),
            ]
        );
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $title = $this->getGeneralService()->findEntityById(Title::class, $this->params('id'));
        if (is_null($title)) {
            return $this->notFoundAction();
        }

        return new ViewModel(['title' => $title]);
    }

    /**
     * Create a new template.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function newAction()
    {
        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare(Title::class, null, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/title/list');
            }

            if ($form->isValid()) {
                /* @var $title Title */
                $title = $form->getData();

                $result = $this->getGeneralService()->newEntity($title);
                $this->redirect()->toRoute(
                    'zfcadmin/title/view',
                    [
                        'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $title = $this->getGeneralService()->findEntityById(Title::class, $this->params('id'));

        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare($title, $title, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/title/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($title);

                return $this->redirect()->toRoute('zfcadmin/title/list');
            }

            if ($form->isValid()) {
                /** @var Title $title */
                $title = $form->getData();

                $title = $this->getGeneralService()->updateEntity($title);
                $this->redirect()->toRoute(
                    'zfcadmin/title/view',
                    [
                        'id' => $title->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'title' => $title]);
    }
}
