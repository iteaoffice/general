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

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Currency;
use General\Form\CurrencyFilter;
use General\Service\FormService;
use General\Service\GeneralService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 *
 */
final class CurrencyController extends AbstractActionController
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

    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService->findFiltered(Currency::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new CurrencyFilter();
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel(
            [
                'paginator'      => $paginator,
                'form'           => $form,
                'generalService' => $this->generalService,
                'encodedFilter'  => urlencode($filterPlugin->getHash()),
                'order'          => $filterPlugin->getOrder(),
                'direction'      => $filterPlugin->getDirection(),
            ]
        );
    }

    public function viewAction(): ViewModel
    {
        $currency = $this->generalService->find(Currency::class, (int)$this->params('id'));
        if (null === $currency) {
            return $this->notFoundAction();
        }

        return new ViewModel(
            [
                'generalService' => $this->generalService,
                'currency'       => $currency
            ]
        );
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Currency::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/currency/list');
            }

            if ($form->isValid()) {
                /* @var $currency Currency */
                $currency = $form->getData();

                $result = $this->generalService->save($currency);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-currency-%s-has-been-created-successfully'),
                            $currency->getName()
                        )
                    );

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

    public function editAction()
    {
        /** @var Currency $currency */
        $currency = $this->generalService->find(Currency::class, (int)$this->params('id'));
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($currency, $data);

        if (! $this->generalService->canDeleteCurrency($currency)) {
            $form->remove('delete');
        }

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/currency/list');
            }

            if (isset($data['delete']) && $this->generalService->canDeleteCurrency($currency)) {
                $this->generalService->delete($currency);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-currency-%s-has-been-deleted-successfully'),
                            $currency->getName()
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
                /** @var Currency $currency */
                $currency = $form->getData();

                $this->generalService->save($currency);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-currency-%s-has-been-updated-successfully'),
                            $currency->getName()
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
