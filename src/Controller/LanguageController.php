<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Language;
use General\Service\FormService;
use General\Service\GeneralService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;
use Search\Form\SearchFilter;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class LanguageController extends AbstractActionController
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
        $this->formService    = $formService;
        $this->translator     = $translator;
    }

    public function listAction(): ViewModel
    {
        $page         = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService->findFiltered(Language::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new SearchFilter();
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
        $language = $this->generalService->find(Language::class, (int)$this->params('id'));
        if (null === $language) {
            return $this->notFoundAction();
        }

        return new ViewModel(['language' => $language]);
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Language::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/language/list');
            }

            if ($form->isValid()) {
                /* @var $language Language */
                $language = $form->getData();

                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('txt-language-has-been-created-successfully'),
                );

                $this->generalService->save($language);

                return $this->redirect()->toRoute(
                    'zfcadmin/language/view',
                    [
                        'id' => $language->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var Language $language */
        $language = $this->generalService->find(Language::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($language, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/language/list');
            }

            if (isset($data['delete'])) {
                $this->generalService->delete($language);

                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('txt-language-has-been-deleted-successfully')
                );


                return $this->redirect()->toRoute('zfcadmin/language/list');
            }

            if ($form->isValid()) {
                /** @var Language $language */
                $language = $form->getData();

                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('txt-language-has-been-updated-successfully'),
                );


                $this->generalService->save($language);
                return $this->redirect()->toRoute(
                    'zfcadmin/language/view',
                    [
                        'id' => $language->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'language' => $language]);
    }
}
