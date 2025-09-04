<?php

namespace App\Services;

use App\Models\Booking;
use App\Services\Contracts\GoogleCalendarService as GoogleCalendarServiceContract;
use DateTimeInterface;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleEvent;
use Google\Service\Calendar\FreeBusyRequest;
use Google\Service\Calendar\FreeBusyRequestItem;
use Google\Service\Exception;
use Illuminate\Support\Str;

class GoogleCalendarService implements GoogleCalendarServiceContract
{
    private GoogleCalendar $calendar;

    private string $calendarId;

    private string $tz;

    public function __construct()
    {
        if (app()->isProduction()) {
            $client = new GoogleClient;
            $credentials = config('bhb.google.credentials_json');
            if (is_string($credentials) && is_file($credentials)) {
                $client->setAuthConfig($credentials);
            } else {
                $client->setAuthConfig(json_decode($credentials, true));
            }

            $client->setScopes([GoogleCalendar::CALENDAR]);
            if ($subject = config('bhb.google.impersonate')) {
                $client->setSubject($subject);
            }

            $this->calendar = new GoogleCalendar($client);
            $this->calendarId = config('bhb.google.calendar_id', 'primary');
            $this->tz = config('bhb.google.timezone', 'UTC');
        }
    }

    public function createEvent(Booking $booking): string
    {
        if (app()->isLocal()) {
            return Str::random(26).'@google.com';
        }

        // RFC3339 datetimes with timezone
        $start = [
            'dateTime' => $this->rfc3339($booking->start_at),
            'timeZone' => $this->tz,
        ];
        $end = [
            'dateTime' => $this->rfc3339($booking->end_at),
            'timeZone' => $this->tz,
        ];

        $summary = 'Beach House Booking';
        $desc = trim(sprintf(
            "Guest: %s\nGuests: %s\nEmail: %s\nPhone: %s",
            $booking->guest_name,
            $booking->guests_count,
            $booking->guest_email,
            $booking->guest_phone ?? '-'
        ));

        $event = new GoogleEvent([
            'summary' => $summary,
            'description' => $desc,
            'start' => $start,
            'end' => $end,
            'location' => config('bhb.location', 'Beach House'),
        ]);

        $created = $this->calendar->events->insert($this->calendarId, $event, ['sendUpdates' => 'none']);

        return $created->id;
    }

    public function deleteEvent(?string $eventId): void
    {
        if (app()->isLocal()) {
            return;
        }

        if (! $eventId) {
            return;
        }

        try {
            $this->calendar->events->delete($this->calendarId, $eventId, ['sendUpdates' => 'none']);
        } catch (Exception $e) {
            if (! in_array($e->getCode(), [404, 410], true)) {
                throw $e;
            }
        }
    }

    public function hasConflict(DateTimeInterface $start, DateTimeInterface $end): bool
    {
        if (app()->isLocal()) {
            return false;
        }

        $req = new FreeBusyRequest;
        $req->setTimeMin($this->rfc3339($start));
        $req->setTimeMax($this->rfc3339($end));
        $req->setTimeZone($this->tz);

        $item = new FreeBusyRequestItem;
        $item->setId($this->calendarId);
        $req->setItems([$item]);

        $resp = $this->calendar->freebusy->query($req);
        $cal = $resp->getCalendars()[$this->calendarId] ?? null;
        if (! $cal) {
            return false;
        }

        $busy = $cal->getBusy() ?? [];

        return count($busy) > 0;
    }

    private function rfc3339(DateTimeInterface $dt): string
    {
        return $dt->format(DateTimeInterface::RFC3339);
    }
}
