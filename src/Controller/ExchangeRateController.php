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

use General\Entity\Currency;
use General\Entity\ExchangeRate;
use Zend\View\Model\ViewModel;

/**
 * Class CurrencyController
 * @package General\Controller
 */
class ExchangeRateController extends GeneralAbstractController
{
    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction()
    {
        $currency = $this->getGeneralService()->findEntityById(Currency::class, $this->params('currencyId'));

        if (\is_null($currency)) {
            return $this->notFoundAction();
        }

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

        return new ViewModel([
            'form'     => $form,
            'currency' => $currency
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction()
    {
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $this->getGeneralService()->findEntityById(ExchangeRate::class, $this->params('id'));
        $currency = $exchangeRate->getCurrency();

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->getFormService()->prepare($exchangeRate, $exchangeRate, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($exchangeRate);

                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }

            if ($form->isValid()) {
                /** @var Currency $exchangeRate */
                $exchangeRate = $form->getData();

                $this->getGeneralService()->updateEntity($exchangeRate);

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
