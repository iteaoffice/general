<?php
/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Vat;
use General\Form\VatFilter;
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
 */
final class VatController extends AbstractActionController
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
        $vatQuery = $this->generalService->findFiltered(Vat::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($vatQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new VatFilter();
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

    public function viewAction(): ViewModel
    {
        $vat = $this->generalService->find(Vat::class, (int)$this->params('id'));
        if (null === $vat) {
            return $this->notFoundAction();
        }

        return new ViewModel(['vat' => $vat]);
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Vat::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/vat/list');
            }

            if ($form->isValid()) {
                /* @var $vat Vat */
                $vat = $form->getData();

                $this->generalService->save($vat);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-vat-%s-has-been-created-successfully'),
                            $vat->getCode()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/vat/view',
                    [
                        'id' => $vat->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var Vat $vat */
        $vat = $this->generalService->find(Vat::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($vat, $data);
        $form->remove('delete');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/vat/list');
            }

            if ($form->isValid()) {
                /** @var Vat $vat */
                $vat = $form->getData();

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-vat-%s-has-been-updated-successfully'),
                            $vat->getCode()
                        )
                    );

                $this->generalService->save($vat);
                return $this->redirect()->toRoute(
                    'zfcadmin/vat/view',
                    [
                        'id' => $vat->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'vat' => $vat]);
    }
}
