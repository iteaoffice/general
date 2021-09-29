<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Controller\Country;

use General\Entity\Country\Video;
use General\Service\CountryService;
use General\Service\FormService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/**
 * Class VideoController
 * @package General\Controller\Country
 * @method FlashMessenger flashMessenger()
 */
final class VideoController extends AbstractActionController
{
    private CountryService $countryService;
    private FormService $formService;
    private TranslatorInterface $translator;

    public function __construct(CountryService $countryService, FormService $formService, TranslatorInterface $translator)
    {
        $this->countryService = $countryService;
        $this->formService    = $formService;
        $this->translator     = $translator;
    }

    public function viewAction(): ViewModel
    {
        /** @var Video $video */
        $video = $this->countryService->find(Video::class, (int)$this->params('id'));

        if (null === $video) {
            return $this->notFoundAction();
        }

        return new ViewModel(['video' => $video]);
    }


    public function editAction()
    {
        /** @var Video $video */
        $video = $this->countryService->find(Video::class, (int)$this->params('id'));

        if (null === $video) {
            return $this->notFoundAction();
        }

        $data = $this->getRequest()->getPost()->toArray();


        $form = $this->formService->prepare($video, $data);
        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute(
                    'zfcadmin/country/video/view',
                    [
                        'id' => $video->getId(),
                    ]
                );
            }

            if (isset($data['delete'])) {
                $this->countryService->delete($video);
                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('txt-country-video-has-been-deleted-successfully')
                );

                return $this->redirect()->toRoute(
                    'zfcadmin/country/view',
                    [
                        'id' => $video->getCountry()->getId(),
                    ]
                );
            }

            if ($form->isValid()) {
                /**
                 * @var $video Video
                 */
                $video = $form->getData();

                $this->countryService->save($video);
                $this->flashMessenger()->addSuccessMessage(
                    $this->translator->translate('txt-country-video-has-been-updated-successfully')
                );

                return $this->redirect()->toRoute(
                    'zfcadmin/country/video/view',
                    [
                        'id' => $video->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'video' => $video]);
    }

    public function newAction()
    {
        /** @var Video $video */
        $country = $this->countryService->findCountryById((int)$this->params('country'));

        if (null === $country) {
            return $this->notFoundAction();
        }

        $data = $this->getRequest()->getPost()->toArray();

        $form = $this->formService->prepare(Video::class, $data);
        $form->remove('delete');
        if ($this->getRequest()->isPost()) {
            if (isset($data['cancel'])) {
                return $this->redirect()->toRoute(
                    'zfcadmin/country/view',
                    [
                        'id' => $country->getId(),
                    ]
                );
            }

            if ($form->isValid()) {
                /**
                 * @var $video Video
                 */
                $video = $form->getData();
                $video->setCountry($country);

                if (isset($data['submit'])) {
                    $this->countryService->save($video);
                    $this->flashMessenger()->addSuccessMessage(
                        $this->translator->translate('txt-country-video-has-been-updated-successfully')
                    );
                }

                return $this->redirect()->toRoute(
                    'zfcadmin/country/video/view',
                    [
                        'id' => $video->getId(),
                    ]
                );
            }
        }

        return new ViewModel(['form' => $form, 'country' => $country]);
    }
}
