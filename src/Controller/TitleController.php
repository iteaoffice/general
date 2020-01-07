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
use General\Entity\Title;
use General\Form\TitleFilter;
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
final class TitleController extends AbstractActionController
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
        $query = $this->generalService->findFiltered(Title::class, $filterPlugin->getFilter());

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

    public function viewAction(): ViewModel
    {
        $title = $this->generalService->find(Title::class, (int)$this->params('id'));
        if (null === $title) {
            return $this->notFoundAction();
        }

        return new ViewModel(['title' => $title]);
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Title::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/title/list');
            }

            if ($form->isValid()) {
                /* @var $title Title */
                $title = $form->getData();

                $this->generalService->save($title);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-title-%s-has-been-created-successfully'),
                            $title->getName()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/title/view',
                    [
                        'id' => $title->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var Title $title */
        $title = $this->generalService->find(Title::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($title, $data);
        $form->remove('delete');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/title/list');
            }

            if ($form->isValid()) {
                /** @var Title $title */
                $title = $form->getData();

                $this->generalService->save($title);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-title-%s-has-been-updated-successfully'),
                            $title->getName()
                        )
                    );

                return $this->redirect()->toRoute(
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
