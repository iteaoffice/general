<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Log;
use General\Form\EmailFilter;
use General\Service\GeneralService;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class LogController
 *
 * @package General\Controller
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class LogController extends AbstractActionController
{
    /**
     * @var GeneralService
     */
    private $generalService;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        GeneralService $generalService,
        EntityManager $entityManager,
        TranslatorInterface $translator
    ) {
        $this->generalService = $generalService;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function listAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $logQuery = $this->generalService
            ->findFiltered(Log::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($logQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new EmailFilter($this->entityManager);
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        if ($this->getRequest()->isGet() && null !== $this->getRequest()->getQuery('submit')) {
            $this->generalService->truncateLog();

            $this->flashMessenger()->addSuccessMessage(
                $this->translator->translate("txt-log-has-been-truncated-successfully")
            );

            return $this->redirect()->toRoute('zfcadmin/log/list');
        }

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
        $log = $this->generalService->find(Log::class, (int)$this->params('id'));
        if (null === $log) {
            return $this->notFoundAction();
        }

        return new ViewModel(['log' => $log]);
    }
}
