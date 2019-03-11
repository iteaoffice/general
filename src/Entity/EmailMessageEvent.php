<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="email_message_event")
 * @ORM\Entity
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("email_message_event")
 */
class EmailMessageEvent extends AbstractEntity
{
    /**
     * @ORM\Column(name="email_message_event_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\EmailMessage", inversedBy="event", cascade={"persist"})
     * @ORM\JoinColumn(name="email_message_id", referencedColumnName="email_message_id")
     *
     * @var \General\Entity\EmailMessage
     */
    private $emailMessage;
    /**
     * @ORM\Column(name="event",type="string",nullable=false)
     *
     * @var string
     */
    private $event;
    /**
     * @ORM\Column(name="time",type="datetime",nullable=false)
     *
     * @var \DateTime
     */
    private $time;
    /**
     * @ORM\Column(name="message_id",type="bigint",nullable=false)
     *
     * @var int
     */
    private $messageId;
    /**
     * @ORM\Column(name="email",type="string",nullable=false)
     *
     * @var string
     */
    private $email;
    /**
     * @ORM\Column(name="campaign",type="string",nullable=false)
     *
     * @var string
     */
    private $campaign;
    /**
     * @ORM\Column(name="smtp_reply",type="string",nullable=true)
     *
     * @var string
     */
    private $smtp_reply;
    /**
     * @ORM\Column(name="url",type="string",nullable=true)
     *
     * @var string
     */
    private $url;
    /**
     * @ORM\Column(name="ip",type="string",nullable=true)
     *
     * @var string
     */
    private $ip;
    /**
     * @ORM\Column(name="agent",type="string",nullable=true)
     *
     * @var string
     */
    private $agent;
    /**
     * @ORM\Column(name="error",type="string",nullable=true)
     *
     * @var string
     */
    private $error;
    /**
     * @ORM\Column(name="error_related_to",type="string",nullable=true)
     *
     * @var string
     */
    private $errorRelatedTo;
    /**
     * @ORM\Column(name="source",type="string",nullable=true)
     *
     * @var string
     */
    private $source;

    /**
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * @param $property
     * @param $value
     *
     * @return void;
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * @param $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return EmailMessageEvent
     */
    public function setId(int $id): EmailMessageEvent
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return EmailMessage
     */
    public function getEmailMessage(): EmailMessage
    {
        return $this->emailMessage;
    }

    /**
     * @param EmailMessage $emailMessage
     *
     * @return EmailMessageEvent
     */
    public function setEmailMessage(EmailMessage $emailMessage): EmailMessageEvent
    {
        $this->emailMessage = $emailMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return EmailMessageEvent
     */
    public function setEvent(string $event): EmailMessageEvent
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     *
     * @return EmailMessageEvent
     */
    public function setTime(\DateTime $time): EmailMessageEvent
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return int
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     *
     * @return EmailMessageEvent
     */
    public function setMessageId(int $messageId): EmailMessageEvent
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return EmailMessageEvent
     */
    public function setEmail(string $email): EmailMessageEvent
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param string $campaign
     *
     * @return EmailMessageEvent
     */
    public function setCampaign(string $campaign): EmailMessageEvent
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * @return string
     */
    public function getSmtpReply()
    {
        return $this->smtp_reply;
    }

    /**
     * @param string $smtp_reply
     *
     * @return EmailMessageEvent
     */
    public function setSmtpReply(string $smtp_reply): EmailMessageEvent
    {
        $this->smtp_reply = $smtp_reply;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return EmailMessageEvent
     */
    public function setUrl(string $url): EmailMessageEvent
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return EmailMessageEvent
     */
    public function setIp(string $ip): EmailMessageEvent
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param string $agent
     *
     * @return EmailMessageEvent
     */
    public function setAgent(string $agent): EmailMessageEvent
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return EmailMessageEvent
     */
    public function setError(string $error): EmailMessageEvent
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorRelatedTo()
    {
        return $this->errorRelatedTo;
    }

    /**
     * @param string $errorRelatedTo
     *
     * @return EmailMessageEvent
     */
    public function setErrorRelatedTo(string $errorRelatedTo): EmailMessageEvent
    {
        $this->errorRelatedTo = $errorRelatedTo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return EmailMessageEvent
     */
    public function setSource(string $source): EmailMessageEvent
    {
        $this->source = $source;

        return $this;
    }
}
