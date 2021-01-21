<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use General\Controller\Plugin\GetFilter;
use General\Entity\EmailMessage;
use General\Entity\EmailMessageEvent;
use General\Form\EmailFilter;
use General\Service\GeneralService;
use Laminas\Json\Json;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Paginator\Paginator;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

/**
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class EmailController extends AbstractActionController
{
    private GeneralService $generalService;
    private EntityManager $entityManager;

    public function __construct(
        GeneralService $generalService,
        EntityManager $entityManager
    ) {
        $this->generalService = $generalService;
        $this->entityManager = $entityManager;
    }

    public function listAction(): ViewModel
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getFilter();
        $contactQuery = $this->generalService
            ->findFiltered(EmailMessage::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new EmailFilter($this->entityManager);
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
        $emailMessage = $this->generalService->find(EmailMessage::class, (int)$this->params('id'));
        if (null === $emailMessage) {
            return $this->notFoundAction();
        }

        return new ViewModel(['emailMessage' => $emailMessage]);
    }


    public function eventAction(): JsonModel
    {
        $events = Json::decode($this->getRequest()->getContent());

        foreach ($events as $data) {
            if (! isset($data->CustomID)) {
                continue;
            }

            /**
             * Try to find the email message, if this cannot be found, short circuit it
             */
            $emailMessage = $this->generalService->findEmailMessageByIdentifier($data->CustomID);
            if (null === $emailMessage) {
                continue;
            }

            //Create a new EmailEvent
            $emailMessageEvent = new EmailMessageEvent();
            $emailMessageEvent->setEmail($data->email);

            $dateTime = new DateTime();
            $emailMessageEvent->setTime($dateTime->setTimestamp($data->time));
            $emailMessageEvent->setEmailMessage($emailMessage);
            $emailMessageEvent->setEvent($data->event);
            $emailMessageEvent->setMessageId($data->MessageID);
            if (isset($data->customcampaign)) {
                $emailMessageEvent->setCampaign($data->customcampaign);
            }
            if (isset($data->smtp_reply)) {
                $emailMessageEvent->setSmtpReply($data->smtp_reply);
            }
            if (isset($data->url)) {
                $emailMessageEvent->setUrl($data->url);
            }
            if (isset($data->ip)) {
                $emailMessageEvent->setIp($data->ip);
            }
            if (isset($data->agent)) {
                $emailMessageEvent->setAgent($data->agent);
            }
            if (isset($data->error)) {
                $emailMessageEvent->setError($data->error);
            }
            if (isset($data->error_related_to)) {
                $emailMessageEvent->setErrorRelatedTo($data->error_related_to);
            }
            if (isset($data->source)) {
                $emailMessageEvent->setSource($data->source);
            }

            $this->generalService->save($emailMessageEvent);

            //Store the latest status in the emailMessage
            $emailMessage->setLatestEvent($data->event);
            $emailMessage->setDateLatestEvent(new DateTime());
            $this->generalService->save($emailMessage);
        }

        return new JsonModel();
    }
}
