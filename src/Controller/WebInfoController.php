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

use Contact\Entity\Contact;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\WebInfo;
use General\Form\WebInfoFilter;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\GeneralService;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Mvc\Plugin\Identity\Identity;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class WebInfoController
 *
 * @package General\Controller
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 * @method Identity|Contact identity()
 */
class WebInfoController extends AbstractActionController
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
     * @var EmailService
     */
    protected $emailService;

    /**
     * WebInfoController constructor.
     *
     * @param GeneralService      $generalService
     * @param FormService         $formService
     * @param TranslatorInterface $translator
     * @param EmailService        $emailService
     */
    public function __construct(
        GeneralService $generalService,
        FormService $formService,
        TranslatorInterface $translator,
        EmailService $emailService
    ) {
        $this->generalService = $generalService;
        $this->formService = $formService;
        $this->translator = $translator;
        $this->emailService = $emailService;
    }


    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService->findFiltered(WebInfo::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new WebInfoFilter();
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
        /** @var WebInfo $webInfo */
        $webInfo = $this->generalService->find(WebInfo::class, (int)$this->params('id'));
        if (null === $webInfo) {
            return $this->notFoundAction();
        }

        if ($this->getRequest()->isPost()) {
            $this->flashMessenger()->setNamespace('info')
                ->addMessage(
                    sprintf(
                        $this->translator->translate("txt-test-mail-of-web-info-%s-has-been-send-successfully"),
                        $webInfo->getInfo()
                    )
                );
            $email = $this->emailService->create();
            $this->emailService->setTemplate($webInfo->getInfo());
            $email->addTo($this->identity());
            $this->emailService->send();
        }

        return new ViewModel(['webInfo' => $webInfo]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(WebInfo::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                /** @var $webInfo WebInfo */
                $webInfo = $form->getData();

                $result = $this->generalService->save($webInfo);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate("txt-web-info-%s-has-been-created-successfully"),
                            $webInfo->getInfo()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/web-info/view',
                    [
                        'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'fullVersion' => true]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var WebInfo $webInfo */
        $webInfo = $this->generalService->find(WebInfo::class, (int)$this->params('id'));

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare($webInfo, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if (isset($data['delete'])) {
                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate("txt-web-info-%s-has-been-removed-successfully"),
                            $webInfo->getInfo()
                        )
                    );

                $this->generalService->delete($webInfo);

                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                /** @var WebInfo $webInfo */
                $webInfo = $form->getData();
                $result = $this->generalService->save($webInfo);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate("txt-web-info-%s-has-been-updated-successfully"),
                            $webInfo->getInfo()
                        )
                    );

                return $this->redirect()->toRoute(
                    'zfcadmin/web-info/view',
                    [
                        'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'webInfo' => $webInfo]);
    }
}
