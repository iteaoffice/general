<?php
/**
 *
 */

declare(strict_types=1);

namespace General\ValueObject;

use function count;

final class Email
{
    private $from;
    private $to;
    private $cc;
    private $bcc;

    private $subject;
    private $textPart;

    private $htmlPart;

    private $customID;
    private $eventPayload;
    private $trackOpens;
    private $trackClicks;
    private $customCampaign;

    private $attachments;
    private $inlinedAttachments;
    private $headers;

    public function __construct(
        array $from,
        array $to,
        array $cc,
        array $bcc,
        string $subject,
        string $textPart,
        string $htmlPart,
        string $customID,
        string $eventPayload,
        string $trackOpens = 'enabled',
        string $trackClicks = 'enabled',
        string $customCampaign = '',
        array $attachments = [],
        array $inlinedAttachments = [],
        array $headers = []
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->subject = $subject;
        $this->textPart = $textPart;
        $this->htmlPart = $htmlPart;
        $this->customID = $customID;
        $this->eventPayload = $eventPayload;
        $this->trackOpens = $trackOpens;
        $this->trackClicks = $trackClicks;
        $this->customCampaign = $customCampaign;
        $this->attachments = $attachments;
        $this->inlinedAttachments = $inlinedAttachments;
        $this->headers = $headers;
    }

    public function isValid(): bool
    {
        return count($this->isInvalidReasons()) === 0;
    }

    public function isInvalidReasons(): array
    {
        $invalidReasons = [];

        if (count($this->from) === 0) {
            $invalidReasons[] = 'No sender given';
        }

        if (count($this->to) === 0) {
            $invalidReasons[] = 'No to given';
        }

        if ('' === $this->subject) {
            $invalidReasons[] = 'No subject given';
        }

        if ('' === $this->htmlPart) {
            $invalidReasons[] = 'No content given';
        }

        return $invalidReasons;
    }

    public function toArray(): array
    {
        $return = [
            'From'        => $this->from,
            'To'          => $this->to,
            'Subject'     => $this->subject,
            'TextPart'    => $this->textPart,
            'HTMLPart'    => $this->htmlPart,
            'TrackOpens'  => $this->trackOpens,
            'TrackClicks' => $this->trackClicks,
        ];

        if (count($this->cc) > 0) {
            $return['Cc'] = $this->cc;
        }

        if (count($this->bcc) > 0) {
            $return['Bcc'] = $this->bcc;
        }

        if (null !== $this->customID) {
            $return['CustomID'] = $this->customID;
        }

        if (null !== $this->eventPayload) {
            $return['EventPayload'] = $this->eventPayload;
        }

        if (null !== $this->customCampaign) {
            $return['CustomCampaign'] = $this->customCampaign;
        }

        if (count($this->headers) > 0) {
            $return['Headers'] = $this->headers;
        }

        if (count($this->attachments) > 0) {
            $return['Attachments'] = $this->attachments;
        }

        if (count($this->inlinedAttachments) > 0) {
            $return['InlinedAttachments'] = $this->inlinedAttachments;
        }

        return $return;
    }
}
