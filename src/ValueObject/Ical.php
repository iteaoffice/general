<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace General\ValueObject;

use Contact\Entity\Contact;
use DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;

final class Ical
{
    private string $id;
    private DateTime $startDate;
    private DateTime $endDate;
    private string $title;
    private string $summary;
    private string $location;
    private Contact $organiser;
    private Contact $participant;

    public function __construct(
        string $id,
        DateTime $startDate,
        DateTime $endDate,
        string $title,
        string $summary,
        string $location,
        Contact $organiser,
        Contact $participant
    ) {
        $this->id          = $id;
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
        $this->title       = $title;
        $this->summary     = $summary;
        $this->location    = $location;
        $this->organiser   = $organiser;
        $this->participant = $participant;
    }

    public function toMimePart(): Part
    {
        $part = new Part((string)$this->getCalendar());
        $part->setType('text/calendar');
        $part->setDisposition(Mime::DISPOSITION_ATTACHMENT);
        $part->setEncoding(Mime::ENCODING_BASE64);
        $part->setFileName('meeting.ics');

        return $part;
    }

    private function getCalendar(): Component
    {
        $event = new \Eluceo\iCal\Domain\Entity\Event();

        $event->setLocation(new Location($this->location));

        $event->setOccurrence(
            new TimeSpan(
                new \Eluceo\iCal\Domain\ValueObject\DateTime($this->startDate, false),
                new \Eluceo\iCal\Domain\ValueObject\DateTime($this->endDate, false)
            )
        );

        $event->setOrganizer(
            new \Eluceo\iCal\Domain\ValueObject\Organizer(
                new EmailAddress($this->organiser->getEmail()),
                $this->organiser->parseFullName()
            )
        );

        $event->setSummary($this->summary);
        $event->setDescription($this->title);

        $calendar = new \Eluceo\iCal\Domain\Entity\Calendar([$event]);

        return (new CalendarFactory())->createCalendar($calendar);
    }
}
