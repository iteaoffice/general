<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Service;

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use Doctrine\ORM\EntityManager;
use General\Email as Email;
use General\Entity\EmailMessage;
use General\Entity\WebInfo;
use Mailing\Entity\Mailing;
use Publication\Entity\Publication;
use Zend\Authentication\AuthenticationService;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\ServiceManager\ServiceManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class EmailService.
 */
class EmailService extends AbstractService
{
    /**
     * @var ContactService
     */
    protected $contactService;
    /**
     * @var GeneralService
     */
    protected $generalService;
    /**
     * @var AuthenticationService
     */
    protected $authenticationService;
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
     * @var \Mailing\Entity\Contact
     */
    protected $mailingContact;
    /**
     * @var Message
     */
    protected $message;
    /**
     * @var string
     */
    protected $htmlView;
    /**
     * @var array
     */
    protected $templateVars = [];
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var MimePart[]
     */
    protected $attachments = [];

    public function __construct(
        array $config,
        ContactService $contactService,
        GeneralService $generalService,
        AuthenticationService $authenticationService,
        EntityManager $entityManager,
        TwigRenderer $renderer
    ) {
        parent::__construct($entityManager);

        $this->config = $config['email'];
        $this->contactService = $contactService;
        $this->generalService = $generalService;
        $this->authenticationService = $authenticationService;
        $this->renderer = $renderer;

        $this->setTransport();
    }

    private function setTransport(): void
    {
        if ($this->config['active']) {
            // Server SMTP config
            $transport = new SendmailTransport();
            // Relay SMTP
            if ($this->config['relay']['active']) {
                $transport = new SmtpTransport();
                $transportConfig = [
                    'name'              => 'ITEA_Office_General_Email',
                    'host'              => $this->config['relay']['host'],
                    'connection_class'  => 'login',
                    'connection_config' => [
                        'username' => $this->config['relay']['username'],
                        'password' => $this->config['relay']['password'],
                    ],
                ];
                // Add port
                if ($this->config['relay']['port']) {
                    $transportConfig['port'] = $this->config['relay']['port'];
                }
                // Add ssl
                if ($this->config['relay']['ssl']) {
                    $transportConfig['connection_config']['ssl'] = $this->config['relay']['ssl'];
                }
                $options = new SmtpOptions($transportConfig);
                $transport->setOptions($options);
            }

            $this->transport = $transport;
        }
    }

    public function create(array $data = []): Email
    {
        $this->email = new Email($data, $this->config);

        return $this->email;
    }

    public function send(): void
    {
        switch ($this->email->isPersonal()) {
            case true:
                foreach ($this->email->getTo() as $recipient => $contact) {
                    /*
                     * Create a new message for everyone
                     */
                    $this->message = new Message();
                    //$this->message->setEncoding('UTF-8');

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
                        $contact = new Contact();
                        $contact->setEmail($recipient);
                    }

                    /*
                   * Overrule the to when we are in development
                   */
                    if (!\defined("ITEAOFFICE_ENVIRONMENT") || 'development' === ITEAOFFICE_ENVIRONMENT) {
                        $this->message->addTo('info@jield.nl', $contact->getDisplayName());
                    } else {
                        $this->message->addTo(
                            $contact->getEmail(),
                            null !== $contact->getId() ? $contact->getDisplayName() : null
                        );
                    }

                    /**
                     * We have the contact and can now produce the content of the message
                     */
                    $this->parseSubject();

                    /**
                     * If we have a deeplink, parse it
                     */
                    $this->parseDeeplink();

                    /**
                     * If we have an unsubscribe, parse it
                     */
                    $this->parseUnsubscribe();

                    /**
                     * We have the contact and can now produce the content of the message
                     */
                    $this->parseBody();

                    /**
                     * Send the email
                     */
                    $this->sendPersonalEmail($contact);
                }
                break;
            case false:
                /*
                 * Create a new message for everyone
                 */
                $this->message = new Message();
                //$this->message->setEncoding('UTF-8');

                $this->generateMessage();

                //add the CC and BCC to the email
                $this->setShadowRecipients();

                foreach ($this->email->getTo() as $recipient => $contact) {
                    /*
                     * We have a recipient which can be an instance of the contact. Produce a contactService object
                     * and fill the templateVars with extra options
                     */
                    if (!$contact instanceof Contact) {
                        $contact = new Contact();
                        $contact->setEmail($recipient);
                    }

                    /*
                     * Overrule the to when we are in development
                     */
                    if (!\defined("ITEAOFFICE_ENVIRONMENT") || 'development' === ITEAOFFICE_ENVIRONMENT) {
                        $this->message->addTo('info@japaveh.nl', $contact->getDisplayName());
                    } else {
                        $this->message->addTo(
                            $contact->getEmail(),
                            null !== $contact->getId() ? $contact->getDisplayName() : null
                        );
                    }
                }

                /*
                 * We have the contact and can now produce the content of the message
                 */
                $this->parseSubject();

                /**
                 * If we have a deeplink, parse it
                 */
                $this->parseDeeplink();

                /**
                 * If we have an unsubscribe, parse it
                 */
                $this->parseUnsubscribe();

                /*
                 * We have the contact and can now produce the content of the message
                 */
                $this->parseBody();


                $this->transport->send($this->message);

                break;
        }
    }

    private function generateMessage(): void
    {
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
        if (null !== $this->email->getFrom()) {
            $this->message->setFrom($this->email->getFrom(), $this->email->getFromName());
        } else {
            $this->message->setFrom($this->config["defaults"]["from_email"], $this->config["defaults"]["from_name"]);
        }
    }

    public function setShadowRecipients(): void
    {
        //Cc recipients
        foreach ($this->email->getCc() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $this->message->addCc($contact->getEmail(), $contact);
            } else {
                $this->message->addCc($emailAddress);
            }
        }
        //Bcc recipients
        foreach ($this->email->getBcc() as $emailAddress => $contact) {
            if ($contact instanceof Contact) {
                $this->message->addBcc($contact->getEmail(), $contact);
            } else {
                $this->message->addBcc($emailAddress);
            }
        }
        if (null !== $this->email->getReplyTo()) {
            $this->message->addReplyTo($this->email->getReplyTo(), $this->email->getReplyToName());
        }
    }

    public function updateTemplateVarsWithContact(Contact $contact): void
    {
        $this->templateVars['attention'] = $this->contactService->parseAttention($contact);
        $this->templateVars['firstname'] = $contact->getFirstName();
        $this->templateVars['lastname'] = trim(
            sprintf('%s %s', $contact->getMiddleName(), $contact->getLastName())
        );
        $this->templateVars['fullname'] = $contact->parseFullName();
        $this->templateVars['email'] = $contact->getEmail();

        if ($this->contactService->hasOrganisation($contact)) {
            $this->templateVars['country'] = (string)$this->contactService->parseCountry($contact)->getCountry();
            $this->templateVars['organisation'] = $this->contactService->parseOrganisation($contact);
        }

        $this->templateVars['signature'] = $this->contactService->parseSignature($contact);
        //Fill the unsubscribe with temp data
        $this->templateVars['unsubscribe'] = 'http://unsubscribe.example';
        $this->templateVars['deeplink'] = 'http://deeplink.example';
    }

    public function parseSubject(): void
    {
        //Transfer first the subject form the email (if any)
        $this->message->setSubject($this->email->getSubject());

        /*
         * When the subject is empty AND we have a template, simply take the subject of the template
         */
        if (null !== $this->template && empty($this->message->getSubject())) {
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
            if (!\is_array($replace)) {
                $this->message->setSubject(str_replace(sprintf("[%s]", $key), $replace, $this->message->getSubject()));
            }
        }
    }

    public function parseDeeplink(): void
    {
        $this->templateVars['deeplink'] = $this->email->getDeeplink();
    }

    public function parseUnsubscribe(): void
    {
        $this->templateVars['unsubscribe'] = $this->email->getUnsubscribe();
    }

    public function parseBody(): bool
    {
        try {
            $htmlView = $this->renderer->render(
                $this->email->getHtmlLayoutName(),
                array_merge(['content' => $this->personaliseMessage($this->email->getMessage())], $this->templateVars)
            );
            $textView = $this->renderer->render(
                'plain',
                array_merge(['content' => $this->personaliseMessage($this->email->getMessage())], $this->templateVars)
            );
        } catch (\Exception $e) {
            $htmlView = $textView = sprintf('Something went wrong with the merge. Error message: %s', $e->getMessage());
        }

        $this->htmlView = $htmlView;

        //Download the embedded files ad attach them to the mailing
        $htmlView = $this->embedImagesAsAttachment($htmlView);


        $htmlContent = new MimePart($htmlView);
        $htmlContent->type = 'text/html';
        $textContent = new MimePart($textView);
        $textContent->type = 'text/plain';
        $body = new MimeMessage();
        $body->setParts(array_merge($this->attachments, [$htmlContent]));

        foreach ($this->headers as $name => $value) {
            $this->message->getHeaders()->addHeaderLine($name, trim($value));
        }

        $this->message->setBody($body);

        return true;
    }

    private function personaliseMessage($message): ?string
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

        /**
         * Create a new instance of the Twig_Environment
         */
        $twigRenderer = new \Twig_Environment(new \Twig_Loader_Array(['email' => $this->createTwigTemplate($content)]));

        return $twigRenderer->render('email', $this->templateVars);
    }

    protected function createTwigTemplate($content): string
    {
        return preg_replace(
            [
                '~\[(.*?)\]~',
            ],
            [
                '{{ $1|raw }}',
            ],
            $content
        );
    }

    protected function embedImagesAsAttachment(string $htmlView): string
    {
        $matches = [];
        preg_match_all("#src=['\"]([^'\"]+)#i", $htmlView, $matches);

        $matches = array_unique($matches[1]);

        if (\count($matches) > 0) {
            foreach ($matches as $key => $filename) {
                if (($filename) && file_exists($filename)) {
                    $attachment = $this->addInlineAttachment($filename);
                    $htmlView = str_replace($filename, 'cid:' . $attachment->id, $htmlView);
                }
            }
        }

        return $htmlView;
    }

    public function addInlineAttachment($fileName): MimePart
    {
        /**
         * Create the attachment, only when the file exists
         */
        $attachment = new MimePart(file_get_contents($fileName));
        $attachment->id = 'cid_' . md5_file($fileName);
        $attachment->type = $this->mimeByExtension($fileName);
        $attachment->filename = substr(md5($attachment->id), 0, 10);
        $attachment->disposition = Mime::DISPOSITION_INLINE;
        // Setting the encoding is recommended for binary data
        $attachment->encoding = Mime::ENCODING_BASE64;

        $this->attachments[] = $attachment;

        return $attachment;
    }

    protected function mimeByExtension(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);


        switch ($extension) {
            case 'gif':
                $type = 'image/gif';
                break;
            case 'jpg':
            case 'jpeg':
                $type = 'image/jpg';
                break;
            case 'png':
                $type = 'image/png';
                break;
            default:
                $type = 'application/octet-stream';
        }

        return $type;
    }

    private function sendPersonalEmail(Contact $contact): void
    {
        $emailMessage = new EmailMessage();

        if (!$contact->isEmpty()) {
            $emailMessage->setContact($contact);
        }
        $emailMessage->setEmailAddress($contact->getEmail());
        $ccList = '';
        foreach ($this->message->getCc() as $cc) {
            $ccList .= sprintf('%s <%s> ', $cc->getName(), $cc->getEmail());
        }
        if (!empty($ccList)) {
            $emailMessage->setCc($ccList);
        }
        $bccList = '';
        foreach ($this->message->getBcc() as $bcc) {
            $bccList .= sprintf('%s <%s> ', $bcc->getName(), $bcc->getEmail());
        }
        if (!empty($bccList)) {
            $emailMessage->setBcc($bccList);
        }
        $emailMessage->setSubject($this->message->getSubject());
        $emailMessage->setMessage($this->getHtmlView());
        $emailMessage->setAmountOfAttachments(\count($this->attachments));

        //Inject the mailing contact
        if (null !== $this->getMailingContact()) {
            $emailMessage->setMailingContact($this->getMailingContact());
        }

        $this->save($emailMessage);

        //Add the custom header id
        $this->message->getHeaders()->addHeaderLine('X-MJ-CustomID', trim($emailMessage->getIdentifier()));
        /**
         * Send the email
         */
        $this->transport->send($this->message);
    }

    public function getHtmlView(): string
    {
        return $this->htmlView;
    }

    public function getMailingContact(): ?\Mailing\Entity\Contact
    {
        return $this->mailingContact;
    }

    public function setMailingContact(\Mailing\Entity\Contact $mailingContact): EmailService
    {
        $this->mailingContact = $mailingContact;

        return $this;
    }

    public function addAttachment($attachment, string $type = null, string $fileName = null): void
    {
        if (!$attachment instanceof MimePart) {
            $attachment = $this->createAttachment($attachment, $type, $fileName);
        }

        $this->attachments[] = $attachment;
    }

    public function createAttachment(string $content, string $type, string $fileName): MimePart
    {
        /**
         * Create the attachment
         */
        $attachment = new MimePart($content);
        $attachment->type = $type;
        $attachment->filename = $fileName;
        $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
        // Setting the encoding is recommended for binary data
        $attachment->encoding = Mime::ENCODING_BASE64;

        return $attachment;
    }

    public function addHeader(string $name, string $content): void
    {
        $this->headers[$name] = $content;
    }

    public function addPublication(Publication $publication): void
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

    public function setMailing($mailing): void
    {
        $this->mailing = $mailing;

        if (null === $this->email) {
            throw new \RuntimeException('The email object is empty. Did you call create() first?');
        }

        //There is a special case when the mail is sent on behalf of the user. The sender is then called _self, we also add the function __owner for the owner of the mailing
        switch (true) {
            case strpos($this->mailing->getSender()->getEmail(), '_self') !== false:
                /** @var Contact $contact */
                if ($this->authenticationService->hasIdentity()) {
                    $contact = $this->authenticationService->getIdentity();
                    $this->email->setFrom($contact->getEmail());
                    $this->email->setFromName($contact->getDisplayName());
                } else {
                    $this->email->setFrom($this->mailing->getContact()->getEmail());
                    $this->email->setFromName($this->mailing->getContact()->getDisplayName());
                }

                break;
            case strpos($this->mailing->getSender()->getEmail(), '_owner') !== false:
                $this->email->setFrom($this->mailing->getContact()->getEmail());
                $this->email->setFromName($this->mailing->getContact()->getDisplayName());
                break;
            default:
                $this->email->setFrom($this->mailing->getSender()->getEmail());
                $this->email->setFromName($this->mailing->getSender()->getSender());
                break;
        }

        $this->email->setSubject($this->mailing->getMailSubject());
        $this->email->setHtmlLayoutName($this->mailing->getTemplate()->getTemplate());
        $this->email->setMessage($this->mailing->getMailHtml());
    }

    public function generatePreview(): string
    {
        $this->updateTemplateVarsWithContact($this->authenticationService->getIdentity());

        if (null === $this->mailing) {
            throw new \RuntimeException('The mailing object is empty. Did you set the mailing?');
        }

        try {
            return $this->renderer->render(
                $this->mailing->getTemplate()->getTemplate(),
                array_merge(['content' => $this->personaliseMessage($this->email->getMessage())], $this->templateVars)
            );
        } catch (\Exception $e) {
            return sprintf('Something went wrong. Error message: %s', $e->getMessage());
        }
    }

    public function cannotRenderEmailReason(): ?string
    {
        try {
            $this->renderer->render(
                $this->mailing->getTemplate()->getTemplate(),
                array_merge(['content' => $this->personaliseMessage($this->email->getMessage())], $this->templateVars)
            );

            return null;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function setTemplate(string $templateName): EmailService
    {
        $this->template = $this->generalService->findWebInfoByInfo($templateName);

        if (null === $this->template) {
            throw new \InvalidArgumentException(sprintf('There is no no template with info "%s"', $templateName));
        }

        if (null === $this->email) {
            throw new \RuntimeException('The email object is empty. Did you call create() first?');
        }

        $this->email->setMessage($this->createTwigTemplate($this->template->getContent()));
        $this->email->setSubject($this->template->getSubject());

        //Inject the sender
        //There is a special case when the mail is sent on behalf of the user. The sender is then called _self, we also add the function __owner for the owner of the mailing
        switch (true) {
            case strpos($this->template->getSender()->getEmail(), '_self') !== false:
                $this->email->setFrom($this->authenticationService->getIdentity()->getEmail());
                $this->email->setFromName($this->authenticationService->getIdentity()->getDisplayName());
                break;
            default:
                $this->email->setFrom($this->template->getSender()->getEmail());
                $this->email->setFromName($this->template->getSender()->getSender());
                break;
        }

        $this->email->setHtmlLayoutName($this->template->getTemplate()->getTemplate());


        return $this;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
