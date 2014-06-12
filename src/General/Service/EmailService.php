<?php
/**
 * A template based email system
 *
 * Supports the sending of multipart txt/html emails based on templates
 *
 */
namespace General\Service;

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use General\Email as Email;
use General\Entity\WebInfo;
use Mailing\Entity\Mailing;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\ServiceManager\ServiceManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class EmailService
 * @package General\Service
 */
class EmailService
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var ServiceManager;
     */
    protected $sm;
    /**
     * @var TwigRenderer
     */
    protected $renderer;
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var WebInfo
     */
    protected $template;
    /**
     * @var Mailing
     */
    protected $mailing;
    /**
     * @var ContactService
     */
    protected $contactService;
    /**
     * @var array
     */
    protected $templateVars = [];

    /**
     * __construct
     *
     * Set default options
     *
     */
    public function __construct($config, $sm)
    {
        $this->config         = $config;
        $this->sm             = $sm;
        $this->renderer       = $this->sm->get('ZfcTwigRenderer');
        $this->generalService = $this->sm->get('general_general_service');
    }

    /**
     *
     * Create a new email
     *
     * @param array $data
     *
     * @return Email
     */
    public function create($data = [])
    {
        return new Email($data);
    }

    /**
     * Send the email
     *
     * @param $email
     *
     */
    public function send($email)
    {
        if (is_null($this->mailing)) {
            $message = $this->prepare($email);
        } else {
            $message = $this->prepareMailing($email);
        }

        //Send email
        if ($message && $this->config["active"]) {
            // Server SMTP config
            $transport = new SendmailTransport();
            // Relay SMTP
            if ($this->config["relay"]["active"]) {
                $transport       = new SmtpTransport();
                $transportConfig = array(
                    'name'              => "DebraNova_General_Email",
                    'host'              => $this->config["relay"]["host"],
                    'connection_class'  => 'login',
                    'connection_config' => array(
                        'username' => $this->config["relay"]["username"],
                        'password' => $this->config["relay"]["password"]
                    )
                );
                // Add port
                if ($this->config["relay"]["port"]) {
                    $transportConfig["port"] = $this->config["relay"]["port"];
                }
                // Add ssl
                if ($this->config["relay"]["ssl"]) {
                    $transportConfig["connection_config"]["ssl"] = $this->config["relay"]["ssl"];
                }
                $options = new SmtpOptions($transportConfig);
                $transport->setOptions($options);
            }

            $transport->send($message);
        }
    }

    /**
     * Prepare email to send.
     */
    private function prepare(Email $email)
    {
        if (is_null($this->template)) {
            return new \InvalidArgumentException("There is no template set");
        }

        //Template Variables
        $this->templateVars = array_merge($this->config["template_vars"], $email->toArray());

        //If not layout, use default
        if (!$email->getHtmlLayoutName()) {
            $email->setHtmlLayoutName($this->config["defaults"]["html_layout_name"]);
        }

        //If not recipient, send to admin
        if (count($email->getTo()) === 0) {
            $email->addTo($this->config["emails"]["admin"]);
        }

        $contactService = clone $this->sm->get('contact_contact_service');
        foreach ($email->getTo() as $emailAddress => $name) {
            $this->contactService = $contactService->setContact($contactService->findContactByEmail($emailAddress));
        }

        $this->updateTemplateVarsWithContactService();

        /**
         * Overrule the to when we are in development
         */
        if ('development' === DEBRANOVA_ENVIRONMENT) {
            $email->setTo(array($this->config["emails"]["admin"] => $this->config["emails"]["admin"]));
        }

        //If not sender, use default
        if (!$email->getFrom()) {
            $email->setFrom($this->config["defaults"]["from_email"]);
            $email->setFromName($this->config["defaults"]["from_name"]);
        }

        $content = $this->renderContent();

        try {
            $htmlView = $this->renderer->render(
                $email->getHtmlLayoutName(),
                array_merge_recursive(array('content' => $content), $this->templateVars)
            );
        } catch (\Twig_Error_Syntax $e) {
            var_dump($e->getMessage());
        }

        if (!is_null($htmlView)) {
            $email->setHtmlContent($htmlView);
        };

        //Create Zend Message
        $message = new Message();

        //From
        $message->setFrom($email->getFrom(), $email->getFromName());

        //Reply to
        if ($this->config["defaults"]["reply_to"]) {
            $message->addReplyTo($this->config["defaults"]["reply_to"], $this->config["defaults"]["reply_to_name"]);
        }

        if ($email->getReplyTo()) {
            $message->addReplyTo($email->getReplyTo(), $email->getReplyToName());
        }

        $message = $this->setRecipients($email, $message);

        //Subject. Include the CompanyName in the [[site]] tags
        $message->setSubject(
            str_replace('[site]', $this->config["template_vars"]["company"], $this->template->getSubject())
        );

        $htmlContent       = new MimePart($email->getHtmlContent());
        $htmlContent->type = "text/html";

        $textContent       = new MimePart($email->getTextContent());
        $textContent->type = 'text/plain';

        $body = new MimeMessage();
        //        $body->setParts(array($htmlContent, $textContent));
        $body->setParts(array($htmlContent));

        /**
         * Set specific headers
         * https://eu.mailjet.com/docs/emails_headers
         */
        //$message->getHeaders()->addHeaderLine('X-Mailjet-Campaign', $campaign);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-DeduplicateCampaign', $duplicateCampaign);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-TrackOpen', $trackOpen);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-TrackClick', $trackClick);

        $message->setBody($body);

        return $message;
    }

    /**
     * Extract the contactService and include the variables in the template array settings
     */
    public function updateTemplateVarsWithContactService()
    {
        if (!$this->getContactService()->isEmpty()) {
            $this->templateVars['attention']    = $this->getContactService()->parseAttention();
            $this->templateVars['firstname']    = $this->getContactService()->getContact()->getFirstName();
            $this->templateVars['lastname']     = trim(
                $this->getContactService()->getContact()->getMiddleName() .
                ' ' .
                $this->getContactService()->getContact()->getLastName()
            );
            $this->templateVars['fullname']     = $this->getContactService()->parseFullName();
            $this->templateVars['country']      = $this->getContactService()->parseCountry()->getCountry();
            $this->templateVars['organisation'] = $this->getContactService()->parseOrganisation();
        }
    }

    /**
     * @return \Contact\Service\ContactService
     */
    public function getContactService()
    {
        return $this->contactService;
    }

    /**
     * @param \Contact\Service\ContactService $contactService
     */
    public function setContactService($contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Render the content twig-wise
     *
     * @return null|string
     */
    private function renderContent()
    {
        /**
         * Clone the twigRenderer and overrule to loader to be a string
         */
        $twigRenderer = new \Twig_Environment(new \Twig_Loader_String());

        return $twigRenderer->render(
            $this->createTwigTemplate($this->template->getContent()),
            $this->templateVars
        );
    }

    /**
     * @param $content
     *
     * @return string
     */
    protected function createTwigTemplate($content)
    {
        return preg_replace(
            array(
                '~\[(.*?)\]~'
            ),
            array
            (
                "{{ $1|raw }}"
            ),
            $content
        );
    }

    /**
     * Set the recipients to the email
     *
     * @param $email
     * @param $message
     *
     * @return Message
     */
    public function setRecipients(Email $email, Message $message)
    {
        //To recipients
        foreach ($email->getTo() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $message->addTo($contact->getEmail(), $contact);
            } else {
                $message->addTo($emailAddress, $contact);
            }
        }

        //Cc recipients
        foreach ($email->getCc() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $message->addCc($contact->getEmail(), $contact);
            } else {
                $message->addCc($emailAddress, $contact);
            }
        }

        //Bcc recipients
        foreach ($email->getBcc() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $message->addBcc($contact->getEmail(), $contact);
            } else {
                $message->addBcc($emailAddress, $contact);
            }
        }

        return $message;
    }

    /**
     * Prepare email to send.
     */
    private function prepareMailing(Email $email)
    {
        //Template Variables
        $this->templateVars = array_merge($this->config["template_vars"], $email->toArray());

        //If not layout, use default
        if (!$email->getHtmlLayoutName()) {
            $email->setHtmlLayoutName($this->config["defaults"]["html_layout_name"]);
        }

        //If not recipient, send to admin
        if (count($email->getTo()) === 0) {
            $email->addTo($this->config["emails"]["admin"]);
        }

        foreach ($email->getTo() as $emailAddress => $recipient) {
            $this->getContactService()->findContactByEmail($emailAddress);
        }

        $this->updateTemplateVarsWithContactService();

        /**
         * Overrule the to when we are in development
         */
        if ('development' === DEBRANOVA_ENVIRONMENT) {
            $email->setTo(array($this->config["emails"]["admin"] => $this->config["emails"]["admin"]));
        }

        $email->setFrom($this->getMailing()->getSender()->getSender());
        $email->setFromName($this->getMailing()->getSender()->getEmail());

        $content = $this->renderMailingContent();

        $htmlView = $this->renderer->render(
            $this->getMailing()->getTemplate()->getTemplate(),
            array_merge_recursive(array('content' => $content), $this->templateVars)
        );

        $textView = $this->renderer->render(
            'plain',
            array_merge_recursive(array('content' => $content), $this->templateVars)
        );

        if (!is_null($textView)) {
            $email->setTextContent(strip_tags($textView));
        };

        if (!is_null($htmlView)) {
            $email->setHtmlContent($htmlView);
        };

        //Create Zend Message
        $message = new Message();

        //From
        $message->setFrom($this->getMailing()->getSender()->getEmail(), $this->getMailing()->getSender()->getSender());

        //Set the other recipients
        $message = $this->setRecipients($email, $message);

        //Subject. Include the CompanyName in the [[site]] tags
        $message->setSubject($this->getMailing()->getMailSubject());

        $htmlContent       = new MimePart($email->getHtmlContent());
        $htmlContent->type = "text/html";

        //        $textContent       = new MimePart($email->getTextContent());
        //        $textContent->type = 'text/plain';

        $body = new MimeMessage();
        //$body->setParts(array($htmlContent, $textContent));
        $body->setParts(array($htmlContent));

        /**
         * Set specific headers
         * https://eu.mailjet.com/docs/emails_headers
         */
        $message->getHeaders()->addHeaderLine(
            'X-Mailjet-Campaign',
            DEBRANOVA_HOST . '-mailing-' . $this->getMailing()->getId()
        );
        //$message->getHeaders()->addHeaderLine('X-Mailjet-DeduplicateCampaign', $duplicateCampaign);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-TrackOpen', $trackOpen);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-TrackClick', $trackClick);

        $message->setBody($body);

        return $message;
    }

    /**
     * @return \Mailing\Entity\Mailing
     */
    public function getMailing()
    {
        if (is_null($this->mailing)) {
            $this->setMailing($this->mailing);
        }

        return $this->mailing;
    }

    /**
     * @param \Mailing\Entity\Mailing $mailing
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
    }

    /**
     * Render the content twig-wise
     *
     * @return null|string
     */
    private function renderMailingContent()
    {
        /**
         * Replace first the content of the mailing with the required (new) shorttags
         */
        $content = preg_replace(
            array(
                '~\[parent::getContact\(\)::firstname\]~',
                '~\[parent::getContact\(\)::parseLastname\(\)\]~',
                '~\[parent::getContact\(\)::parseFullname\(\)\]~',
                '~\[parent::getContact\(\)::getContactOrganisation\(\)::parseOrganisationWithBranch\(\)\]~',
                '~\[parent::getContact\(\)::country\]~'
            ),
            array(
                "[firstname]",
                "[lastname]",
                "[fullname]",
                "[organisation]",
                "[country]"
            ),
            $this->getMailing()->getMailHtml()
        );

        /**
         * Clone the twigRenderer and overrule to loader to be a string
         */
        $twigRenderer = clone $this->renderer->getEngine();
        $twigRenderer->setLoader(new \Twig_Loader_String());

        return $twigRenderer->render(
            $this->createTwigTemplate($content),
            $this->templateVars
        );
    }

    /**
     * @param $templateName
     *
     * @return EmailService
     *
     * @throws \Exception
     */
    public function setTemplate($templateName)
    {
        $this->template = $this->generalService->findWebInfoByInfo($templateName);

        if (is_null($this->template)) {
            throw new \InvalidArgumentException(sprintf('There is no no template with info "%s"', $templateName));
        }

        return $this;
    }
}
