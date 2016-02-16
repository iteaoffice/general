<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Challenge;
use General\Form\ChallengeFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 *
 */
class ChallengeController extends GeneralAbstractController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()
            ->findEntitiesFiltered('challenge', $filterPlugin->getFilter());

        $paginator
            = new Paginator(new PaginatorAdapter(new ORMPaginator(
                $contactQuery,
                false
            )));
        $paginator->setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX
            : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount()
            / $paginator->getDefaultItemCountPerPage()));

        $form = new ChallengeFilter($this->getGeneralService());
        $form->setData(['filter' => $filterPlugin->getFilter()]);

        return new ViewModel([
            'paginator'     => $paginator,
            'form'          => $form,
            'encodedFilter' => urlencode($filterPlugin->getHash()),
            'order'         => $filterPlugin->getOrder(),
            'direction'     => $filterPlugin->getDirection(),
        ]);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $challenge = $this->getGeneralService()
            ->findEntityById('challenge', $this->params('id'));
        if (is_null($challenge)) {
            return $this->notFoundAction();
        }

        return new ViewModel(['challenge' => $challenge]);
    }

    /**
     * Create a new template.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function newAction()
    {
        $data = array_merge(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()->prepare('challenge', null, $data);
        $form->remove('delete');

        $form->setAttribute('class', 'form-horizontal');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if ($form->isValid()) {
                /* @var $challenge Challenge */
                $challenge = $form->getData();

                $result = $this->getGeneralService()->newEntity($challenge);
                $this->redirect()->toRoute('zfcadmin/challenge/view', [
                    'id' => $result->getId(),
                ]);
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * Edit an template by finding it and call the corresponding form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $challenge = $this->getGeneralService()
            ->findEntityById('challenge', $this->params('id'));

        $data = array_merge(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()
            ->prepare($challenge->get('entity_name'), $challenge, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($challenge);

                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if ($form->isValid()) {
                $result = $this->getGeneralService()
                    ->updateEntity($form->getData());
                $this->redirect()->toRoute('zfcadmin/challenge/view', [
                    'id' => $result->getId(),
                ]);
            }
        }

        return new ViewModel(['form' => $form, 'challenge' => $challenge]);
    }
}
