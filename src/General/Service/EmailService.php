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
use General\Service\GeneralService;
use General\Entity\WebInfo;
use ZfcTwig\View\Renderer\TwigRenderer;

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
     */
    public function send($email)
    {
        $message = $this->prepare($email);

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

            return $transport->send($message);
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
        $templateVars = $this->config["template_vars"];
        $templateVars = array_merge($templateVars, $email->toArray());

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

        $content = $this->renderContent($templateVars);

        $htmlView = $this->renderer->render(
            'email/' . $email->getHtmlLayoutName() . '.twig',
            array('content' => $content)
        );


        $textView = $this->renderer->render(
            'email/' . $email->getTextLayoutName() . 'twig',
            array('content' => $content)
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
            str_replace('[[site]]', $this->config["template_vars"]["company"], $this->template->getSubject())
        );

        $htmlContent       = new MimePart($email->getHtmlContent());
        $htmlContent->type = "text/html";

        $textContent       = new MimePart($email->getTextContent());
        $textContent->type = 'text/plain';

        $body = new MimeMessage();
        $body->setParts(array($htmlContent, $textContent));


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
     * Render the content twig-wise
     *
     * @param $templateVars
     *
     * @return null|string
     */
    private function renderContent($templateVars)
    {
        /**
         * Grab the content from the template and save the .twig format in on the file server
         */
        file_put_contents(
            $this->getTemplateLocation(), preg_replace('~\[(.*?)\]~', "{{ $1 }}", $this->template->getContent())
        );

        $content = $this->renderer->render(
            $this->getTemplateLocation(), $templateVars
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
     * @param $templateName
     */
    public function setTemplate($templateName)
    {
        $this->template = $this->generalService->findWebInfoByInfo($templateName);
    }
}
