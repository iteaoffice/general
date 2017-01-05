<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

namespace General\Controller;

use General\Entity\EmailMessageEvent;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;

/**
 * Class EmailController
 *
 * @package General\Controller
 */
class EmailController extends GeneralAbstractController
{
    /**
     * @return JsonModel
     */
    public function eventAction()
    {
        $data = Json::decode($this->getRequest()->getContent());

        /**
         * Try to find the email message, if this cannot be found, short circuit it
         */
        $emailMessage = $this->getGeneralService()->findEmailMessageByIdentifier($data->CustomID);
        if (is_null($emailMessage)) {
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
