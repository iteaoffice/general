<?php
/**
 * Jield copyright message placeholder.
 *
 * @category    Admin
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use General\Controller\Plugin\GetFilter;
use General\Entity\Currency;
use General\Entity\ExchangeRate;
use General\Service\FormService;
use General\Service\GeneralService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class ExchangeRateController extends AbstractActionController
{
    private GeneralService $generalService;
    private FormService $formService;
    private TranslatorInterface $translator;

    public function __construct(
        GeneralService $generalService,
        FormService $formService,
        TranslatorInterface $translator
    ) {
        $this->generalService = $generalService;
        $this->formService = $formService;
        $this->translator = $translator;
    }

    public function newAction()
    {
        /** @var Currency $currency */
        $currency = $this->generalService->find(Currency::class, (int)$this->params('currencyId'));

        if (null === $currency) {
            return $this->notFoundAction();
        }

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(ExchangeRate::class, $data);
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

                $this->generalService->save($exchangeRate);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-exchangerate-for-%s-has-been-created-successfully'),
                            $exchangeRate->getCurrency()->getName()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }
        }

        return new ViewModel(
            [
                'form'     => $form,
                'currency' => $currency
            ]
        );
    }

    public function editAction()
    {
        /** @var ExchangeRate $exchangeRate */
        $exchangeRate = $this->generalService->find(ExchangeRate::class, (int)$this->params('id'));

        /** @var Currency $currency */
        $currency = $exchangeRate->getCurrency();

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($exchangeRate, $data);

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
                $this->generalService->delete($exchangeRate);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-exchangerate-for-%s-has-been-deleted-successfully'),
                            $exchangeRate->getCurrency()->getName()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/currency/view',
                    [
                        'id' => $currency->getId(),
                    ]
                );
            }

            if ($form->isValid()) {
                /** @var ExchangeRate $exchangeRate */
                $exchangeRate = $form->getData();

                $this->generalService->save($exchangeRate);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-exchangerate-for-%s-has-been-updated-successfully'),
                            $exchangeRate->getCurrency()->getName()
                        )
                    );

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
