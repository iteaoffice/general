<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
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
     * @var EmailMessage
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
     * @var DateTime
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
     * @ORM\Column(name="url",type="string",length=1000, nullable=true)
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
     * @ORM\Column(name="agent",type="string",length=1000,nullable=true)
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): EmailMessageEvent
    {
        $this->id = $id;
        return $this;
    }

    public function getEmailMessage(): ?EmailMessage
    {
        return $this->emailMessage;
    }

    public function setEmailMessage(?EmailMessage $emailMessage): EmailMessageEvent
    {
        $this->emailMessage = $emailMessage;
        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): EmailMessageEvent
    {
        $this->event = $event;
        return $this;
    }

    public function getTime(): ?DateTime
    {
        return $this->time;
    }

    public function setTime(?DateTime $time): EmailMessageEvent
    {
        $this->time = $time;
        return $this;
    }

    public function getMessageId(): ?int
    {
        return $this->messageId;
    }

    public function setMessageId(?int $messageId): EmailMessageEvent
    {
        $this->messageId = $messageId;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): EmailMessageEvent
    {
        $this->email = $email;
        return $this;
    }

    public function getCampaign(): ?string
    {
        return $this->campaign;
    }

    public function setCampaign(?string $campaign): EmailMessageEvent
    {
        $this->campaign = $campaign;
        return $this;
    }

    public function getSmtpReply(): ?string
    {
        return $this->smtp_reply;
    }

    public function setSmtpReply(?string $smtp_reply): EmailMessageEvent
    {
        $this->smtp_reply = $smtp_reply;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): EmailMessageEvent
    {
        $this->url = $url;
        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): EmailMessageEvent
    {
        $this->ip = $ip;
        return $this;
    }

    public function getAgent(): ?string
    {
        return $this->agent;
    }

    public function setAgent(?string $agent): EmailMessageEvent
    {
        $this->agent = $agent;
        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): EmailMessageEvent
    {
        $this->error = $error;
        return $this;
    }

    public function getErrorRelatedTo(): ?string
    {
        return $this->errorRelatedTo;
    }

    public function setErrorRelatedTo(?string $errorRelatedTo): EmailMessageEvent
    {
        $this->errorRelatedTo = $errorRelatedTo;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): EmailMessageEvent
    {
        $this->source = $source;
        return $this;
    }
}
