<?php

/**
 * Jield BV all rights reserved
 *
 * @author    Johan van der Heide <info@jield.nl>
 * @copyright Copyright (c) 2020 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace General\ValueObject;

use Contact\Entity\Contact;
use DateTime;
use DateTimeZone;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Property\Event\Attendees;
use Eluceo\iCal\Property\Event\Organizer;
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
        $part = new Part($this->getCalendar()->render());
        $part->setType('text/calendar');
        $part->setDisposition(Mime::DISPOSITION_ATTACHMENT);
        $part->setEncoding(Mime::ENCODING_BASE64);
        $part->setFileName('meeting.ics');

        return $part;
    }

    private function getCalendar(): Calendar
    {
        $timeZone = new DateTimeZone('Europe/Amsterdam');

        $startDate = clone $this->startDate;
        $startDate->setTimezone($timeZone);
        $endDate = clone $this->endDate;
        $endDate->setTimezone($timeZone);

        $calendar = new Calendar('jield.nl');
        $calendar->setCalId($this->id);
        $calendar->setTimezone('Europe/Amsterdam');
        $calendar->setMethod('REQUEST');
        $calendar->setForceInspectOrOpen(true);

        $attendees = new Attendees();
        $attendees->add(
            sprintf('MAILTO:%s', $this->participant->getEmail()),
            [

                'CUTYPE'       => 'CUTYPE=INDIVIDUAL',
                'ROLE'         => 'REQ-PARTICIPANT',
                'PARTSTAT'     => 'NEEDS-ACTION',
                'RSVP'         => 'TRUE',
                'CN'           => $this->participant->getDisplayName(),
                'X-NUM-GUESTS' => 0,
            ]
        );

        $event = new Event();
        $event->setDtStart($startDate->setTimezone(new DateTimeZone('UTC')));
        $event->setDtEnd($endDate->setTimezone(new DateTimeZone('UTC')));
        $event->setLocation($this->location);

        $event->setOrganizer(
            new Organizer(
                sprintf('MAILTO:%s', $this->organiser->getEmail()),
                ['CN' => $this->organiser->getDisplayName()]
            )
        );
        $event->setAttendees($attendees);
        $event->setSummary($this->summary);
        $event->setDescription($this->title);
        $event->setUseUtc();
        $event->setUseTimezone(false);
        $calendar->addComponent($event);

        return $calendar;
    }
}
