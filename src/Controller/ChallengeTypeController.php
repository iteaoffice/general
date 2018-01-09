<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Challenge;
use General\Form\ChallengeTypeFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class ChallengeTypeController
 *
 * @package General\Controller
 */
class ChallengeTypeController extends GeneralAbstractController
{
    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()
            ->findEntitiesFiltered(Challenge\Type::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
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

    /**
     * @return ViewModel
     */
    public function viewAction(): ViewModel
    {
        $challengeType = $this->getGeneralService()->findEntityById(Challenge\Type::class, $this->params('id'));

        if (null === $challengeType) {
            return $this->notFoundAction();
        }

        return new ViewModel(['type' => $challengeType]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction()
    {
        $data = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        $form = $this->getFormService()->prepare(Challenge\Type::class, null, $data);
        $form->remove('delete');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/challenge/type/list');
            }

            if ($form->isValid()) {
                /* @var $challengeTypeType Challenge\Type */
                $challengeTypeType = $form->getData();

                $challengeTypeType = $this->getGeneralService()->newEntity($challengeTypeType);

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

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction()
    {
        /** @var Challenge\Type $challengeType */
        $challengeType = $this->getGeneralService()->findEntityById(Challenge\Type::class, $this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();
        $form = $this->getFormService()->prepare($challengeType, $challengeType, $data);

        if (null === $challengeType) {
            return $this->notFoundAction();
        }

        if (!$challengeType->getChallenge()->isEmpty()) {
            $form->remove('delete');
        }

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/type/list');
            }

            if (isset($data['delete']) && $challengeType->getChallenge()->isEmpty()) {
                $this->getGeneralService()->removeEntity($challengeType);

                return $this->redirect()->toRoute('zfcadmin/challenge/type/list');
            }

            if ($form->isValid()) {
                /** @var Challenge\Type $challengeType */
                $challengeType = $form->getData();

                $this->getGeneralService()->updateEntity($challengeType);

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
