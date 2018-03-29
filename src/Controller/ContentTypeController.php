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
use General\Controller\Plugin\GetFilter;
use General\Entity\ContentType;
use General\Form\ContentTypeFilter;
use General\Service\FormService;
use General\Service\GeneralService;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class ContentTypeController
 *
 * @package General\Controller
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
class ContentTypeController extends AbstractActionController
{
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var FormService
     */
    protected $formService;
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * ContentTypeController constructor.
     *
     * @param GeneralService      $generalService
     * @param FormService         $formService
     * @param TranslatorInterface $translator
     */
    public function __construct(
        GeneralService $generalService,
        FormService $formService,
        TranslatorInterface $translator
    ) {
        $this->generalService = $generalService;
        $this->formService = $formService;
        $this->translator = $translator;
    }


    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService
            ->findFiltered(ContentType::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new ContentTypeFilter();
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
        $contentType = $this->generalService->find(ContentType::class, (int)$this->params('id'));
        if (null === $contentType) {
            return $this->notFoundAction();
        }

        return new ViewModel(['contentType' => $contentType]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(ContentType::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/content-type/list');
            }

            if ($form->isValid()) {
                /* @var $contentType ContentType */
                $contentType = $form->getData();

                $this->generalService->save($contentType);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate("txt-content-type-%s-has-been-created-successfully"),
                            $contentType->getDescription()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/content-type/view',
                    [
                        'id' => $contentType->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'fullVersion' => true]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction()
    {
        /** @var ContentType $contentType */
        $contentType = $this->generalService->find(ContentType::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($contentType, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/content-type/list');
            }

            if ($form->isValid()) {
                /** @var ContentType $contentType */
                $contentType = $form->getData();

                $this->generalService->save($contentType);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate("txt-content-type-%s-has-been-updated-successfully"),
                            $contentType->getDescription()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/content-type/view',
                    [
                        'id' => $contentType->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'contentType' => $contentType]);
    }
}
