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

/**
 * Entity for the General.
 *
 * @ORM\Table(name="email_message")
 * @ORM\Entity(repositoryClass="General\Repository\EmailMessage")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectProperty")
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
     * @ORM\Column(name="cc",type="string", nullable=true)
     *
     * @var string
     */
    private $cc;
    /**
     * @ORM\Column(name="bcc",type="string", nullable=true)
     *
     * @var string
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
        $this->identifier = sha1(Rand::getString(30));
        $this->event = new ArrayCollection();
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

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(?string $cc): EmailMessage
    {
        $this->cc = $cc;
        return $this;
    }

    public function getBcc(): ?string
    {
        return $this->bcc;
    }

    public function setBcc(?string $bcc): EmailMessage
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
}
