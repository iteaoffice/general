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

use Contact\Entity\Contact;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\WebInfo;
use General\Form\WebInfoFilter;
use General\Service\EmailService;
use General\Service\FormService;
use General\Service\GeneralService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Mvc\Plugin\Identity\Identity;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\ViewModel;
use function defined;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 * @method Identity|Contact identity()
 */
final class WebInfoController extends AbstractActionController
{
    private GeneralService $generalService;
    private FormService $formService;
    private TranslatorInterface $translator;
    private EmailService $emailService;

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

    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService->findFiltered(WebInfo::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
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

    public function viewAction(): ViewModel
    {
        /** @var WebInfo $webInfo */
        $webInfo = $this->generalService->find(WebInfo::class, (int)$this->params('id'));
        if (null === $webInfo) {
            return $this->notFoundAction();
        }

        if ($this->getRequest()->isPost()) {
            $this->emailService->setWebInfo($webInfo->getInfo());
            $this->emailService->addTo($this->identity());
            $this->emailService->setFrom($this->identity()->parseFullName(), $this->identity()->getEmail());
            $this->emailService->setTemplateVariable('site', defined('ITEAOFFICE_HOST') ? ITEAOFFICE_HOST : 'test');

            if ($this->emailService->send()) {
                $this->flashMessenger()->addSuccessMessage(
                    sprintf(
                        $this->translator->translate('txt-test-mail-of-web-info-%s-has-been-send-successfully'),
                        $webInfo->getInfo()
                    )
                );
            } else {
                $this->flashMessenger()->addErrorMessage(
                    sprintf(
                        $this->translator->translate('txt-test-mail-of-web-info-%s-has-not-been-sent'),
                        $webInfo->getInfo()
                    )
                );
            }
        }

        return new ViewModel(['webInfo' => $webInfo]);
    }

    public function newAction()
    {
        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(WebInfo::class, $data);
        $form->remove('delete');


        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/web-info/list');
            }

            if ($form->isValid()) {
                /** @var $webInfo WebInfo */
                $webInfo = $form->getData();

                $result = $this->generalService->save($webInfo);

                $this->flashMessenger()->setNamespace('info')
                    ->addMessage(
                        sprintf(
                            $this->translator->translate('txt-web-info-%s-has-been-created-successfully'),
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
                $this->flashMessenger()->addInfoMessage(
                    sprintf(
                        $this->translator->translate('txt-web-info-%s-has-been-removed-successfully'),
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

                $this->flashMessenger()->addSuccessMessage(
                    sprintf(
                        $this->translator->translate('txt-web-info-%s-has-been-updated-successfully'),
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
