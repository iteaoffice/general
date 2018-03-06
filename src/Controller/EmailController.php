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
use General\Entity\EmailMessage;
use General\Entity\EmailMessageEvent;
use General\Form\EmailFilter;
use Zend\Json\Json;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class EmailController
 *
 * @package General\Controller
 */
class EmailController extends GeneralAbstractController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $filterPlugin = $this->getGeneralFilter();
        $contactQuery = $this->getGeneralService()
            ->findEntitiesFiltered(EmailMessage::class, $filterPlugin->getFilter());

        $paginator = new Paginator(new PaginatorAdapter(new ORMPaginator($contactQuery, false)));
        $paginator::setDefaultItemCountPerPage(($page === 'all') ? PHP_INT_MAX : 20);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(ceil($paginator->getTotalItemCount() / $paginator::getDefaultItemCountPerPage()));

        $form = new EmailFilter($this->getEntityManager());
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
    public function viewAction()
    {
        $emailMessage = $this->getGeneralService()
            ->findEntityById(EmailMessage::class, $this->params('id'));
        if (null === $emailMessage) {
            return $this->notFoundAction();
        }

        return new ViewModel(['emailMessage' => $emailMessage]);
    }


    /**
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eventAction()
    {
        $data = Json::decode($this->getRequest()->getContent());

        if (!isset($data->CustomID)) {
            return new JsonModel();
        }

        /**
         * Try to find the email message, if this cannot be found, short circuit it
         */
        $emailMessage = $this->getGeneralService()->findEmailMessageByIdentifier($data->CustomID);
        if (null === $emailMessage) {
            return new JsonModel();
        }

        //Create a new EmailEvent
        $emailMessageEvent = new EmailMessageEvent();
        $emailMessageEvent->setEmail($data->email);

        $dateTime = new \DateTime();
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

        $this->getGeneralService()->newEntity($emailMessageEvent);

        //Store the latest status in the emailMessage
        $emailMessage->setLatestEvent($data->event);
        $emailMessage->setDateLatestEvent(new \DateTime());
        $this->getGeneralService()->updateEntity($emailMessage);

        return new JsonModel();
    }
}
