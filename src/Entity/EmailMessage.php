<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

namespace General\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation;
use Zend\Math\Rand;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="email_message")
 * @ORM\Entity
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("email_message")
 */
class EmailMessage extends EntityAbstract
{
    /**
     * @ORM\Column(name="email_message_id",type="integer",length=10,nullable=false)
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
     * @var int
     */
    private $identifier;
    /**
     * @ORM\Column(name="date_created", type="datetime",nullable=false)
     * @Gedmo\Timestampable(on="create")
     *
     * @var \datetime
     */
    private $dateCreated;
    /**
     * @ORM\ManyToOne(targetEntity="Contact\Entity\Contact", inversedBy="emailMessage", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="contact_id", nullable=true)
     * })
     * @Annotation\Exclude()
     *
     * @var \Contact\Entity\Contact|null
     */
    private $contact;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Contact", inversedBy="emailMessage", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="mailing_contact_id", referencedColumnName="mailing_contact_id", nullable=true)
     * })
     * @Annotation\Exclude()
     *
     * @var \Mailing\Entity\Contact|null
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
     * @ORM\Column(name="latest_event",type="string")
     *
     * @var string
     */
    private $latestEvent;
    /**
     * @ORM\Column(name="date_latest_event",type="datetime")
     *
     * @var \DateTime
     */
    private $dateLatestEvent;

    /**
     * EmailMessage constructor.
     */
    public function __construct()
    {
        $this->identifier          = sha1(Rand::getString(30));
        $this->event               = new ArrayCollection();
        $this->amountOfAttachments = 0;
    }

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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return EmailMessage
     */
    public function setId(int $id): EmailMessage
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param int $identifier
     *
     * @return EmailMessage
     */
    public function setIdentifier($identifier): EmailMessage
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return \Contact\Entity\Contact|null
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param \Contact\Entity\Contact|null $contact
     *
     * @return EmailMessage
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return \Mailing\Entity\Contact|null
     */
    public function getMailingContact()
    {
        return $this->mailingContact;
    }

    /**
     * @param \Mailing\Entity\Contact|null $mailingContact
     *
     * @return EmailMessage
     */
    public function setMailingContact($mailingContact)
    {
        $this->mailingContact = $mailingContact;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     *
     * @return EmailMessage
     */
    public function setEmailAddress(string $emailAddress): EmailMessage
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return EmailMessage
     */
    public function setSubject(string $subject): EmailMessage
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return EmailMessage
     */
    public function setMessage(string $message): EmailMessage
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmountOfAttachments(): int
    {
        return $this->amountOfAttachments;
    }

    /**
     * @param int $amountOfAttachments
     *
     * @return EmailMessage
     */
    public function setAmountOfAttachments(int $amountOfAttachments): EmailMessage
    {
        $this->amountOfAttachments = $amountOfAttachments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     *
     * @return EmailMessage
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return string
     */
    public function getLatestEvent()
    {
        return $this->latestEvent;
    }

    /**
     * @param string $latestEvent
     *
     * @return EmailMessage
     */
    public function setLatestEvent(string $latestEvent): EmailMessage
    {
        $this->latestEvent = $latestEvent;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     *
     * @return EmailMessage
     */
    public function setDateCreated(\DateTime $dateCreated): EmailMessage
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateLatestEvent()
    {
        return $this->dateLatestEvent;
    }

    /**
     * @param \DateTime $dateLatestEvent
     *
     * @return EmailMessage
     */
    public function setDateLatestEvent(\DateTime $dateLatestEvent): EmailMessage
    {
        $this->dateLatestEvent = $dateLatestEvent;

        return $this;
    }
}