<?php
/**
 * Email class
 *
 */
namespace General;

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use General\Service\GeneralService;

/**
 * Class Email
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
 * @method array getTo()
 * @method array setTo($to)
 * @method array getCc()
 * @method array setCc($cc)
 * @method array getBcc()
 * @method array setBcc($bcc)
 * @method string getFrom()
 * @method void setFrom($from)
 * @method string getFromName()
 * @method void setFromName($fromName)
 * @method string getReplyTo()
 * @method void setReplyTo($replyTo)
 * @method string getReplyToName()
 * @method void setDeeplink($deeplink)
 * @method void setSubject($subject)
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
    protected $lastName;
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
     * @var GeneralService
     */
    protected $generalService;

    /**
     * __construct
     *
     * Set default options
     *
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
            $this->to[$var->getEmail()] = $var->getDisplayName();
        } else {
            $this->to[$var] = $user;
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
                $key   = $this->_underscore(substr($method, 3));
                $index = isset($args[0]) ? $args[0] : null;
                //Try to find a property
                if (!$index && isset($this->$key)) {
                    return $this->$key;
                }

                return "";
            case 'set':
                $key        = $this->_underscore(substr($method, 3));
                $result     = isset($args[0]) ? $args[0] : null;
                $this->$key = $result;

                return $result;
        }
        throw new \Exception("Invalid method " . $method);
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
    protected function _underscore($name)
    {
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));

        return $result;
    }

    /**
     * Export all class properties to array
     * E.g.: ["full_name"] => "Ignacio Pascual"
     *
     * Check all variables if exists the method getVariable() then is added to the Array.
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
     * @param ContactService $contactService
     */
    public function setContactService(ContactService $contactService)
    {
        $this->fullname     = $contactService->parseFullName();
        $this->firstname    = $contactService->getContact()->getFirstName();
        $this->lastName     = $contactService->parseLastName();
        $this->organisation = $contactService->parseOrganisation();
        $this->country      = $contactService->parseCountry();
        $this->attention    = $contactService->parseAttention();
    }
}
