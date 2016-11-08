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
use General\Entity\Gender;
use General\Form\GenderFilter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 *
 */
class GenderController extends GeneralAbstractController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $page         = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()
            ->findEntitiesFiltered(Gender::class, $filterPlugin->getFilter());

        $paginator
            = new Paginator(
                new PaginatorAdapter(
                    new ORMPaginator(
                        $contactQuery,
                        false
                    )
                )
            );
        $paginator->setDefaultItemCountPerPage(
            ($page === 'all') ? PHP_INT_MAX
                : 20
        );
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(
            ceil(
                $paginator->getTotalItemCount()
                / $paginator->getDefaultItemCountPerPage()
            )
        );

        $form = new GenderFilter($this->getGeneralService());
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
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $gender = $this->getGeneralService()
            ->findEntityById(Gender::class, $this->params('id'));
        if (is_null($gender)) {
            return $this->notFoundAction();
        }

        return new ViewModel(['gender' => $gender]);
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

        $form = $this->getFormService()->prepare(Gender::class, null, $data);
        $form->remove('delete');

        $form->setAttribute('class', 'form-horizontal');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/gender/list');
            }

            if ($form->isValid()) {
                /* @var $gender Gender */
                $gender = $form->getData();

                $result = $this->getGeneralService()->newEntity($gender);
                $this->redirect()->toRoute(
                    'zfcadmin/gender/view',
                    [
                    'id' => $result->getId(),
                    ]
                );
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
        $gender = $this->getGeneralService()
            ->findEntityById(Gender::class, $this->params('id'));

        $data = array_merge(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()
            ->prepare($gender, $gender, $data);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/gender/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($gender);

                return $this->redirect()->toRoute('zfcadmin/gender/list');
            }

            if ($form->isValid()) {
                $result = $this->getGeneralService()
                    ->updateEntity($form->getData());
                $this->redirect()->toRoute(
                    'zfcadmin/gender/view',
                    [
                    'id' => $result->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'gender' => $gender]);
    }
}
