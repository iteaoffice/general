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
use General\Entity\ExchangeRate;
use General\Form\CurrencyFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class CurrencyController
 * @package General\Controller
 */
class ExchangeRateController extends GeneralAbstractController
{
    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function newAction()
    {
        $currency = $this->getGeneralService()->findEntityById(Currency::class, $this->params('currencyId'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->getFormService()->prepare(ExchangeRate::class, null, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }

            if ($form->isValid()) {
                /* @var $exchangeRate ExchangeRate */
                $exchangeRate = $form->getData();
                $exchangeRate->setCurrency($currency);

                $result = $this->getGeneralService()->newEntity($exchangeRate);
                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
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
        $currency = $this->getGeneralService()->findEntityById(Currency::class, $this->params('id'));

        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare($currency, $currency, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/currency/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($currency);

                return $this->redirect()->toRoute('zfcadmin/currency/list');
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
