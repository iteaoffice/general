<?php
/**
 * Jield copyright message placeholder.
 *
 * @category    Admin
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Password;
use General\Form\PasswordFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 *
 */
class PasswordController extends GeneralAbstractController
{
    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()->findEntitiesFiltered(Password::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new PasswordFilter();
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
     * @return ViewModel
     */
    public function viewAction(): ViewModel
    {
        $password = $this->getGeneralService()->findEntityById(Password::class, $this->params('id'));
        if (is_null($password)) {
            return $this->notFoundAction();
        }

        return new ViewModel(['password' => $password]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function newAction()
    {
        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare(Password::class, null, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/password/list');
            }

            if ($form->isValid()) {
                /* @var $password Password */
                $password = $form->getData();

                $this->flashMessenger()->setNamespace('success')
                    ->addMessage(
                        sprintf(
                            $this->translate("txt-password-for-%s-has-been-created-successfully"),
                            $password->getDescription()
                        )
                    );

                $result = $this->getGeneralService()->newEntity($password);

                return $this->redirect()->toRoute(
                    'zfcadmin/password/view',
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
        /** @var Password $password */
        $password = $this->getGeneralService()->findEntityById(Password::class, $this->params('id'));

        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare($password, $password, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/password/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($password);

                $this->flashMessenger()->setNamespace('success')
                    ->addMessage(
                        sprintf(
                            $this->translate("txt-password-for-%s-has-been-deleted-successfully"),
                            $password->getDescription()
                        )
                    );


                return $this->redirect()->toRoute('zfcadmin/password/list');
            }

            if ($form->isValid()) {
                /** @var Password $password */
                $password = $form->getData();

                $this->flashMessenger()->setNamespace('success')
                    ->addMessage(
                        sprintf(
                            $this->translate("txt-password-for-%s-has-been-updated-successfully"),
                            $password->getDescription()
                        )
                    );


                $password = $this->getGeneralService()->updateEntity($password);
                $this->redirect()->toRoute(
                    'zfcadmin/password/view',
                    [
                        'id' => $password->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'password' => $password]);
    }
}
