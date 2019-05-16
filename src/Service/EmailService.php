<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2018 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */
declare(strict_types=1);

namespace General\Service;

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use DateTime;
use Deeplink\Entity\Target;
use Deeplink\Service\DeeplinkService;
use Exception;
use General\Entity\EmailMessage;
use General\Entity\EmailMessageEvent;
use General\ValueObject;
use InvalidArgumentException;
use Mailing\Entity\Mailing;
use Mailing\Entity\Sender;
use Mailing\Entity\Template;
use Mailjet\Client;
use Mailjet\Resources;
use Publication\Entity\Publication;
use Twig_Environment;
use Twig_Loader_Array;
use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\Url;
use Zend\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;
use function array_merge;
use function array_unique;
use function base64_encode;
use function count;
use function defined;
use function file_exists;
use function file_get_contents;
use function is_bool;
use function preg_match_all;
use function preg_replace;
use function sprintf;
use function str_replace;
use function stream_get_contents;
use function strip_tags;
use function substr;

/**
 * Class FormService
 *
 * @package Application\Service
 */
class EmailService
{
    private $config;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var GeneralService
     */
    private $generalService;
    /**
     * @var DeeplinkService
     */
    private $deeplinkService;
    /**
     * @var AuthenticationService
     */
    private $authenticationService;
    /**
     * @var TwigRenderer
     */
    private $renderer;
    /**
     * @var HelperPluginManager
     */
    private $viewHelperManager;

    /** @var Client */
    private $client;

    /** @var Template */
    private $template;

    /** @var \Mailing\Entity\Contact */
    private $mailingContact;

    private $templateVariables = [];
    /** @var ValueObject\Attachment[] */
    private $attachments = [];
    /** @var ValueObject\Attachment[] */
    private $inlinedAttachments = [];
    /** @var ValueObject\Recipient */
    private $from;
    /** @var ValueObject\Recipient[] */
    private $to = [];
    /** @var ValueObject\Recipient[] */
    private $cc = [];
    /** @var ValueObject\Recipient[] */
    private $bcc = [];
    /** @var ValueObject\Header[] */
    private $headers = [];

    /** @var string */
    private $emailContent;
    /** @var string */
    private $textPart;
    /** @var string */
    private $htmlPart;
    /** @var string */
    private $emailSubject;
    /** @var string */
    private $emailCampaign;

    public function __construct(
        array $config,
        ContactService $contactService,
        GeneralService $generalService,
        DeeplinkService $deeplinkService,
        AuthenticationService $authenticationService,
        TwigRenderer $renderer,
        HelperPluginManager $viewHelperManager
    ) {
        $this->config = $config;
        $this->contactService = $contactService;
        $this->generalService = $generalService;
        $this->deeplinkService = $deeplinkService;
        $this->authenticationService = $authenticationService;
        $this->renderer = $renderer;
        $this->viewHelperManager = $viewHelperManager;

        $this->client = new Client(
            $config['email']['relay']['username'],
            $config['email']['relay']['password'],
            true,
            ['version' => 'v3.1']
        );
    }

    public function setMailing(Mailing $mailing): void
    {
        $this->resetEmailContent();

        $this->setSender($mailing->getSender(), $mailing->getContact());

        $this->template = $mailing->getTemplate();
        $this->emailContent = $mailing->getMailHtml();
        $this->emailSubject = $mailing->getMailSubject();
        $this->emailCampaign = $mailing->getMailing();

        $this->templateVariables['subject'] = $mailing->getMailSubject();
    }

    private function resetEmailContent(): void
    {
        $this->from = null;
        $this->attachments = [];
        $this->inlinedAttachments = [];
        $this->templateVariables = [];
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->headers = [];
        $this->textPart = '';
        $this->htmlPart = '';
    }

    public function setSender(Sender $sender, Contact $owner = null): void
    {
        switch ($sender->getEmail()) {
            case '_self':
                /** @var Contact $contact */
                if ($this->authenticationService->hasIdentity()) {
                    $owner = $this->authenticationService->getIdentity();
                }
            // no break
            case '_owner':
                if (null === $owner) {
                    throw new InvalidArgumentException(sprintf('Owner cannot be empty for %s', $sender->getEmail()));
                }

                $this->from = new ValueObject\Recipient($owner->parseFullName(), $owner->getEmail());

                $this->templateVariables['sender_name'] = $owner->parseFullName();
                $this->templateVariables['sender_email'] = $owner->getEmail();
                $this->templateVariables['sender_signature'] = $this->contactService->parseSignature($owner);
                break;
            default:
                $this->from = new ValueObject\Recipient($sender->getSender(), $sender->getEmail());

                $this->templateVariables['sender_name'] = $sender->getSender();
                $this->templateVariables['sender_email'] = $sender->getEmail();
                break;
        }
    }

    public function setWebInfo(string $webInfoName): void
    {
        $this->resetEmailContent();

        $webInfo = $this->generalService->findWebInfoByInfo($webInfoName);

        if (null === $webInfo) {
            throw new InvalidArgumentException(sprintf('The requested template %s cannot be found', $webInfoName));
        }

        $this->setSender($webInfo->getSender(), $this->authenticationService->getIdentity());

        $this->template = $webInfo->getTemplate();
        $this->emailContent = $webInfo->getContent();
        $this->emailSubject = $webInfo->getSubject();
        $this->emailCampaign = $webInfo->getInfo();
    }

    public function setSubject(string $subject): void
    {
        $this->emailSubject = $subject;
    }

    public function setEmailContent(string $content): void
    {
        $this->emailContent = $content;
    }

    public function cannotRenderMailingReason(Mailing $mailing): ?string
    {
        if (null === $mailing->getMailHtml()) {
            return null;
        }

        try {
            $message = $this->createTwigTemplate($mailing->getMailHtml());

            $this->renderer->render(
                $mailing->getTemplate()->getTemplate(),
                ['content' => $message]
            );
            return null;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function createTwigTemplate(string $content): string
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
            $content
        );

        $content = preg_replace(
            [
                '~\[(.*?)\]~',
            ],
            [
                '{{ $1|raw }}',
            ],
            $content
        );

        $twigRenderer = new Twig_Environment(new Twig_Loader_Array(['email_template' => $content]));

        return $twigRenderer->render('email_template', $this->templateVariables);
    }


    public function addAttachment(string $contentType, string $fileName, string $content): void
    {
        $this->attachments[] = new ValueObject\Attachment(
            $contentType,
            $fileName,
            base64_encode($content)
        );
    }

    public function addTo(Contact $contact): void
    {
        $this->to[] = new ValueObject\Recipient($contact->parseFullName(), $contact->getEmail());

        $this->updateTemplateVarsWithContact($contact);
    }

    private function updateTemplateVarsWithContact(Contact $contact): void
    {
        $this->templateVariables['attention'] = $this->contactService->parseAttention($contact);
        $this->templateVariables['firstname'] = $contact->getFirstName();
        $this->templateVariables['lastname'] = trim(
            sprintf('%s %s', $contact->getMiddleName(), $contact->getLastName())
        );
        $this->templateVariables['fullname'] = $contact->parseFullName();
        $this->templateVariables['email'] = $contact->getEmail();

        if ($this->contactService->hasOrganisation($contact)) {
            $this->templateVariables['country'] = (string)$this->contactService->parseCountry($contact)->getCountry();
            $this->templateVariables['organisation'] = $this->contactService->parseOrganisation($contact);
        }

        $this->templateVariables['signature'] = $this->contactService->parseSignature($contact);
    }

    public function addToEmailAddress(string $emailAddress): void
    {
        $this->to[] = new ValueObject\Recipient($emailAddress, $emailAddress);
    }

    public function addCCEmailAddress(string $emailAddress): void
    {
        $this->cc[] = new ValueObject\Recipient($emailAddress, $emailAddress);
    }

    public function addBCCEmailAddress(string $emailAddress): void
    {
        $this->bcc[] = new ValueObject\Recipient($emailAddress, $emailAddress);
    }

    public function setDeeplink(Target $target, Contact $contact, ?string $key = null): void
    {
        $deeplink = $this->deeplinkService->createDeeplink(
            $target,
            $contact,
            null,
            $key
        );

        $this->templateVariables['deeplink'] = $this->deeplinkService->parseDeeplinkUrl($deeplink, 'link');
    }

    public function setUnsubscribe(string $unsubscribe): void
    {
        $this->templateVariables['unsubscribe'] = $unsubscribe;

        $this->headers[] = new ValueObject\Header('List-Unsubscribe', '<' . trim($unsubscribe) . '>');
    }

    public function send(): bool
    {
        $this->renderEmailContent();

        $emailMessage = new EmailMessage();
        $emailMessage->setEmailAddress($this->to[0]->toArray()['Email']);
        $emailMessage->setSubject($this->emailSubject);
        $emailMessage->setMessage($this->htmlPart);
        $emailMessage->setAmountOfAttachments(count($this->attachments));

        //Inject the mailing contact
        if (null !== $this->mailingContact) {
            $emailMessage->setMailingContact($this->mailingContact);
        }

        $this->generalService->save($emailMessage);

        $urlHelper = $this->viewHelperManager->get(Url::class);
        $link = $urlHelper('email/event', ['customId' => $emailMessage->getIdentifier()]);

        $messages = [];

        $message = new ValueObject\Email(
            $this->from->toArray(),
            $this->getTo(),
            $this->getCC(),
            $this->getBCC(),
            $this->emailSubject,
            $this->textPart,
            $this->htmlPart,
            $emailMessage->getIdentifier(),
            $this->config['deeplink']['serverUrl'] . $link,
            'enabled',
            'enabled',
            $this->emailCampaign,
            $this->getAttachments(),
            $this->getInlinedAttachments(),
            $this->getHeaders()
        );
        $messages[] = $message->toArray();

        $body = new ValueObject\Body($messages);

        $response = $this->client->post(Resources::$Email, ['body' => $body->toArray()]);

        if (!$response->success()) {
            //Update the email message
            $emailMessage->setLatestEvent('not_sent');
            $emailMessage->setDateLatestEvent(new DateTime());

            //Update the mailingContact
            if (null !== $this->mailingContact) {
                $this->mailingContact->setDateSent(new DateTime());
            }

            $emailEvent = new EmailMessageEvent();
            $emailEvent->setEmailMessage($emailMessage);
            $emailEvent->setEvent('not_sent');
            $emailEvent->setTime(new DateTime());
            $emailEvent->setMessageId(0);
            $emailEvent->setEmail('');
            $emailEvent->setCampaign('');
            $emailEvent->setError($response->getReasonPhrase());
            $this->generalService->save($emailEvent);

            return false;
        }

        //Update the email message
        $emailMessage->setLatestEvent('sent_to_mailjet');
        $emailMessage->setDateLatestEvent(new DateTime());

        $emailEvent = new EmailMessageEvent();
        $emailEvent->setEmailMessage($emailMessage);
        $emailEvent->setEvent('sent_to_mailjet');
        $emailEvent->setTime(new DateTime());
        $emailEvent->setMessageId(0);
        $emailEvent->setEmail('');
        $emailEvent->setCampaign('');
        $emailEvent->setError($response->getReasonPhrase());
        $this->generalService->save($emailEvent);

        return true;
    }

    private function renderEmailContent(): void
    {
        $message = $this->createTwigTemplate((string)$this->emailContent);

        try {
            $htmlPart = $this->renderer->render(
                $this->template->getTemplate(),
                array_merge(['content' => $message], $this->templateVariables)
            );
            $textPart = $this->renderer->render(
                'plain',
                ['content' => strip_tags($message)]
            );
        } catch (Exception $e) {
            $htmlPart = $textPart = sprintf('Something went wrong with the merge. Error message: %s', $e->getMessage());
        }

        $this->embedImagesAsAttachment($htmlPart);

        $this->htmlPart = $htmlPart;
        $this->textPart = $textPart;

        foreach ($this->templateVariables as $key => $replace) {
            //Do not replace booleans or \DateTime instances
            if (is_bool($replace) || $replace instanceof DateTime) {
                continue;
            }
            $this->emailSubject = str_replace(sprintf('[%s]', $key), $replace, $this->emailSubject);
        }
    }

    private function embedImagesAsAttachment(string &$htmlPart): void
    {
        $matches = [];
        preg_match_all("#src=['\"]([^'\"]+)#i", $htmlPart, $matches);

        $matches = array_unique($matches[1]);

        if (count($matches) > 0) {
            foreach ($matches as $key => $filename) {
                if ($filename && file_exists($filename)) {
                    $id = md5_file($filename);

                    $this->inlinedAttachments = new ValueObject\Attachment(
                        $this->mimeByExtension($filename),
                        substr($id, 0, 10),
                        file_get_contents($filename),
                        $id
                    );

                    $htmlPart = str_replace($filename, 'cid:' . $id, $htmlPart);
                }
            }
        }
    }

    private function mimeByExtension(string $filename): string
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

    private function getTo(): array
    {
        $to = [];

        foreach ($this->to as $singleTo) {
            $toValue = $singleTo->toArray();
            if (!defined('ITEAOFFICE_ENVIRONMENT') || 'development' === ITEAOFFICE_ENVIRONMENT) {
                $toValue['Email'] = 'info@jield.nl';
            }
            $to[] = $toValue;
        }

        return $to;
    }

    private function getCC(): ?array
    {
        $cc = [];
        foreach ($this->cc as $singleCC) {
            $cc[] = $singleCC->toArray();
        }

        return $cc;
    }

    private function getBCC(): ?array
    {
        $bcc = [];
        foreach ($this->bcc as $singleBCC) {
            $bcc[] = $singleBCC->toArray();
        }

        return $bcc;
    }

    private function getAttachments(): ?array
    {
        $attachments = [];
        foreach ($this->attachments as $singleAttachment) {
            $attachments[] = $singleAttachment->toArray();
        }

        return $attachments;
    }

    private function getInlinedAttachments(): ?array
    {
        $inlinedAttachments = [];
        foreach ($this->inlinedAttachments as $singleAttachment) {
            $inlinedAttachments[] = $singleAttachment->toArray();
        }

        return $inlinedAttachments;
    }

    private function getHeaders(): ?array
    {
        $headers = [];
        foreach ($this->headers as $singleHeader) {
            $headers += $singleHeader->toArray();
        }

        return $headers;
    }

    public function setFrom(string $name, string $email): void
    {
        $this->from = new ValueObject\Recipient($name, $email);
    }

    public function addPublication(Publication $publication): void
    {
        $this->attachments[] = new ValueObject\Attachment(
            $publication->getContentType()->getContentType(),
            $publication->getOriginal(),
            base64_encode(stream_get_contents($publication->getObject()->first()->getObject()))
        );
    }

    public function setMailingContact(\Mailing\Entity\Contact $mailingContact): void
    {
        $this->mailingContact = $mailingContact;
    }

    public function addTemplateVariables(array $variables): void
    {
        foreach ($variables as $variable => $value) {
            $this->setTemplateVariable($variable, $value);
        }
    }

    public function setTemplateVariable(string $variable, $value): void
    {
        if (!is_bool($value) && !$value instanceof DateTime) {
            $value = (string)$value;
        }

        $this->templateVariables[$this->underscore($variable)] = $value;
    }

    private function underscore(string $name): string
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
    }
}
