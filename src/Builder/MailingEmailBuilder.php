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

use Contact\Entity\Contact;
use Contact\Service\ContactService;
use Deeplink\Service\DeeplinkService;
use General\Options\EmailOptions;
use General\Options\ModuleOptions;
use Mailing\Entity\Mailing;
use Mailing\Service\MailingService;
use RuntimeException;

/**
 * Class EmailService
 * @package Mailing\Service
 */
final class MailingEmailBuilder extends EmailBuilder
{
    private Mailing $mailing;
    private ModuleOptions $moduleOptions;
    private MailingService $mailingService;

    public function __construct(
        Mailing $mailing,
        EmailOptions $emailOptions,
        ModuleOptions $moduleOptions,
        MailingService $mailingService,
        ContactService $contactService,
        DeeplinkService $deeplinkService
    ) {
        parent::__construct($emailOptions, $mailingService, $contactService, $deeplinkService);

        $this->mailingService = $mailingService;
        $this->mailing        = $mailing;
        $this->moduleOptions  = $moduleOptions;
    }

    public function renderEmail(): void
    {
        $this->setFrom($this->mailing->getSender()->getSender(), $this->mailing->getSender()->getEmail());

        $this->emailCampaign = (string)$this->mailing->getMailing();
        $this->template      = $this->mailing->getTemplate();

        //Add the attachments
        foreach ($this->mailing->getAttachment() as $attachment) {
            $this->addAttachment(
                $attachment->getContentType(),
                $attachment->getFilename(),
                stream_get_contents($attachment->getAttachment())
            );

            rewind($attachment->getAttachment());
        }

        $this->setSender($this->mailing->getSender(), $this->mailing->getContact());
        $this->renderSubject($this->mailing->getMailSubject());
        $this->renderBody($this->mailing->getMailHtml());
    }

    public function sendMailingToMailingContact(\Mailing\Entity\Contact $mailingContact): void
    {
        $this->addContactTo($mailingContact->getContact());
        $this->mailingContact = $mailingContact;

        if ($this->mailing->hasDeeplink()) {
            $this->setDeeplink(
                $this->mailing->getDeeplink()->getTarget()->getRoute(),
                $mailingContact->getContact(),
                $this->mailing->getDeeplink()->getKeyId()
            );
        }

        //Parse the unsubscribe link
        if (! $this->mailing->disrespectOptIn()) {
            $link = '%s/community/mailing/manage-subscriptions/%s.html';
            $unsubscribe = sprintf($link, $this->moduleOptions->getServerUrl(), $mailingContact->getContact()->getHash());

            $this->setTemplateVariable('unsubscribe', $unsubscribe);
            $this->addHeader('List-Unsubscribe', '<' . trim($unsubscribe) . '>');
        }

        $this->mailingService->registerSend($mailingContact->getMailing(), $mailingContact->getContact());
    }

    public function sendMailingToContact(Contact $contact): void
    {
        $this->addContactTo($contact);

        if ($this->mailing->hasDeeplink()) {
            $this->setDeeplink(
                $this->mailing->getDeeplink()->getTarget()->getRoute(),
                $contact,
                $this->mailing->getDeeplink()->getKeyId()
            );
        }
    }

    public function cannotRenderBodyReason(): ?string
    {
        return $this->renderTwigTemplate($this->mailing->getMailHtml());
    }

    public function cannotRenderSubjectReason(): ?string
    {
        return $this->renderTwigTemplate($this->mailing->getMailSubject());
    }

    public function setPersonal(bool $personal): EmailBuilder
    {
        throw new RuntimeException('It is not allowed to make a mailing non-personal');
    }
}
