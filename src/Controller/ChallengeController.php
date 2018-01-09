<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Entity\Challenge;
use General\Form\ChallengeFilter;
use Zend\Paginator\Paginator;
use Zend\Validator\File\MimeType;
use Zend\View\Model\ViewModel;

/**
 * Class ChallengeController
 * @package General\Controller
 */
class ChallengeController extends GeneralAbstractController
{
    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()->findEntitiesFiltered(Challenge::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new ChallengeFilter($this->entityManager);
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
        $challenge = $this->getGeneralService()->findEntityById(Challenge::class, $this->params('id'));
        if (\is_null($challenge)) {
            return $this->notFoundAction();
        }

        return new ViewModel(['challenge' => $challenge]);
    }

    /**
     * @return ViewModel
     */
    public function newAction(): ViewModel
    {
        $data = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()->prepare(Challenge::class, null, $data);
        $form->remove('delete');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if ($form->isValid()) {
                /* @var $challenge Challenge */
                $challenge = $form->getData();

                $fileData = $this->params()->fromFiles()['general_entity_challenge'];

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (!empty($fileData['icon']['tmp_name'])) {
                    $icon = $challenge->getIcon();
                    $icon->setChallenge($challenge);
                    $icon->setIcon(file_get_contents($fileData['icon']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['icon']);
                    $icon->setContentType($this->getGeneralService()->findContentTypeByContentTypeName($fileTypeValidator->type));

                    //Remove the file if it exist
                    if (file_exists($icon->getCacheFileName())) {
                        unlink($icon->getCacheFileName());
                    }
                }

                //Remove the icon when the tmp is empty and there is not icon at all
                if (empty($fileData['icon']['tmp_name'])) {
                    $challenge->setIcon(null);
                }


                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (!empty($fileData['image']['tmp_name'])) {
                    $image = $challenge->getImage();
                    $image->setChallenge($challenge);
                    $image->setImage(file_get_contents($fileData['image']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['image']);
                    $image->setContentType($this->getGeneralService()->findContentTypeByContentTypeName($fileTypeValidator->type));
                }

                //Remove the image when the tmp is empty and there is not image at all
                if (empty($fileData['image']['tmp_name'])) {
                    $challenge->setImage(null);
                }

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (!empty($fileData['pdf']['tmp_name'])) {
                    $pdf = $challenge->getPdf();
                    $pdf->setChallenge($challenge);
                    $pdf->setPdf(file_get_contents($fileData['pdf']['tmp_name']));
                }

                //Remove the pdf when the tmp is empty and there is not pdf at all
                if (empty($fileData['pdf']['tmp_name'])) {
                    $challenge->setPdf(null);
                }

                $challenge = $this->getGeneralService()->newEntity($challenge);

                $this->flashMessenger()->setNamespace('success')
                    ->addMessage(sprintf(
                        $this->translate("txt-challenge-%s-has-successfully-been-created"),
                        $challenge
                    ));

                $this->redirect()->toRoute(
                    'zfcadmin/challenge/view',
                    [
                        'id' => $challenge->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction(): ViewModel
    {
        /** @var Challenge $challenge */
        $challenge = $this->getGeneralService()->findEntityById(Challenge::class, $this->params('id'));

        //Store the icon, image, pdf for later use
        $pdf = $challenge->getPdf();
        $image = $challenge->getImage();
        $icon = $challenge->getIcon();

        $data = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->getFormService()->prepare($challenge, $challenge, $data);

        //We do not need the icon and the PDF right now
        $form->getInputFilter()->get('general_entity_challenge')->get('icon')->setRequired(false);
        $form->getInputFilter()->get('general_entity_challenge')->get('pdf')->setRequired(false);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if (isset($data['delete'])) {
                $this->getGeneralService()->removeEntity($challenge);

                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if ($form->isValid()) {
                $challenge = $form->getData();

                $fileData = $this->params()->fromFiles()['general_entity_challenge'];

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (!empty($fileData['icon']['tmp_name'])) {
                    if (\is_null($icon)) {
                        $icon = new Challenge\Icon();
                    }
                    $icon->setChallenge($challenge);
                    $icon->setIcon(file_get_contents($fileData['icon']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['icon']);
                    $icon->setContentType($this->getGeneralService()->findContentTypeByContentTypeName($fileTypeValidator->type));

                    $challenge->setIcon($icon);
                }

                //Remove the icon when the tmp is empty and there is not icon at all
                if (empty($fileData['icon']['tmp_name']) && !\is_null($challenge->getIcon()) && \is_null($challenge->getIcon()->getId())) {
                    $challenge->setIcon(null);
                }


                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (!empty($fileData['image']['tmp_name'])) {
                    if (null === $image) {
                        $image = new Challenge\Image();
                    }
                    $image->setChallenge($challenge);
                    $image->setImage(file_get_contents($fileData['image']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['image']);
                    $image->setContentType($this->getGeneralService()->findContentTypeByContentTypeName($fileTypeValidator->type));

                    $challenge->setImage($image);
                }

                //Remove the image when the tmp is empty and there is not image at all
                if (empty($fileData['image']['tmp_name']) && !\is_null($challenge->getImage()) && \is_null($challenge->getImage()->getId())) {
                    $challenge->setImage(null);
                }

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (!empty($fileData['pdf']['tmp_name'])) {
                    if (null === $pdf) {
                        $pdf = new Challenge\Pdf();
                    }
                    $pdf->setChallenge($challenge);
                    $pdf->setPdf(file_get_contents($fileData['pdf']['tmp_name']));

                    $challenge->setPdf($pdf);
                }

                //Remove the pdf when the tmp is empty and there is not pdf at all
                if (empty($fileData['pdf']['tmp_name']) && !\is_null($challenge->getPdf()) && \is_null($challenge->getPdf()->getId())) {
                    $challenge->setPdf(null);
                }

                //Manually feed the rest of the form elements to the $challage as we cannot use the $form->getData here is it
                //Does not contain all the images

                if (empty($data['general_entity_challenge']['call'])) {
                    $challenge->setCall([]);
                }

                $challenge = $this->getGeneralService()->updateEntity($challenge);

                $this->flashMessenger()->setNamespace('success')
                    ->addMessage(sprintf(
                        $this->translate("txt-challenge-%s-has-successfully-been-updated"),
                        $challenge
                    ));

                $this->redirect()->toRoute(
                    'zfcadmin/challenge/view',
                    [
                        'id' => $challenge->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'challenge' => $challenge]);
    }


    /**
     * @return \Zend\Stdlib\ResponseInterface|ViewModel
     */
    public function downloadPdfAction()
    {
        /**
         * @var $challenge Challenge
         */
        $challenge = $this->getGeneralService()->findEntityById(Challenge::class, $this->params('id'));

        /*
         * Check if a project is found
         */
        if (\is_null($challenge) || \is_null($challenge->getPdf())) {
            return $this->notFoundAction();
        }

        $file = stream_get_contents($challenge->getPdf()->getPdf());

        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")->addHeaderLine("Pragma: public")
            ->addHeaderLine('Content-Type: application/pdf')
            ->addHeaderLine('Content-Length: ' . strlen($file));
        $response->setContent($file);

        return $response;
    }
}
