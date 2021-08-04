<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Service;

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use DateTime;
use Deeplink\Entity\Target;
use Deeplink\Service\DeeplinkService;
use Doctrine\ORM\EntityManager;
use Exception;
use General\Builder;
use General\Entity\EmailMessage;
use General\Entity\EmailMessageEvent;
use General\Options\EmailOptions;
use General\Options\ModuleOptions;
use General\Validator;
use General\ValueObject;
use InvalidArgumentException;
use Laminas\Authentication\AuthenticationService;
use Laminas\View\Helper\Url;
use Laminas\View\HelperPluginManager;
use Mailing\Entity\Mailing;
use Mailing\Entity\Sender;
use Mailing\Entity\Template;
use Mailing\Service\MailingService;
use Mailjet\Client;
use Mailjet\Resources;
use Publication\Entity\Publication;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
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
    private ContactService $contactService;
    private GeneralService $generalService;
    private MailingService $mailingService;
    private DeeplinkService $deeplinkService;
    private AuthenticationService $authenticationService;
    private TwigRenderer $renderer;
    private HelperPluginManager $viewHelperManager;
    private ModuleOptions $moduleOptions;
    private EmailOptions $emailOptions;
    private EntityManager $entityManager;
    private Client $client;
    private Template $template;
    private ?\Mailing\Entity\Contact $mailingContact = null;
    private array $templateVariables = [];
    /** @var ValueObject\Attachment[] */
    private array $attachments = [];
    /** @var ValueObject\Attachment[] */
    private array $inlinedAttachments = [];
    private ?ValueObject\Recipient $from;
    /** @var ValueObject\Recipient[] */
    private array $to = [];
    /** @var ValueObject\Recipient[] */
    private array $cc = [];
    /** @var ValueObject\Recipient[] */
    private array $bcc = [];
    /** @var ValueObject\Header[] */
    private array $headers = [];
    private string $emailContent;
    private string $textPart;
    private string $htmlPart;
    private string $emailSubject;
    private ?string $emailCampaign;

    public function __construct(
        EmailOptions $emailOptions,
        EntityManager $entityManager,
        ContactService $contactService,
        GeneralService $generalService,
        MailingService $mailingService,
        DeeplinkService $deeplinkService,
        AuthenticationService $authenticationService,
        TwigRenderer $renderer,
        HelperPluginManager $viewHelperManager,
        ModuleOptions $moduleOptions
    ) {
        $this->emailOptions          = $emailOptions;
        $this->entityManager         = $entityManager;
        $this->contactService        = $contactService;
        $this->generalService        = $generalService;
        $this->mailingService        = $mailingService;
        $this->deeplinkService       = $deeplinkService;
        $this->authenticationService = $authenticationService;
        $this->renderer              = $renderer;
        $this->viewHelperManager     = $viewHelperManager;
        $this->moduleOptions         = $moduleOptions;

        $this->client = new Client(
            $emailOptions->getUsername(),
            $emailOptions->getPassword(),
            true,
            ['version' => 'v3.1']
        );
    }

    public function createNewWebInfoEmailBuilder(string $key): Builder\WebInfoEmailBuilder
    {
        return new Builder\WebInfoEmailBuilder(
            $key,
            $this->emailOptions,
            $this->generalService,
            $this->mailingService,
            $this->contactService,
            $this->deeplinkService
        );
    }

    public function createNewMailingMailBuilder(Mailing $mailing): Builder\MailingEmailBuilder
    {
        return new Builder\MailingEmailBuilder(
            $mailing,
            $this->emailOptions,
            $this->moduleOptions,
            $this->mailingService,
            $this->contactService,
            $this->deeplinkService
        );
    }

    /** @deprecated */
    public function setMailing(Mailing $mailing): void
    {
        $this->resetEmailContent();

        $this->setSender($mailing->getSender(), $mailing->getContact());

        $this->template      = $mailing->getTemplate();
        $this->emailContent  = $mailing->getMailHtml();
        $this->emailSubject  = $mailing->getMailSubject();
        $this->emailCampaign = $mailing->getMailing();

        $this->templateVariables['subject'] = $mailing->getMailSubject();
    }

    /** @deprecated */
    private function resetEmailContent(): void
    {
        $this->from               = null;
        $this->attachments        = [];
        $this->inlinedAttachments = [];
        $this->templateVariables  = [];
        $this->to                 = [];
        $this->cc                 = [];
        $this->bcc                = [];
        $this->headers            = [];
        $this->textPart           = '';
        $this->htmlPart           = '';
    }

    /** @deprecated */
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

                $this->from = new ValueObject\Recipient($owner->getEmail(), $owner->parseFullName());

                $this->templateVariables['sender_name']      = $owner->parseFullName();
                $this->templateVariables['sender_email']     = $owner->getEmail();
                $this->templateVariables['sender_signature'] = $this->contactService->parseSignature($owner);
                break;
            default:
                $this->from = new ValueObject\Recipient($sender->getEmail(), $sender->getSender());

                $this->templateVariables['sender_name']  = $sender->getSender();
                $this->templateVariables['sender_email'] = $sender->getEmail();
                break;
        }
    }

    /** @deprecated */
    public function setWebInfo(string $webInfoName): void
    {
        $this->resetEmailContent();

        $webInfo = $this->generalService->findWebInfoByInfo($webInfoName);

        if (null === $webInfo) {
            throw new InvalidArgumentException(sprintf('The requested template %s cannot be found', $webInfoName));
        }

        $this->setSender($webInfo->getSender(), $this->authenticationService->getIdentity());

        $this->template      = $webInfo->getTemplate();
        $this->emailContent  = $webInfo->getContent();
        $this->emailSubject  = $webInfo->getSubject();
        $this->emailCampaign = $webInfo->getInfo();
    }

    /** @deprecated */
    public function setSubject(string $subject): void
    {
        $this->emailSubject = $subject;
    }

    /** @deprecated */
    public function setEmailContent(string $content): void
    {
        $this->emailContent = $content;
    }

    /** @deprecated */
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

    /** @deprecated */
    private function createTwigTemplate(string $content): string
    {
        $twigRenderer = new Environment(new ArrayLoader(['email_template' => $content]));

        return $twigRenderer->render('email_template', $this->templateVariables);
    }

    /** @deprecated */
    public function addAttachment(string $contentType, string $fileName, string $content): void
    {
        $this->attachments[] = new ValueObject\Attachment(
            $contentType,
            $fileName,
            base64_encode($content)
        );
    }

    /** @deprecated */
    public function addTo(Contact $contact): void
    {
        $this->to[] = new ValueObject\Recipient($contact->getEmail(), $contact->parseFullName());

        $this->updateTemplateVarsWithContact($contact);
    }

    /** @deprecated */
    private function updateTemplateVarsWithContact(Contact $contact): void
    {
        $this->templateVariables['attention'] = $this->contactService->parseAttention($contact);
        $this->templateVariables['firstname'] = $contact->getFirstName();
        $this->templateVariables['lastname']  = trim(
            sprintf('%s %s', $contact->getMiddleName(), $contact->getLastName())
        );
        $this->templateVariables['fullname']  = $contact->parseFullName();
        $this->templateVariables['email']     = $contact->getEmail();

        if ($this->contactService->hasOrganisation($contact)) {
            $this->templateVariables['country']      = (string)$this->contactService->parseCountry($contact)->getCountry();
            $this->templateVariables['organisation'] = $this->contactService->parseOrganisation($contact);
        }

        $this->templateVariables['signature'] = $this->contactService->parseSignature($contact);
    }

    /** @deprecated */
    public function addToEmailAddress(string $emailAddress): void
    {
        $this->to[] = new ValueObject\Recipient($emailAddress);
    }

    /** @deprecated */
    public function addCCEmailAddress(string $emailAddress): void
    {
        $this->cc[] = new ValueObject\Recipient($emailAddress);
    }

    /** @deprecated */
    public function addBCCEmailAddress(string $emailAddress): void
    {
        $this->bcc[] = new ValueObject\Recipient($emailAddress);
    }

    /** @deprecated */
    public function setDeeplink(Target $target, Contact $contact, ?string $key = null): void
    {
        $deeplink = $this->deeplinkService->createDeeplink(
            $target,
            $contact,
            null,
            $key
        );

        $this->templateVariables['deeplink'] = $this->deeplinkService->parseDeeplinkUrl($deeplink);
    }

    /** @deprecated */
    public function setUnsubscribe(string $unsubscribe): void
    {
        $this->templateVariables['unsubscribe'] = $unsubscribe;

        $this->headers[] = new ValueObject\Header('List-Unsubscribe', '<' . trim($unsubscribe) . '>');
    }

    public function sendBuilder(Builder\EmailBuilder $emailBuilder): bool
    {
        $emailBuilder->renderEmail();

        $validator = new Validator\EmailValidator($emailBuilder);

        if (! $validator->isValid()) {
            var_dump($validator->getCannotSendEmailReasons());
            return false;
        }


        $emailMessage = $this->registerEmailMessage($emailBuilder);

        $emailMessageEvent = new EmailMessageEvent();
        $emailMessageEvent->setEmailMessage($emailMessage);

        $response = $this->client->post(
            Resources::$Email,
            ['body' => $emailBuilder->getMailjetBody($emailMessage->getIdentifier())->toArray()]
        );

        if (! $response->success()) {
            //Update the email message
            $emailMessage->setLatestEvent('sending_failed');
            $emailMessageEvent->setEvent('sending_failed');
        }

        if ($response->success()) {
            //Update the email message
            $emailMessage->setLatestEvent('sent_to_mailjet');
            $emailMessageEvent->setEvent('sent_to_mailjet');
        }

        $emailMessage->setDateLatestEvent(new DateTime());

        $emailMessageEvent->setTime(new DateTime());
        $emailMessageEvent->setMessageId(0);
        $emailMessageEvent->setError($response->getReasonPhrase());
        $this->entityManager->persist($emailMessageEvent);


        $this->entityManager->flush();

        return true;
    }

    private function registerEmailMessage(Builder\EmailBuilder $emailBuilder): EmailMessage
    {
        $emailMessage = new EmailMessage();
        $emailMessage->setEmailAddress($emailBuilder->getFirstEmailAddress());
        $emailMessage->setSubject($emailBuilder->getSubject());
        $emailMessage->setMessage($emailBuilder->getHtmlPart());
        $emailMessage->setAmountOfAttachments($emailBuilder->getAmountOfAttachments());

        $emailMessage->setSender($emailBuilder->getSender());
        $emailMessage->setTemplate($emailBuilder->getTemplate());
        $emailMessage->setTo($emailBuilder->getTo());
        $emailMessage->setCc($emailBuilder->getCC());
        $emailMessage->setBcc($emailBuilder->getBCC());
        $emailMessage->setContact($emailMessage->getContact());

        if ($emailBuilder->getMailingContact()) {
            $emailMessage->setMailingContact($emailBuilder->getMailingContact());
        }

        $this->entityManager->persist($emailMessage);

        return $emailMessage;
    }

    /** @deprecated */
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

        $messages = [];

        $message    = new ValueObject\Email(
            $this->from->toArray(),
            $this->getTo(),
            $this->getCC(),
            $this->getBCC(),
            $this->emailSubject,
            $this->textPart,
            $this->htmlPart,
            $emailMessage->getIdentifier(),
            $this->moduleOptions->getServerUrl() . '/email/event.json',
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

        if (! $response->success()) {
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

    /** @deprecated */
    private function renderEmailContent(): void
    {
        $message = $this->createTwigTemplate((string)$this->emailContent);

        try {
            $htmlPart = $this->renderer->render(
                $this->template->getTemplate(),
                array_merge(['content' => $message], $this->templateVariables)
            );
            $textPart = strip_tags($htmlPart);
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

                    $this->inlinedAttachments[] = new ValueObject\Attachment(
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

    /** @deprecated */
    private function getTo(): array
    {
        $to = [];

        foreach ($this->to as $singleTo) {
            $toValue = $singleTo->toArray();
            if (! defined('ITEAOFFICE_ENVIRONMENT') || 'development' === ITEAOFFICE_ENVIRONMENT) {
                $toValue['Email'] = 'info@jield.nl';
            }
            $to[] = $toValue;
        }

        return $to;
    }

    /** @deprecated */
    private function getCC(): ?array
    {
        $cc = [];
        foreach ($this->cc as $singleCC) {
            $cc[] = $singleCC->toArray();
        }

        return $cc;
    }

    /** @deprecated */
    private function getBCC(): ?array
    {
        $bcc = [];
        foreach ($this->bcc as $singleBCC) {
            $bcc[] = $singleBCC->toArray();
        }

        return $bcc;
    }

    /** @deprecated */
    private function getAttachments(): ?array
    {
        $attachments = [];
        foreach ($this->attachments as $singleAttachment) {
            $attachments[] = $singleAttachment->toArray();
        }

        return $attachments;
    }

    /** @deprecated */
    private function getInlinedAttachments(): ?array
    {
        $inlinedAttachments = [];
        foreach ($this->inlinedAttachments as $singleAttachment) {
            $inlinedAttachments[] = $singleAttachment->toArray();
        }

        return $inlinedAttachments;
    }

    /** @deprecated */
    private function getHeaders(): array
    {
        $headers = [];
        foreach ($this->headers as $singleHeader) {
            $headers += $singleHeader->toArray();
        }

        return $headers;
    }

    /** @deprecated */
    public function setFrom(string $name, string $email): void
    {
        $this->from = new ValueObject\Recipient($email, $name);
    }

    /** @deprecated */
    public function addPublication(Publication $publication): void
    {
        $this->attachments[] = new ValueObject\Attachment(
            $publication->getContentType()->getContentType(),
            $publication->getOriginal(),
            base64_encode(stream_get_contents($publication->getObject()->first()->getObject()))
        );
    }

    /** @deprecated */
    public function setMailingContact(\Mailing\Entity\Contact $mailingContact): void
    {
        $this->mailingContact = $mailingContact;
    }

    /** @deprecated */
    public function addTemplateVariables(array $variables): void
    {
        foreach ($variables as $variable => $value) {
            $this->setTemplateVariable($variable, $value);
        }
    }

    /** @deprecated */
    public function setTemplateVariable(string $variable, $value): void
    {
        if (! is_bool($value) && ! $value instanceof DateTime) {
            $value = (string)$value;
        }

        $this->templateVariables[$this->underscore($variable)] = $value;
    }

    /** @deprecated */
    private function underscore(string $name): string
    {
        return strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', $name));
    }
}
