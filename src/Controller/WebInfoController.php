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
        $contactQuery = $this->getGeneralService()->findEntitiesFiltered(WebInfo::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new WebInfoFilter();
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
     * @return array|ViewModel
     */
    public function viewAction()
    {
        /** @var WebInfo $webInfo */
        $webInfo = $this->getGeneralService()->findEntityById(WebInfo::class, $this->params('id'));
        if (\is_null($webInfo)) {
            return $this->notFoundAction();
        }

        if ($this->getRequest()->isPost()) {
            $this->flashMessenger()->setNamespace('info')
                ->addMessage(
                    sprintf(
                        $this->translate("txt-test-mail-of-web-info-%s-has-been-send-successfully"),
                        $webInfo->getInfo()
                    )
                );
            $email = $this->getEmailService()->create();
            $this->getEmailService()->setTemplate($webInfo->getInfo());
            $email->addTo($this->zfcUserAuthentication()->getIdentity());
            $this->getEmailService()->send();
        }

        return new ViewModel(['webInfo' => $webInfo]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function newAction()
    {
        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare(WebInfo::class, null, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                /** @var $webInfo WebInfo */
                $webInfo = $form->getData();

                $result = $this->getGeneralService()->newEntity($webInfo);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translate("txt-web-info-%s-has-been-created-successfully"),
                            $webInfo->getInfo()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/web-info/view',
                    [
                        'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'fullVersion' => true]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var WebInfo $webInfo */
        $webInfo = $this->getGeneralService()->findEntityById(WebInfo::class, $this->params('id'));

        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare($webInfo, $webInfo, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if (isset($data['delete'])) {
                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translate("txt-web-info-%s-has-been-removed-successfully"),
                            $webInfo->getInfo()
                        )
                    );

                $this->getGeneralService()->removeEntity($webInfo);

                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                /** @var WebInfo $webInfo */
                $webInfo = $form->getData();
                $result = $this->getGeneralService()->updateEntity($webInfo);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translate("txt-web-info-%s-has-been-updated-successfully"),
                            $webInfo->getInfo()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/web-info/view',
                    [
                        'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'webInfo' => $webInfo]);
    }
}
