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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Laminas\Form\Annotation;
use Laminas\Math\Rand;
use Mailing\Entity\Contact;
use Mailing\Entity\Sender;
use Mailing\Entity\Template;

/**
 * @ORM\Table(name="email_message")
 * @ORM\Entity(repositoryClass="General\Repository\EmailMessage")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("email_message")
 */
class EmailMessage extends AbstractEntity
{
    /**
     * @ORM\Column(name="email_message_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="identifier",type="string",nullable=false, unique=true)
     *
     * @var string
     */
    private $identifier;
    /**
     * @ORM\Column(name="date_created", type="datetime",nullable=false)
     * @Gedmo\Timestampable(on="create")
     *
     * @var DateTime
     */
    private $dateCreated;
    /**
     * @ORM\ManyToOne(targetEntity="Contact\Entity\Contact", inversedBy="emailMessage", cascade={"persist"})
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="contact_id", nullable=true)
     * @Annotation\Exclude()
     *
     * @var \Contact\Entity\Contact
     */
    private $contact;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Contact", inversedBy="emailMessage", cascade={"persist"})
     * @ORM\JoinColumn(name="mailing_contact_id", referencedColumnName="mailing_contact_id", nullable=true)
     * @Annotation\Exclude()
     *
     * @var Contact
     */
    private $mailingContact;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Template", inversedBy="emailMessage", cascade={"persist"})
     * @ORM\JoinColumn(name="mailtemplate_id", referencedColumnName="mailtemplate_id", nullable=true)
     *
     * @var Template
     */
    private $template;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Sender", inversedBy="emailMessage", cascade={"persist"})
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="sender_id", nullable=true)
     *
     * @var Sender
     */
    private $sender;
    /**
     * @ORM\Column(name="email_address",type="string")
     *
     * @var string
     */
    private $emailAddress;
    /**
     * @ORM\Column(name="subject",type="string")
     *
     * @var string
     */
    private $subject;
    /**
     * @ORM\Column(name="`to`",type="array", nullable=true)
     *
     * @var array
     */
    private $to;
    /**
     * @ORM\Column(name="`cc`",type="array", nullable=true)
     *
     * @var array
     */
    private $cc;
    /**
     * @ORM\Column(name="`bcc`",type="array", nullable=true)
     *
     * @var array
     */
    private $bcc;
    /**
     * @ORM\Column(name="message",type="text")
     *
     * @var string
     */
    private $message;
    /**
     * @ORM\Column(name="amount_of_attachments",type="smallint")
     *
     * @var int
     */
    private $amountOfAttachments;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\EmailMessageEvent", mappedBy="emailMessage", cascade={"persist","remove"})
     */
    private $event;
    /**
     * @ORM\Column(name="latest_event",type="string", nullable=true)
     *
     * @var string
     */
    private $latestEvent;
    /**
     * @ORM\Column(name="date_latest_event",type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $dateLatestEvent;

    public function __construct()
    {
        $this->identifier          = sha1(Rand::getString(30));
        $this->event               = new ArrayCollection();
        $this->amountOfAttachments = 0;
    }

    public function __toString(): string
    {
        return (string)$this->subject;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): EmailMessage
    {
        $this->id = $id;
        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): EmailMessage
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?DateTime $dateCreated): EmailMessage
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getContact(): ?\Contact\Entity\Contact
    {
        return $this->contact;
    }

    public function setContact(?\Contact\Entity\Contact $contact): EmailMessage
    {
        $this->contact = $contact;
        return $this;
    }

    public function getMailingContact(): ?Contact
    {
        return $this->mailingContact;
    }

    public function setMailingContact(?Contact $mailingContact): EmailMessage
    {
        $this->mailingContact = $mailingContact;
        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): EmailMessage
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): EmailMessage
    {
        $this->subject = $subject;
        return $this;
    }

    public function getCc(): ?array
    {
        return $this->cc;
    }

    public function setCc(?array $cc): EmailMessage
    {
        $this->cc = $cc;
        return $this;
    }

    public function getBcc(): ?array
    {
        return $this->bcc;
    }

    public function setBcc(?array $bcc): EmailMessage
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): EmailMessage
    {
        $this->message = $message;
        return $this;
    }

    public function getAmountOfAttachments(): ?int
    {
        return $this->amountOfAttachments;
    }

    public function setAmountOfAttachments(?int $amountOfAttachments): EmailMessage
    {
        $this->amountOfAttachments = $amountOfAttachments;
        return $this;
    }

    public function getEvent(): ?Collection
    {
        return $this->event;
    }

    public function setEvent(?Collection $event): EmailMessage
    {
        $this->event = $event;
        return $this;
    }

    public function getLatestEvent(): ?string
    {
        return $this->latestEvent;
    }

    public function setLatestEvent(?string $latestEvent): EmailMessage
    {
        $this->latestEvent = $latestEvent;
        return $this;
    }

    public function getDateLatestEvent(): ?DateTime
    {
        return $this->dateLatestEvent;
    }

    public function setDateLatestEvent(?DateTime $dateLatestEvent): EmailMessage
    {
        $this->dateLatestEvent = $dateLatestEvent;
        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): EmailMessage
    {
        $this->template = $template;
        return $this;
    }

    public function getSender(): ?Sender
    {
        return $this->sender;
    }

    public function setSender(?Sender $sender): EmailMessage
    {
        $this->sender = $sender;
        return $this;
    }

    public function getTo(): array
    {
        return $this->to;
    }

    public function setTo(array $to): EmailMessage
    {
        $this->to = $to;
        return $this;
    }
}
