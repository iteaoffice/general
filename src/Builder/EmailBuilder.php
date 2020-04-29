<?php

/**
 * Jield BV all rights reserved
 *
 * @category  Mailing
 *
 * @author    Johan van der Heide <info@jield.nl>
 * @copyright Copyright (c) 2018 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace General\Builder;

use Admin\Entity\User;
use Contact\Entity\Contact;
use Contact\Service\ContactService;
use DateTime;
use Deeplink\Service\DeeplinkService;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use General\Options\EmailOptions;
use General\ValueObject;
use General\ValueObject\Link\LinkDecoration;
use InvalidArgumentException;
use Mailing\Entity\Sender;
use Mailing\Entity\Template;
use Mailing\Service\MailingService;
use Publication\Entity\Publication;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Class EmailService
 * @package Mailing\Service
 */
abstract class EmailBuilder
{
    protected string $subject;
    protected string $emailCampaign;
    protected ArrayCollection $templateVariables;
    protected string $textPart;
    protected string $htmlPart;
    protected Template $template;
    protected ?\Mailing\Entity\Contact $mailingContact = null;
    private Sender $sender;
    private Contact $contact;
    private bool $personal = true;
    private bool $isDevelopment;
    /** @var string string */
    private string $defaultEmailaddress;
    private $fromEmail;
    /** @var ValueObject\Recipient */
    private ValueObject\Recipient $from;
    /** @var ValueObject\Recipient[] */
    private array $to = [];
    /** @var ValueObject\Recipient[] */
    private array $cc = [];
    /** @var ValueObject\Recipient[] */
    private array $bcc = [];
    /** @var ValueObject\Attachment[] */
    private array $attachments = [];
    /** @var ValueObject\Ical[] */
    private array $invitations = [];
    /** @var ValueObject\Attachment[] */
    private array $inlinedAttachments = [];
    /** @var ValueObject\Header[] */
    private array $headers = [];
    private DeeplinkService $deeplinkService;
    private ContactService $contactService;

    public function __construct(
        EmailOptions $emailOptions,
        MailingService $mailingService,
        ContactService $contactService,
        DeeplinkService $deeplinkService = null
    ) {
        $this->isDevelopment     = $emailOptions->isDevelopment();
        $this->templateVariables = new ArrayCollection();
        $this->contactService    = $contactService;

        $this->setSender($mailingService->findDefaultSender());
        $this->setTemplate($mailingService->findDefaultTemplate());

        $this->defaultEmailaddress = $emailOptions->getEmailAddress();

        if (null !== $deeplinkService) {
            $this->deeplinkService = $deeplinkService;
        }
    }

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function setSender(Sender $setSender = null, Contact $ownerOrLoggedInContact = null): EmailBuilder
    {
        //Via this function it is possible to overrule the sender, but if no value for the sender is given
        //We do a fallback to the default sender
        if (null !== $setSender) {
            $this->sender = $setSender;
        }

        $sender = $this->sender;

        switch ($sender) {
            case $sender->isLoggedInContact():
            case $sender->isOwner():
                if (null === $ownerOrLoggedInContact) {
                    throw new InvalidArgumentException(
                        'When selecting LoggedInContact or Owner as sender, the contact has to be set'
                    );
                }
                $this->setTemplateVariables(
                    [
                        'sender_email' => $ownerOrLoggedInContact->getEmail(),
                        'sender_name'  => $ownerOrLoggedInContact->getDisplayName()
                    ]
                );

                //Create a default from
                $this->fromEmail = $ownerOrLoggedInContact->getEmail();
                $this->from      = new ValueObject\Recipient(
                    $ownerOrLoggedInContact->getEmail(),
                    $ownerOrLoggedInContact->getDisplayName(),
                );
                break;
            case null:
            default:
                $this->setTemplateVariables(
                    [
                        'sender_email' => $sender->getEmail(),
                        'sender_name'  => $sender->getSender()
                    ]
                );

                //Create a default from
                $this->fromEmail = $sender->getEmail();
                $this->from      = new ValueObject\Recipient($sender->getEmail(), $sender->getSender());
                break;
        }

        return $this;
    }

    public function getMailjetBody(string $identifier): ValueObject\Body
    {
        $messages = [];

        $message    = new ValueObject\Email(
            $this->from->toArray(),
            $this->getTo(),
            $this->getCC(),
            $this->getBCC(),
            $this->subject,
            $this->textPart,
            $this->htmlPart,
            $identifier,
            '',
            'enabled',
            'enabled',
            $this->emailCampaign,
            $this->getAttachments(),
            $this->getInlinedAttachments(),
            $this->getHeaders()
        );
        $messages[] = $message->toArray();

        return new ValueObject\Body($messages);
    }

    public function getTo(): array
    {
        $to = [];
        foreach ($this->to as $singleTo) {
            $to[] = $singleTo->toArray($this->isDevelopment, $this->defaultEmailaddress);
        }

        return $to;
    }

    public function getCC(): array
    {
        $cc = [];
        foreach ($this->cc as $singleCC) {
            $cc[] = $singleCC->toArray($this->isDevelopment, $this->defaultEmailaddress);
        }

        return $cc;
    }

    public function getBCC(): array
    {
        $bcc = [];
        foreach ($this->bcc as $singleBCC) {
            $bcc[] = $singleBCC->toArray($this->isDevelopment, $this->defaultEmailaddress);
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

    private function getInlinedAttachments(): array
    {
        $inlinedAttachments = [];
        foreach ($this->inlinedAttachments as $singleAttachment) {
            $inlinedAttachments[] = $singleAttachment->toArray();
        }

        return $inlinedAttachments;
    }

    private function getHeaders(): array
    {
        $headers = [];
        foreach ($this->headers as $singleHeader) {
            $headers += $singleHeader->toArray();
        }

        return $headers;
    }

    public function addAttachment(string $contentType, string $fileName, string $content): void
    {
        $this->attachments[] = new ValueObject\Attachment(
            $contentType,
            $fileName,
            base64_encode($content),
            $content
        );
    }

    public function addHeader(string $header, string $content): void
    {
        $this->headers[] = new ValueObject\Header($header, $content);
    }

    public function addInvitation(
        string $id,
        DateTime $startDate,
        DateTime $endDate,
        string $title,
        string $summary,
        string $location,
        Contact $organiser,
        Contact $participant
    ): void {
        $this->invitations[] = new ValueObject\Ical(
            $id,
            $startDate,
            $endDate,
            $title,
            $summary,
            $location,
            $organiser,
            $participant
        );
    }

    public function addContactTo(Contact $contact): EmailBuilder
    {
        //As we add the to contact in the builder, we automatically extract the contact details in the template variables
        //Only extract contact details when mailing is personal
        if ($this->personal) {
            $this->setTemplateVariables(
                [
                    'attention' => $this->contactService->parseAttention($contact),
                    'firstname' => $contact->getFirstName(),
                    'lastname'  => $contact->getLastName(),
                    'fullname'  => $contact->getDisplayName(),
                    'email'     => $contact->getEmail(),
                    'signature' => $this->contactService->parseSignature($contact)
                ]
            );

            if ($this->contactService->hasOrganisation($contact)) {
                $this->setTemplateVariable('country', (string)$this->contactService->parseCountry($contact)->getCountry());
                $this->setTemplateVariable('organisation', (string)$this->contactService->parseOrganisation($contact));
            }
        }

        $this->addTo($contact->getEmail(), $contact->parseFullName());

        return $this;
    }

    public function setTemplateVariables(array $variables): EmailBuilder
    {
        foreach ($variables as $key => $value) {
            $this->setTemplateVariable($key, $value);
        }

        return $this;
    }

    public function setTemplateVariable($key, $value): EmailBuilder
    {
        $this->templateVariables->set($key, $value);

        return $this;
    }

    public function addTo(string $email, string $name = null): EmailBuilder
    {
        if ($this->personal && count($this->to) > 0) {
            throw new InvalidArgumentException('Impossible to add more recipients to an personal email');
        }

        $to = new ValueObject\Recipient($email, $name);

        if ($to->isValid()) {
            $this->to[] = $to;
        }

        return $this;
    }

    public function addContactCC(Contact $contact): EmailBuilder
    {
        $this->addCC($contact->getEmail(), $contact->parseFullName());

        return $this;
    }

    public function addCC(string $email, string $name = null): EmailBuilder
    {
        if ($this->personal) {
            throw  new InvalidArgumentException('Impossible to add CC recipients to an personal email');
        }

        $cc = new ValueObject\Recipient($email, $name);

        if ($cc->isValid()) {
            $this->cc[] = $cc;
        }

        return $this;
    }

    public function addContactBCC(Contact $contact): EmailBuilder
    {
        $this->contact = $contact;
        $this->addBCC($contact->getEmail(), $contact->parseFullName());

        return $this;
    }

    public function addBCC(string $email, string $name = null): EmailBuilder
    {
        if ($this->personal) {
            throw  new InvalidArgumentException('Impossible to add BCC recipients to an personal email');
        }

        $bcc = new ValueObject\Recipient($email, $name);

        if ($bcc->isValid()) {
            $this->bcc[] = $bcc;
        }

        return $this;
    }

    public function setPersonal(bool $personal): EmailBuilder
    {
        $this->personal = $personal;
        return $this;
    }

    public function setFrom(string $email, string $name): EmailBuilder
    {
        $this->setTemplateVariables(
            [
                'from_name'  => $name,
                'from_email' => $email
            ]
        );

        $this->from = new ValueObject\Recipient($email, $name);

        return $this;
    }

    public function getFirstEmailAddress(): string
    {
        return $this->to[0]->toArray()['Email'] ?? 'noreply@example.com';
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getHtmlPart(): ?string
    {
        return $this->htmlPart;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function getTextPart(): ?string
    {
        return $this->textPart;
    }

    public function getAmountOfAttachments(): int
    {
        return count($this->attachments);
    }

    public function getEmailCampaign(): ?string
    {
        return $this->emailCampaign;
    }

    public function addPublication(Publication $publication): void
    {
        $this->attachments[] = new ValueObject\Attachment(
            $publication->getContentType()->getContentType(),
            $publication->getOriginal(),
            base64_encode(stream_get_contents($publication->getObject()->first()->getObject()))
        );
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): EmailBuilder
    {
        $this->template = $template;
        return $this;
    }

    public function addDeeplink(string $route, string $templateVariable = 'deeplink', Contact $contact = null, string $email = null, $key = null): void
    {
        if (! $this->personal) {
            throw new InvalidArgumentException('It is not possible to add a deeplink for a non-personal email');
        }
        if (null === $contact && null === $email) {
            throw new InvalidArgumentException('Contact object and email cannot be null together');
        }
        //Create a target
        $target = $this->deeplinkService->createTargetFromRoute($route);

        $deeplink = $this->deeplinkService->createDeeplink(
            $target,
            $contact,
            $email,
            $key
        );

        $this->setTemplateVariable(
            $templateVariable,
            $this->deeplinkService->parseDeeplinkUrl(
                $deeplink,
                LinkDecoration::SHOW_RAW
            )
        );
    }

    abstract public function renderEmail(): void;

    public function renderTwigTemplate(string $template): ?string
    {
        try {
            //Create a second template in which the content of the email is parsed and render the content in
            (new Environment(
                new ArrayLoader(
                    ['rendered_content' => $template]
                )
            ))->render('rendered_content', $this->templateVariables->toArray());

            return null;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function hasMailingContact(): bool
    {
        return null !== $this->mailingContact;
    }

    public function getMailingContact(): ?\Mailing\Entity\Contact
    {
        return $this->mailingContact;
    }

    public function setDeeplink(string $route, Contact $user, $key = null): void
    {
        if (! $this->personal) {
            throw new \InvalidArgumentException('It is not possible to add a deeplink for a non-personal email');
        }
        //Create a target
        $target = $this->deeplinkService->createTargetFromRoute($route);

        $deeplink = $this->deeplinkService->createDeeplink(
            $target,
            $user,
            null,
            $key
        );

        $this->setTemplateVariable(
            'deeplink',
            $this->deeplinkService->parseDeeplinkUrl(
                $deeplink,
                LinkDecoration::SHOW_RAW
            )
        );
    }

    protected function renderSubject(string $mailSubject): void
    {
        //Tempfix since we are still in the old and new template system
        $mailSubject = str_replace(['[', ']'], ['{{', '}}'], $mailSubject);


        try {
            //Create a Twig Template on the fly with the template source content
            $subjectTemplate = new Environment(
                new ArrayLoader(
                    ['template_subject' => str_replace(['[', ']'], ['{{', '}}'], $this->template->getSubject())]
                )
            );
            //Create a second template in which the content of the email is parsed and render the content in
            $mailSubject = (new Environment(
                new ArrayLoader(
                    ['email_subject' => $mailSubject]
                )
            ))->render('email_subject', $this->templateVariables->toArray());

            $this->setTemplateVariable('subject', $mailSubject);

            //Render the $mailBody in the content of the main template
            $this->subject = $subjectTemplate->render(
                'template_subject',
                $this->templateVariables->toArray()
            );
        } catch (Exception $e) {
            $this->subject = sprintf(
                'Something went wrong with the merge of the subject. Error message: %s',
                $e->getMessage()
            );
        }
    }

    protected function renderBody(string $bodyText): void
    {
        try {
            //Create a Twig Template on the fly with the template source content
            $htmlTemplate = new Environment(
                new ArrayLoader(
                    [$this->template->parseName() => $this->template->parseSourceContent()]
                )
            );

            //Create a second template in which the content of the email is parsed and render the content in
            $mailBody = (new Environment(
                new ArrayLoader(
                    ['email_content' => $bodyText]
                )
            ))->render('email_content', $this->templateVariables->toArray());

            $this->setTemplateVariable('content', $mailBody);

            $htmlPart = $htmlTemplate->render(
                $this->template->parseName(),
                $this->templateVariables->toArray()
            );
            $this->embedImagesAsAttachment($htmlPart);

            //Render the $mailBody in the content of the main template
            $this->htmlPart = $htmlPart;
            $this->textPart = strip_tags($mailBody);
        } catch (Exception $e) {
            $this->htmlPart = $this->textPart = sprintf(
                'Something went wrong with the merge of the body text. Error message: %s',
                $e->getMessage()
            );
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
}
