<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Log;
use General\Form\EmailFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class LogController
 *
 * @package General\Controller
 */
class LogController extends GeneralAbstractController
{
    /**
     * @return ViewModel
     */
    public function listAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $logQuery = $this->getGeneralService()
            ->findEntitiesFiltered(Log::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($logQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new EmailFilter($this->getEntityManager());
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        if ($this->getRequest()->isGet()) {
            if (null !== $this->getRequest()->getQuery('submit')) {
                $this->getGeneralService()->truncateLog();
            }
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

    /**
     * @return ViewModel
     */
    public function viewAction()
    {
        $log = $this->getGeneralService()
            ->findEntityById(Log::class, $this->params('id'));
        if (null === $log) {
            return $this->notFoundAction();
        }

        return new ViewModel(['log' => $log]);
    }
}
