<?php
/**
 *
 */

declare(strict_types=1);

namespace General;

use Contact\Entity\Contact;
use Contact\Entity\Selection;
use Contact\Service\ContactService;

/**
 * Class Email.
 *
 *
 * @method string getHtmlLayoutName()
 * @method void setHtmlLayoutName($name)
 * @method string getTextLayoutName()
 * @method void setTextLayoutName($name)
 * @method string getHtmlContent()
 * @method void setHtmlContent($htmlContent)
 * @method string getTextContent()
 * @method void setTextContent($textContent)
 * @method void setCode($code)
 * @method void setUrl($url)
 * @method void setProject($project)
 * @method void setProjectLeader($projectLeader)
 * @method void setProjectLeaderOrganisation($projectLeaderOrganisation)
 * @method void setProjectLeaderEmail($projectLeaderEmail)
 * @method void setProjectLeaderCountry($projectLeaderEmail)
 * @method void setFullname($fullname)
 * @method void setContact($contact)
 * @method void setOrganisation($contact)
 * @method void setCountry($contact)
 * @method void setIdea($idea)
 * @method void setComment($comment)
 * @method void setCommenter($commenter)
 */
class Email
{
    /**
     * @var string
     */
    protected $fullname;
    /**
     * @var string
     */
    protected $firstname;
    /**
     * @var string
     */
    protected $lastname;
    /**
     * @var string
     */
    protected $organisation;
    /**
     * @var string
     */
    protected $country;
    /**
     * @var string
     */
    protected $attention;
    /**
     * @var string
     */
    protected $from;
    /**
     * @var string
     */
    protected $replyTo = null;
    /**
     * @var string
     */
    protected $replyToName = null;
    /**
     * @var string
     */
    protected $fromName;
    /**
     * @var string
     */
    protected $unsubscribe;
    /**
     * @var string
     */
    protected $deeplink;
    /**
     * To recipients.
     */
    protected $to = [];
    /**
     * Cc recipients.
     */
    protected $cc = [];
    /**
     * Bcc recipients.
     */
    protected $bcc = [];
    /**
     * Subject.
     */
    protected $subject = '';
    /**
     * Message.
     */
    protected $message = '';
    /**
     * Value to check if a mail is sent personal or send to everyone in the to if set to false.
     *
     * @var bool
     */
    protected $personal = true;
    /**
     * @var array
     */
    protected $config;

    /**
     * Email constructor.
     * @param array $data
     * @param array $config
     */
    public function __construct(array $data, array $config)
    {
        $this->setProperties($data);
        $this->setConfig($config);
    }

    /**
     * @param array $data
     *
     * @return Email
     */
    public function setProperties(array $data): Email
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param $from
     *
     * @return Email
     */
    public function setFrom(string $from): Email
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName): void
    {
        $this->fromName = $fromName;
    }

    /**
     * @param Contact $contact
     */
    public function setFromContact(Contact $contact): void
    {
        //If the domain of the contact email is the same as the default email
        [$name, $defaultDomain] = explode('@', $this->config['defaults']['from_email']);
        [$contactName, $contactDomain] = explode('@', $contact->getEmail());

        //When the domains are the same, use the sender without a trick
        if ($defaultDomain === $contactDomain) {
            $this->from = $contact->getEmail();
            $this->fromName = $contact->getDisplayName();
        } else {
            $this->from = $this->config['defaults']['from_email'];
            $this->fromName = sprintf(
                '%s (via %s)',
                $contact->getDisplayName(),
                $this->config['defaults']['from_name']
            );
        }

        $this->replyTo = $contact->getEmail();
        $this->replyToName = $contact->getDisplayName();
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param array $to
     */
    public function setTo(array $to)
    {
        $this->to = $to;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param array $cc
     *
     * @return Email
     */
    public function setCc(array $cc): Email
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @param array $bcc
     *
     * @return Email
     */
    public function setBcc(array $bcc): Email
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     *
     * @return Email
     */
    public function setMessage(?string $message): Email
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param Selection $selection
     * @param ContactService $contactService
     */
    public function addSelection(Selection $selection, ContactService $contactService)
    {
        foreach ($contactService->findContactsInSelection($selection) as $contact) {
            $this->addTo($contact);
        }
    }

    /**
     * Add TO recipient.
     *
     * @param      $var
     * @param null $user
     */
    public function addTo($var, $user = null): void
    {
        if ($var instanceof Contact) {
            $this->to[$var->getEmail()] = $var;
        } else {
            $this->to[$var] = $user ?? $var;
        }
    }

    /**
     * Add CC recipient.
     *
     * @param      $var
     * @param null $user
     */
    public function addCc($var, $user = null): void
    {
        if ($var instanceof Contact) {
            $this->cc[$var->getEmail()] = $var->getDisplayName();
        } else {
            $this->cc[$var] = $user ?? $var;
        }
    }

    /**
     * Add BCC recipient.
     *
     * @param      $var
     * @param null $user
     */
    public function addBcc($var, $user = null): void
    {
        if ($var instanceof Contact) {
            $this->bcc[$var->getEmail()] = $var->getDisplayName();
        } else {
            $this->bcc[$var] = $user ?? $var;
        }
    }

    /**
     * @param $method
     * @param $args
     *
     * @return string
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->underscore(substr($method, 3));
                $index = $args[0] ?? null;

                if (!$index && isset($this->$key)) {
                    return $this->$key;
                }

                return '';
            case 'set':
                $key = $this->underscore(substr($method, 3));
                $result = $args[0] ?? null;

                //Only keep the item when it can be set to a toString
                if ((!\is_array($result))
                    && ((!\is_object($result) && \settype($result, 'string') !== false)
                        || (\is_object($result) && \method_exists($result, '__toString')))
                ) {
                    $this->$key = (string)$result;

                    return (string)$result;
                }
        }
        throw new \Exception("Invalid method " . $method);
    }

    /**
     * Converts field names for setters and getters.
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unnecessary preg_replace
     *
     * @param string $name
     *
     * @return string
     */
    protected function underscore($name): string
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     *
     */
    public function toArray()
    {
        $values = [];
        foreach (get_object_vars($this) as $key => $val) {
            $values[$key] = $val;
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return Email
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @return string
     */
    public function getReplyToName()
    {
        return $this->replyToName;
    }

    /**
     * @param string $replyToName
     */
    public function setReplyToName($replyToName)
    {
        $this->replyToName = $replyToName;
    }

    /**
     * @return bool
     */
    public function isPersonal(): bool
    {
        return $this->personal;
    }

    /**
     * @param boolean $personal
     *
     * @return Email
     */
    public function setPersonal($personal): bool
    {
        $this->personal = $personal;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnsubscribe()
    {
        return $this->unsubscribe;
    }

    /**
     * @param string $unsubscribe
     *
     * @return Email
     */
    public function setUnsubscribe($unsubscribe)
    {
        $this->unsubscribe = $unsubscribe;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeeplink()
    {
        return $this->deeplink;
    }

    /**
     * @param string $deeplink
     *
     * @return Email
     */
    public function setDeeplink($deeplink)
    {
        $this->deeplink = $deeplink;

        return $this;
    }
}
