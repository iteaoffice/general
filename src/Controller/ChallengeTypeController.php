<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Challenge;
use General\Form\ChallengeTypeFilter;
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
final class ChallengeTypeController extends AbstractActionController
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
        $contactQuery = $this->generalService
            ->findFiltered(Challenge\Type::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new ChallengeTypeFilter();
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
        $challengeType = $this->generalService->find(Challenge\Type::class, (int)$this->params('id'));

        if (null === $challengeType) {
            return $this->notFoundAction();
        }

        return new ViewModel(['type' => $challengeType]);
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Challenge\Type::class, $data);
        $form->remove('delete');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/type/list');
            }

            if ($form->isValid()) {
                /* @var $challengeTypeType Challenge\Type */
                $challengeTypeType = $form->getData();

                $challengeTypeType = $this->generalService->save($challengeTypeType);

                return $this->redirect()->toRoute(
                    'zfcadmin/challenge/type/view',
                    [
                        'id' => $challengeTypeType->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var Challenge\Type $challengeType */
        $challengeType = $this->generalService->find(Challenge\Type::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();
        $form = $this->formService->prepare($challengeType, $data);

        if (null === $challengeType) {
            return $this->notFoundAction();
        }

        if (! $challengeType->getChallenge()->isEmpty()) {
            $form->remove('delete');
        }

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/type/list');
            }

            if (isset($data['delete']) && $challengeType->getChallenge()->isEmpty()) {
                $this->generalService->delete($challengeType);

                return $this->redirect()->toRoute('zfcadmin/challenge/type/list');
            }

            if ($form->isValid()) {
                /** @var Challenge\Type $challengeType */
                $challengeType = $form->getData();

                $this->generalService->save($challengeType);

                return $this->redirect()->toRoute(
                    'zfcadmin/challenge/type/view',
                    [
                        'id' => $challengeType->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'challengeType' => $challengeType]);
    }
}
