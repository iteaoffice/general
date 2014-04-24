<?php
/**
 * Email class
 *
 */
namespace dd;

use Contact\Entity\Contact;
use General\Service\GeneralService;
use Contact\Service\ContactService;

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
 * @method void setTo($to)
 * @method array getCc()
 * @method void setCc($cc)
 * @method array getBcc()
 * @method void setBcc($bcc)
 * @method string getFrom()
 * @method void setFrom($from)
 * @method string getFromName()
 * @method void setFromName($fromName)
 * @method string getReplyTo()
 * @method void setReplyTo($replyTo)
 * @method string getReplyToName()
 * @method void setReplyToName($replyToName)
 */
class Email
{


















    /**
     * __construct
     *
     * Set default options
     *
     */
    public function __construct($data = array())
    {
        $this->setProperties($data);
    }

    /**
     * To recipients
     */
    protected $to = array();
    /**
     * Cc recipients
     */
    protected $cc = array();
    /**
     * Bcc recipients
     */
    protected $bcc = array();
    /**
     * Subject
     */
    protected $subject = "";
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var WebInfo
     */
    protected $template;

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
        if (is_object($var)) {
            //to[email] = UserObject
            $this->cc[$var->getEmail()] = $var;
        } else {
            //to[email] = user_name
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
        if (is_object($var)) {
            //to[email] = UserObject
            $this->bcc[$var->getEmail()] = $var;
        } else {
            //to[email] = user_name
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
     * Export all class properties to array
     * E.g.: ["full_name"] => "Ignacio Pascual"
     *
     * Check all variables if exists the method getVariable() then is added to the Array.
     *
     */
    public function toArray()
    {
        $values = array();
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
