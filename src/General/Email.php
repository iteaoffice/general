<?php
/**
 *
 */
namespace General;

use Contact\Entity\Contact;
use Contact\Entity\Selection;
use Contact\Service\ContactService;

/**
 * Class Email
 *
 * @package General
 *
 * @method string getHtmlLayoutName()
 * @method void setHtmlLayoutName($name)
 * @method string getTextLayoutName()
 * @method void setTextLayoutName($name)
 * @method string getHtmlContent()
 * @method void setHtmlContent($htmlContent)
 * @method string getTextContent()
 * @method void setTextContent($textContent)
 * @method string getReplyTo()
 * @method void setReplyTo($replyTo)
 * @method string getReplyToName()
 * @method void setDeeplink($deeplink)
 * @method void setReplyToName($replyToName)
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
    protected $fromName;
    /**
     * To recipients
     */
    protected $to = [];
    /**
     * Cc recipients
     */
    protected $cc = [];
    /**
     * Bcc recipients
     */
    protected $bcc = [];
    /**
     * Subject
     */
    protected $subject = "";
    /**
     * Message
     */
    protected $message = "";

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->setProperties($data);
    }

    /**
     * Set all values from $data to each property.
     *
     * @param $data array set
     *
     * @return $this
     */
    public function setProperties(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Add TO recipient
     *
     * @param      $var
     * @param null $user
     */
    public function addTo($var, $user = null)
    {
        if ($var instanceof Contact) {
            $this->to[$var->getEmail()] = $var;
        } else {
            $this->to[$var] = is_null($user) ? $var : $user;
        }
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * @param Contact $contact
     */
    public function setFromContact(Contact $contact)
    {
        $this->from = $contact->getEmail();
        $this->fromName = $contact->getDisplayName();
    }

    /**
     * @param array $to
     */
    public function setTo(array $to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param mixed $cc
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    /**
     * @return mixed
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param mixed $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param Selection      $selection
     * @param ContactService $contactService
     */
    public function addSelection(Selection $selection, ContactService $contactService)
    {
        foreach ($contactService->findContactsInSelection($selection) as $contact) {
            $this->addTo($contact);
        }
    }

    /**
     * Add CC recipient
     *
     * @param      $var
     * @param null $user
     */
    public function addCc($var, $user = null)
    {
        if ($var instanceof Contact) {
            $this->cc[$var->getEmail()] = $var->getDisplayName();
        } else {
            $this->cc[$var] = $user;
        }
    }

    /**
     * Add BCC recipient
     *
     * @param      $var
     * @param null $user
     */
    public function addBcc($var, $user = null)
    {
        if ($var instanceof Contact) {
            $this->bcc[$var->getEmail()] = $var->getDisplayName();
        } else {
            $this->bcc[$var] = $user;
        }
    }

    /**
     * Set/Get Magic function
     *
     * Set
     * $user->setSubject("This is a test")
     *
     * Get
     * $user->getName();
     * $user->getPhonenumbers(3);
     *
     * @param $method
     * @param $args
     *
     * @return null|string
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->underscore(substr($method, 3));
                $index = isset($args[0]) ? $args[0] : null;

                if (!$index && isset($this->$key)) {
                    return $this->$key;
                }

                return "";
            case 'set':
                $key = $this->underscore(substr($method, 3));
                $result = isset($args[0]) ? $args[0] : null;

                //Only keep the item when it can be set to a toString
                if (
                    (!is_array($result)) &&
                    ((!is_object($result) && settype($result, 'string') !== false) ||
                        (is_object($result) && method_exists($result, '__toString')))
                ) {
                    $this->$key = (string)$result;

                    return (string)$result;

                }

        }
        throw new \Exception("Invalid method " . $method);
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
     * Converts field names for setters and getters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unnecessary preg_replace
     *
     * @param string $name
     *
     * @return string
     */
    protected function underscore($name)
    {
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));

        return $result;
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
}
