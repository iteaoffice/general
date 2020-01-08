<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\Challenge;
use General\Form\ChallengeFilter;
use General\Service\FormService;
use General\Service\GeneralService;
use Laminas\Http\Response;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Paginator\Paginator;
use Laminas\Validator\File\MimeType;
use Laminas\View\Model\ViewModel;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class ChallengeController extends AbstractActionController
{
    private GeneralService $generalService;
    private FormService $formService;
    private TranslatorInterface $translator;
    private EntityManager $entityManager;

    public function __construct(
        GeneralService $generalService,
        FormService $formService,
        TranslatorInterface $translator,
        EntityManager $entityManager
    ) {
        $this->generalService = $generalService;
        $this->formService = $formService;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService->findFiltered(Challenge::class, $filterPlugin->getFilter());

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

    public function viewAction(): ViewModel
    {
        $challenge = $this->generalService->find(Challenge::class, (int)$this->params('id'));
        if (null === $challenge) {
            return $this->notFoundAction();
        }

        return new ViewModel(['challenge' => $challenge]);
    }

    public function newAction()
    {
        $data = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->formService->prepare(Challenge::class, $data);
        $form->remove('delete');

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if ($form->isValid()) {
                /* @var $challenge Challenge */
                $challenge = $form->getData();

                $fileData = $this->params()->fromFiles()['general_entity_challenge'];

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (! empty($fileData['icon']['tmp_name'])) {
                    $icon = $challenge->getIcon();
                    if (null === $icon) {
                        $icon = new Challenge\Icon();
                        $icon->setChallenge($challenge);
                    }

                    $icon->setIcon(file_get_contents($fileData['icon']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['icon']);
                    $icon->setContentType(
                        $this->generalService->findContentTypeByContentTypeName($fileTypeValidator->type)
                    );
                }

                //Remove the icon when the tmp is empty and there is not icon at all
                if (empty($fileData['icon']['tmp_name'])) {
                    $challenge->setIcon(null);
                }
                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (! empty($fileData['image']['tmp_name'])) {
                    $image = $challenge->getImage();
                    if (null === $image) {
                        $image = new Challenge\Image();
                        $image->setChallenge($challenge);
                    }

                    $image->setImage(file_get_contents($fileData['image']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['image']);
                    $image->setContentType(
                        $this->generalService->findContentTypeByContentTypeName($fileTypeValidator->type)
                    );
                }

                //Remove the image when the tmp is empty and there is not image at all
                if (empty($fileData['image']['tmp_name'])) {
                    $challenge->setImage(null);
                }

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (! empty($fileData['pdf']['tmp_name'])) {
                    $pdf = $challenge->getPdf();
                    if (null === $pdf) {
                        $pdf = new Challenge\Pdf();
                        $pdf->setChallenge($challenge);
                    }
                    $pdf->setPdf(file_get_contents($fileData['pdf']['tmp_name']));
                }

                //Remove the pdf when the tmp is empty and there is not pdf at all
                if (empty($fileData['pdf']['tmp_name'])) {
                    $challenge->setPdf(null);
                }

                $challenge = $this->generalService->save($challenge);

                $this->flashMessenger()->addSuccessMessage(
                    sprintf(
                        $this->translator->translate('txt-challenge-%s-has-successfully-been-created'),
                        $challenge
                    )
                );

                return $this->redirect()->toRoute(
                    'zfcadmin/challenge/view',
                    [
                        'id' => $challenge->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var Challenge $challenge */
        $challenge = $this->generalService->find(Challenge::class, (int)$this->params('id'));

        //Store the icon, image, pdf for later use
        $pdf = $challenge->getPdf();
        $image = $challenge->getImage();
        $icon = $challenge->getIcon();

        $data = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
        );

        $form = $this->formService->prepare($challenge, $data);

        //We do not need the icon and the PDF right now
        $form->getInputFilter()->get('general_entity_challenge')->get('icon')->setRequired(false);
        $form->getInputFilter()->get('general_entity_challenge')->get('pdf')->setRequired(false);

        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if (isset($data['delete'])) {
                $this->generalService->delete($challenge);

                return $this->redirect()->toRoute('zfcadmin/challenge/list');
            }

            if ($form->isValid()) {
                /** @var Challenge $challenge */
                $challenge = $form->getData();

                $fileData = $this->params()->fromFiles()['general_entity_challenge'];

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (! empty($fileData['icon']['tmp_name'])) {
                    if (null === $icon) {
                        $icon = new Challenge\Icon();
                    }
                    $icon->setChallenge($challenge);
                    $icon->setIcon(file_get_contents($fileData['icon']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['icon']);
                    $icon->setContentType(
                        $this->generalService->findContentTypeByContentTypeName($fileTypeValidator->type)
                    );

                    $challenge->setIcon($icon);
                }

                //Remove the icon when the tmp is empty and there is not icon at all
                if (
                    empty($fileData['icon']['tmp_name']) && null !== $challenge->getIcon()
                    && null === $challenge->getIcon()->getId()
                ) {
                    $challenge->setIcon(null);
                }


                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (! empty($fileData['image']['tmp_name'])) {
                    if (null === $image) {
                        $image = new Challenge\Image();
                    }
                    $image->setChallenge($challenge);
                    $image->setImage(file_get_contents($fileData['image']['tmp_name']));

                    $fileTypeValidator = new MimeType();
                    $fileTypeValidator->isValid($fileData['image']);
                    $image->setContentType(
                        $this->generalService->findContentTypeByContentTypeName($fileTypeValidator->type)
                    );

                    $challenge->setImage($image);
                }

                //Remove the image when the tmp is empty and there is not image at all
                if (
                    empty($fileData['image']['tmp_name'])
                    && null !== $challenge->getImage()
                    && null === $challenge->getImage()->getId()
                ) {
                    $challenge->setImage(null);
                }

                /**
                 * Handle the new logo (if any logo is updated)
                 */
                if (! empty($fileData['pdf']['tmp_name'])) {
                    if (null === $pdf) {
                        $pdf = new Challenge\Pdf();
                    }
                    $pdf->setChallenge($challenge);
                    $pdf->setPdf(file_get_contents($fileData['pdf']['tmp_name']));

                    $challenge->setPdf($pdf);
                }

                //Remove the pdf when the tmp is empty and there is not pdf at all
                if (
                    empty($fileData['pdf']['tmp_name'])
                    && null !== $challenge->getPdf()
                    && null === $challenge->getPdf()->getId()
                ) {
                    $challenge->setPdf(null);
                }

                //Manually feed the rest of the form elements to the $challage as we cannot use the $form->getData here is it
                //Does not contain all the images

                if (empty($data['general_entity_challenge']['call'])) {
                    $challenge->setCall([]);
                }

                $challenge = $this->generalService->save($challenge);

                $this->flashMessenger()->addSuccessMessage(
                    sprintf(
                        $this->translator->translate('txt-challenge-%s-has-successfully-been-updated'),
                        $challenge
                    )
                );

                return $this->redirect()->toRoute(
                    'zfcadmin/challenge/view',
                    [
                        'id' => $challenge->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'challenge' => $challenge]);
    }

    public function downloadPdfAction(): Response
    {
        /**
         * @var $challenge Challenge
         */
        $challenge = $this->generalService->find(Challenge::class, (int)$this->params('id'));

        /** @var Response $response */
        $response = $this->getResponse();

        /*
         * Check if a project is found
         */
        if (null === $challenge || null === $challenge->getPdf()) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $file = stream_get_contents($challenge->getPdf()->getPdf());

        $response->getHeaders()->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000, must-revalidate')->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: application/pdf')
            ->addHeaderLine('Content-Length: ' . strlen($file));
        $response->setContent($file);

        return $response;
    }
}
