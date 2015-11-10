<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Service;

use Contact\Entity\Contact;
use General\Email as Email;
use General\Entity\WebInfo;
use Mailing\Entity\Mailing;
use Publication\Entity\Publication;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class EmailService.
 */
class EmailService extends ServiceAbstract implements
    ServiceLocatorAwareInterface,
    GeneralServiceAwareInterface
{
    /**
     * @var Email
     */
    protected $email;
    /**
     * @var SendmailTransport|SmtpTransport
     */
    protected $transport;
    /**
     * @var array
     */
    protected $config;
    /**
     * @var TwigRenderer
     */
    protected $renderer;
    /**
     * @var WebInfo
     */
    protected $template;
    /**
     * @var Mailing
     */
    protected $mailing;
    /**
     * @var Message
     */
    protected $message;
    /**
     * @var array
     */
    protected $templateVars = [];
    /**
     * @var MimePart[]
     */
    protected $attachments = [];


    /**
     * @param                $config
     * @param ServiceManager $serviceManager
     */
    public function __construct($config, ServiceManager $serviceManager)
    {
        $this->config = $config;

        if ($this->config["active"]) {
            $this->renderer = $serviceManager->get('ZfcTwigRenderer');
            // Server SMTP config
            $transport = new SendmailTransport();
            // Relay SMTP
            if ($this->config["relay"]["active"]) {
                $transport = new SmtpTransport();
                $transportConfig = [
                    'name'              => "DebraNova_General_Email",
                    'host'              => $this->config["relay"]["host"],
                    'connection_class'  => 'login',
                    'connection_config' => [
                        'username' => $this->config["relay"]["username"],
                        'password' => $this->config["relay"]["password"],
                    ],
                ];
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

            $this->transport = $transport;
        }
    }

    /**
     * Create a new email.
     *
     * @param array $data
     *
     * @return Email
     */
    public function create($data = [])
    {
        $this->email = new Email($data, $this->getServiceLocator());

        return $this->email;
    }

    /**
     * Send the email.
     */
    public function send()
    {
        switch ($this->email->isPersonal()) {
            case true:
                foreach ($this->email->getTo() as $recipient => $contact) {
                    /*
                     * Create a new message for everyone
                     */
                    $this->message = new Message();
                    $this->message->setEncoding('UTF-8');

                    $this->generateMessage();

                    //add the CC and BCC to the email
                    $this->setShadowRecipients();

                    /*
                     * We have a recipient which can be an instance of the contact. Produce a contactService object
                     * and fill the templateVars with extra options
                     */
                    if ($contact instanceof Contact) {
                        $this->updateTemplateVarsWithContact($contact);
                    } else {
                        $contactName = $contact;
                        $contact = new Contact();
                        $contact->setEmail($recipient);
                        if (!is_null($contactName)) {
                            $contact->setFirstName($contactName);
                        }
                    }

                    /*
                     * Overrule the to when we are in development
                     */
                    if (!defined("DEBRANOVA_ENVIRONMENT") || 'development' === DEBRANOVA_ENVIRONMENT) {
                        $this->message->addTo($this->config["emails"]["admin"], $contact->getDisplayName());
                    } else {
                        $this->message->addTo($contact->getEmail(), $contact->getDisplayName());
                    }

                    /**
                     * We have the contact and can now produce the content of the message
                     */
                    $this->parseSubject();

                    /**
                     * We have the contact and can now produce the content of the message
                     */
                    $this->parseBody();

                    /*
                     * Send the email
                     */
                    $this->transport->send($this->message);
                }
                break;
            case false:
                /*
                 * Create a new message for everyone
                 */
                $this->message = new Message();
                $this->message->setEncoding('UTF-8');

                $this->generateMessage();

                //add the CC and BCC to the email
                $this->setShadowRecipients();

                foreach ($this->email->getTo() as $recipient => $contact) {
                    /*
                     * We have a recipient which can be an instance of the contact. Produce a contactService object
                     * and fill the templateVars with extra options
                     */
                    if (!$contact instanceof Contact) {
                        $contactName = $contact;
                        $contact = new Contact();
                        $contact->setEmail($recipient);
                        $contact->setFirstName($contactName);
                    }

                    /*
                     * Overrule the to when we are in development
                     */
                    if (!defined("DEBRANOVA_ENVIRONMENT") || 'development' === DEBRANOVA_ENVIRONMENT) {
                        $this->message->addTo($this->config["emails"]["admin"], $contact->getDisplayName());
                    } else {
                        $this->message->addTo($contact->getEmail(), $contact->getDisplayName());
                    }
                }

                /*
                 * We have the contact and can now produce the content of the message
                 */
                $this->parseSubject();

                /*
                 * We have the contact and can now produce the content of the message
                 */
                $this->parseBody();

                /*
                 * Send the email
                 */
                $this->transport->send($this->message);

                break;
        }
    }

    /**
     * Parse the subject of the email.
     */
    public function parseSubject()
    {
        //Transfer first the subject form the email (if any)
        $this->message->setSubject($this->email->getSubject());

        /*
         * When the subject is empty AND we have a template, simply take the subject of the template
         */
        if (!empty($this->message->getSubject()) && !is_null($this->template)) {
            $this->message->setSubject($this->template->getSubject());
        }

        /*
         * Go over the templateVars to replace content in the subject
         */
        foreach ($this->templateVars as $key => $replace) {
            /*
             * Skip the service manager
             */
            if ($replace instanceof ServiceManager) {
                continue;
            }

            /*
             * replace the content of the title with the available keys in the template vars
             */
            if (!is_array($replace)) {
                $this->message->setSubject(str_replace(sprintf("[%s]", $key), $replace, $this->message->getSubject()));
            }
        }
    }

    /**
     * @return string|null
     */
    public function parseBody()
    {
        try {
            $htmlView = $this->renderer->render(
                $this->email->getHtmlLayoutName(),
                array_merge(
                    ['content' => $this->personaliseMessage($this->email->getMessage())],
                    $this->templateVars
                )
            );
            $textView = $this->renderer->render(
                'plain',
                array_merge(
                    ['content' => $this->personaliseMessage($this->email->getMessage())],
                    $this->templateVars
                )
            );
        } catch (\Twig_Error_Syntax $e) {
            $htmlView = $textView = sprintf("Something went wrong with the merge. Error message: %s", $e->getMessage());
        }

        $htmlContent = new MimePart($htmlView);
        $htmlContent->type = "text/html";
        $textContent = new MimePart($textView);
        $textContent->type = 'text/plain';
        $body = new MimeMessage();
        $body->setParts(array_merge($this->attachments, [$htmlContent]));
        /*
         * Set specific headers
         * https://eu.mailjet.com/docs/emails_headers
         */
        //message->getHeaders()->addHeaderLine('X-Mailjet-Campaign', $campaign);
        //message->getHeaders()->addHeaderLine('X-Mailjet-DeduplicateCampaign', $duplicateCampaign);
        //message->getHeaders()->addHeaderLine('X-Mailjet-TrackOpen', $trackOpen);
        //message->getHeaders()->addHeaderLine('X-Mailjet-TrackClick', $trackClick);

        $this->message->setBody($body);

        return true;
    }

    /**
     *
     */
    private function generateMessage()
    {
        //Reply to
        if ($this->config["defaults"]["reply_to"] && is_null($this->email->getReplyTo())) {
            $this->message->addReplyTo(
                $this->config["defaults"]["reply_to"],
                $this->config["defaults"]["reply_to_name"]
            );
        }

        /*
         * Produce a list of template vars
         */
        $this->templateVars = array_merge($this->config["template_vars"], $this->email->toArray());

        //If not layout, use default
        if (!$this->email->getHtmlLayoutName()) {
            $this->email->setHtmlLayoutName($this->config["defaults"]["html_layout_name"]);
        }

        /*
         * If not sender, use default
         */
        if (!is_null($this->email->getFrom())) {
            $this->message->setFrom($this->email->getFrom(), $this->email->getFromName());
        } else {
            $this->message->setFrom($this->config["defaults"]["from_email"], $this->config["defaults"]["from_name"]);
        }

        /*
         * Force the mailing as header if we have a mailing
         */
        if (!is_null($this->mailing)) {
            $this->message->getHeaders()->addHeaderLine(
                'X-Mailjet-Campaign',
                DEBRANOVA_HOST . '-mailing-' . $this->mailing->getId()
            );
        }
    }

    /**
     * Extract the contactService and include the variables in the template array settings.
     *
     * @var Contact
     */
    public function updateTemplateVarsWithContact(Contact $contact)
    {
        /*
         * @var ContactService
         */
        $contactService = clone $this->getServiceLocator()->get('contact_contact_service');
        $contactService->setContact($contact);

        $this->templateVars['attention'] = $contactService->parseAttention();
        $this->templateVars['firstname'] = $contactService->getContact()->getFirstName();
        $this->templateVars['lastname'] = trim(
            sprintf(
                "%s %s",
                $contactService->getContact()->getMiddleName(),
                $contactService->getContact()->getLastName()
            )
        );
        $this->templateVars['fullname'] = $contactService->parseFullName();
        $this->templateVars['country'] = (string)$contactService->parseCountry();
        $this->templateVars['organisation'] = $contactService->parseOrganisation();
        $this->templateVars['email'] = $contactService->getContact()->getEmail();
        $this->templateVars['signature'] = $contactService->parseSignature();
    }

    /**
     * @param $content
     *
     * @return string
     */
    protected function createTwigTemplate($content)
    {
        return preg_replace(
            [
                '~\[(.*?)\]~',
            ],
            [
                "{{ $1|raw }}",
            ],
            $content
        );
    }

    /**
     * @param $content
     * @param $type
     * @param $fileName
     */
    public function addAttachment($content, $type, $fileName)
    {
        /*
         * Create the attachment
         */
        $attachment = new MimePart($content);
        $attachment->type = $type;
        $attachment->filename = $fileName;
        $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
        // Setting the encoding is recommended for binary data
        $attachment->encoding = Mime::ENCODING_BASE64;

        $this->attachments[] = $attachment;
    }

    /**
     * @param $publication
     */
    public function addPublication(Publication $publication)
    {
        /*
         * Create the attachment
         */
        $attachment = new MimePart(stream_get_contents($publication->getObject()->first()->getObject()));
        $attachment->type = $publication->getContentType()->getContentType();
        $attachment->filename = $publication->getOriginal();
        $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
        // Setting the encoding is recommended for binary data
        $attachment->encoding = Mime::ENCODING_BASE64;

        $this->attachments[] = $attachment;
    }


    /**
     * Set the BCC and CC recipients to the email (they are the same for every email).
     *
     * @return Message
     */
    public function setShadowRecipients()
    {
        //Cc recipients
        foreach ($this->email->getCc() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $this->message->addCc($contact->getEmail(), $contact);
            } else {
                $this->message->addCc($emailAddress, $contact);
            }
        }
        //Bcc recipients
        foreach ($this->email->getBcc() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $this->message->addBcc($contact->getEmail(), $contact);
            } else {
                $this->message->addBcc($emailAddress, $contact);
            }
        }
        if (!is_null($this->email->getReplyTo())) {
            $this->message->addReplyTo($this->email->getReplyTo(), $this->email->getReplyToName());
        }
    }

    /**
     * When the mailing is set, we need to take some features over from the mailing to the email.
     *
     * @param \Mailing\Entity\Mailing $mailing
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;

        if (is_null($this->email)) {
            throw new \RuntimeException("The email object is empty. Did you call create() first?");
        }

        $this->email->setFrom($this->mailing->getSender()->getEmail());
        $this->email->setFromName($this->mailing->getSender()->getSender());

        $this->email->setSubject($this->mailing->getMailSubject());
        $this->email->setHtmlLayoutName($this->mailing->getTemplate()->getTemplate());
        $this->email->setMessage($this->mailing->getMailHtml());
    }

    /**
     * Render the content twig-wise.
     *
     * @param $message
     *
     * @return null|string
     */
    private function personaliseMessage($message)
    {
        /*
         * Replace first the content of the mailing with the required (new) short tags
         */
        $content = preg_replace(
            [
                '~\[parent::getContact\(\)::firstname\]~',
                '~\[parent::getContact\(\)::parseLastname\(\)\]~',
                '~\[parent::getContact\(\)::parseFullname\(\)\]~',
                '~\[parent::getContact\(\)::getContactOrganisation\(\)::parseOrganisationWithBranch\(\)\]~',
                '~\[parent::getContact\(\)::country\]~',
            ],
            [
                "[firstname]",
                "[lastname]",
                "[fullname]",
                "[organisation]",
                "[country]",
            ],
            $message
        );

        /*
         * Clone the twigRenderer and overrule to loader to be a string
         */
        $twigRenderer = new \Twig_Environment();
        $twigRenderer->setLoader(new \Twig_Loader_Array(['email' => $this->createTwigTemplate($content)]));

        return $twigRenderer->render(
            'email',
            $this->templateVars
        );
    }

    /**
     * Produce a preview of the mailing content.
     *
     * @return null|string
     */
    public function generatePreview()
    {
        $this->updateTemplateVarsWithContact($this->getContactService()->getContact());

        if (is_null($this->mailing)) {
            throw new \RuntimeException("The mailing object is empty. Did set the template");
        }

        try {
            return $this->renderer->render(
                $this->mailing->getTemplate()->getTemplate(),
                array_merge(
                    ['content' => $this->personaliseMessage($this->email->getMessage())],
                    $this->templateVars
                )
            );
        } catch (\Twig_Error_Syntax $e) {
            print sprintf("Something went wrong. Error message: %s", $e->getMessage());
        }

        return true;
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

        if (is_null($this->email)) {
            throw new \RuntimeException("The email object is empty. Did you call create() first?");
        }

        $this->email->setMessage($this->createTwigTemplate($this->template->getContent()));
        $this->email->setSubject($this->template->getSubject());

        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
