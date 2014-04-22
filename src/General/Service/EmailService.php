<?php
/**
 * A template based email system
 *
 * Supports the sending of multipart txt/html emails based on templates
 *
 */
namespace General\Service;

use General\Email as Email;

use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use Mailing\Entity\Mailing;
use General\Entity\WebInfo;
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
    protected $templateVars = array();

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
    public function create($data = array())
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
     * Return a preview of the email
     */
    public function preview($email)
    {
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

        $this->updateTemplateVarsWithContactService();

        //If not layout, use default
        if (!$email->getHtmlLayoutName()) {
            $email->setHtmlLayoutName($this->config["defaults"]["html_layout_name"]);
        }

        //If not recipient, send to admin
        if (count($email->getTo()) === 0) {
            $email->addTo($this->config["emails"]["admin"]);
        }

        //If not sender, use default
        if (!$email->getFrom()) {
            $email->setFrom($this->config["defaults"]["from_email"]);
            $email->setFromName($this->config["defaults"]["from_name"]);
        }

        $content = $this->renderContent($this->templateVars);

        $htmlView = $this->renderer->render(
            'email/' . $email->getHtmlLayoutName(),
            array_merge_recursive(array('content' => $content), $this->templateVars)
        );

        $textView = $this->renderer->render(
            'email/' . $email->getTextLayoutName(),
            array_merge_recursive(array('content' => $content), $this->templateVars)
        );

        if (!is_null($textView)) {
            $email->setTextContent($textView);
        };

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
     * Prepare email to send.
     */
    private function prepareMailing(Email $email)
    {
        //Template Variables
        $this->templateVars = array_merge($this->config["template_vars"], $email->toArray());

        $this->updateTemplateVarsWithContactService();

        //If not layout, use default
        if (!$email->getHtmlLayoutName()) {
            $email->setHtmlLayoutName($this->config["defaults"]["html_layout_name"]);
        }

        //If not recipient, send to admin
        if (count($email->getTo()) === 0) {
            $email->addTo($this->config["emails"]["admin"]);
        }

        $email->setFrom($this->getMailing()->getSender()->getSender());
        $email->setFromName($this->getMailing()->getSender()->getEmail());

        $content = $this->renderMailingContent($this->templateVars);

        $htmlView = $this->renderer->render(
            'email/' . $email->getHtmlLayoutName(),
            array_merge_recursive(array('content' => $content), $this->templateVars)
        );

        $textView = $this->renderer->render(
            'email/' . $email->getTextLayoutName(),
            array_merge_recursive(array('content' => $content), $this->templateVars)
        );

        if (!is_null($textView)) {
            $email->setTextContent($textView);
        };

        if (!is_null($htmlView)) {
            $email->setHtmlContent($htmlView);
        };

        //Create Zend Message
        $message = new Message();

        //To recipients
        foreach ($email->getTo() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $message->addTo($contact->getEmail(), $contact);
            } else {
                $message->addTo($emailAddress, $contact);
            }
        }

        //From
        $message->setFrom($this->getMailing()->getSender()->getEmail(), $this->getMailing()->getSender()->getSender());

        //To recipients
        foreach ($email->getTo() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $message->addTo($contact->getEmail(), $contact);
            } else {
                $message->addTo($emailAddress, $contact);
            }
        }

        //Subject. Include the CompanyName in the [[site]] tags
        $message->setSubject($this->getMailing()->getMailSubject());

        $htmlContent       = new MimePart($email->getHtmlContent());
        $htmlContent->type = "text/html";

        $textContent       = new MimePart($email->getTextContent());
        $textContent->type = 'text/plain';

        $body = new MimeMessage();
        //$body->setParts(array($htmlContent, $textContent));
        $body->setParts(array($htmlContent));

        /**
         * Set specific headers
         * https://eu.mailjet.com/docs/emails_headers
         */
        $message->getHeaders()->addHeaderLine('X-Mailjet-Campaign', DEBRANOVA_HOST . '-mailing-' . $this->getMailing()->getId());
        //$message->getHeaders()->addHeaderLine('X-Mailjet-DeduplicateCampaign', $duplicateCampaign);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-TrackOpen', $trackOpen);
        //$message->getHeaders()->addHeaderLine('X-Mailjet-TrackClick', $trackClick);

        $message->setBody($body);

        return $message;
    }

    /**
     * Render the content twig-wise
     *
     * @param $this ->templateVars
     *
     * @return null|string
     */
    private function renderContent()
    {
        /**
         * Grab the content from the template and save the .twig format in on the file server
         */
        file_put_contents(
            $this->config['template_vars']['cache_location'] . DIRECTORY_SEPARATOR .
            $this->getTemplateLocation(), preg_replace('~\[(.*?)\]~', "{{ $1|raw }}", nl2br($this->template->getContent()))
        );

        $content = $this->renderer->render(
            $this->getTemplateLocation(), $this->templateVars
        );

        return $content;
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
            ), $this->getMailing()->getMailHtml());

        $content = preg_replace(
            array(
                '~\[(.*?)\]~'
            ),
            array
            ("{{ $1|raw }}"
            ), $content);

        /**
         * Grab the content from the template and save the .twig format in on the file server
         */
        file_put_contents(
            $this->config['template_vars']['cache_location'] . DIRECTORY_SEPARATOR .
            $this->getMailingTemplateLocation($this->getMailing()->getId()),
            $content
        );

        $content = $this->renderer->render(
            $this->getMailingTemplateLocation($this->getMailing()->getId()), $this->templateVars
        );

        return $content;
    }


    /**
     * @return string
     */
    public function getTemplateLocation()
    {
        return 'template-' . $this->template->getId() . '.twig';
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function getMailingTemplateLocation($id)
    {
        return 'template-mailing-content-' . $id . '.twig';
    }

    /**
     * @param $templateName
     *
     * @throws \Exception
     */
    public function setTemplate($templateName)
    {
        $this->template = $this->generalService->findWebInfoByInfo($templateName);

        if (is_null($this->template)) {
            throw new \InvalidArgumentException(sprintf('There is no no template with info "%s"', $templateName));
        }
    }

    /**
     * @param \Mailing\Entity\Mailing $mailing
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
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
     * Extract the contactService and include the variables in the template array settings
     */
    public function updateTemplateVarsWithContactService()
    {
        if (!is_null($this->getContactService())) {

            $this->templateVars['attention']    = $this->getContactService()->parseAttention();
            $this->templateVars['fullname']     = $this->getContactService()->parseFullName();
            $this->templateVars['country']      = $this->getContactService()->parseCountry();
            $this->templateVars['organisation'] = $this->getContactService()->parseOrganisation();
        }
    }


    /**
     * @param \Contact\Service\ContactService $contactService
     */
    public function setContactService($contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * @return \Contact\Service\ContactService
     */
    public function getContactService()
    {
        return $this->contactService;
    }
}
