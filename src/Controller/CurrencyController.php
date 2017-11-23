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
use General\Entity\Currency;
use General\Form\CurrencyFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class CurrencyController
 * @package General\Controller
 */
class CurrencyController extends GeneralAbstractController
{
    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()->findEntitiesFiltered(Currency::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new CurrencyFilter();
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel(
            [
                'paginator'      => $paginator,
                'form'           => $form,
                'generalService' => $this->getGeneralService(),
                'encodedFilter'  => urlencode($filterPlugin->getHash()),
                'order'          => $filterPlugin->getOrder(),
                'direction'      => $filterPlugin->getDirection(),
            ]
        );
    }

    /**
     * @return ViewModel
     */
    public function viewAction(): ViewModel
    {
        $currency = $this->getGeneralService()->findEntityById(Currency::class, $this->params('id'));
        if (\is_null($currency)) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'generalService' => $this->getGeneralService(),
            'currency'       => $currency
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->getFormService()->prepare(Currency::class, null, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/currency/list');
            }

            if ($form->isValid()) {
                /* @var $currency Currency */
                $currency = $form->getData();

                $result = $this->getGeneralService()->newEntity($currency);

                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
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
        /** @var Currency $currency */
        $currency = $this->getGeneralService()->findEntityById(Currency::class, $this->params('id'));
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->getFormService()->prepare($currency, $currency, $data);

        if ($this->getGeneralService()->canDeleteCurrency($currency)) {
            $form->remove('delete');
        }

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/currency/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($currency);

                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }

            if ($form->isValid()) {
                /** @var Currency $currency */
                $currency = $form->getData();

                $currency = $this->getGeneralService()->updateEntity($currency);

                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'currency' => $currency]);
    }
}
