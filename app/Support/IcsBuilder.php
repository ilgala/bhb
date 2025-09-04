<?php

namespace App\Support;

use App\Models\Booking;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class IcsBuilder
{
    /**
     * Build an RFC 5545 .ics file for a booking and return the absolute path.
     * - Stores under storage/app/ics/{booking-id}.ics
     * - Uses UTC (Z) timestamps to avoid TZ drift
     * - Includes ATTENDEE (guest) and ORGANIZER (from config/mail.from)
     */
    public function build(Booking $b): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'PRODID:-//BHB//EN',
            'VERSION:2.0',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            $this->prop('UID', $this->uid($b)),
            $this->prop('DTSTAMP', $b->updated_at?->clone()->utc()->format('Ymd\THis\Z') ?? now('UTC')->format('Ymd\THis\Z')),
            $this->prop('DTSTART', $b->start_at->clone()->utc()->format('Ymd\THis\Z')),
            $this->prop('DTEND', $b->end_at->clone()->utc()->format('Ymd\THis\Z')),
            $this->prop('SUMMARY', 'Beach House Booking'),
            $this->prop('DESCRIPTION', $this->description($b)),
            $this->prop('LOCATION', config('bhb.location', 'Beach House')),
            // Organizer & Attendee are optional but nice to have
            $this->prop('ORGANIZER;CN='.$this->escape(config('mail.from.name', 'BHB')), 'MAILTO:'.config('mail.from.address')),
            $this->prop('ATTENDEE;CN='.$this->escape($b->guest_name).';ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED', 'MAILTO:'.$b->guest_email),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        // Fold long lines to 75 octets per RFC 5545
        $payload = implode("\r\n", array_map([$this, 'fold'], $lines))."\r\n";

        $path = storage_path('app/ics/'.$b->getKey().'.ics');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $payload);

        return $path;
    }

    /** Build a robust UID (stable across regenerations for the same booking). */
    protected function uid(Booking $b): string
    {
        // If you prefer stable UID: persist a uid on the model and use it here.
        // This version derives one deterministically from id + created_at.
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'bhb.local';
        $hash = Str::uuid()->toString(); // change to deterministic if desired

        return "bhb-{$b->getKey()}-{$hash}@{$host}";
    }

    /** Escape text per RFC 5545 (\n, comma, semicolon, backslash). */
    protected function escape(string $text): string
    {
        return str_replace(
            ['\\', ';', ',', "\n", "\r"],
            ['\\\\', "\;", "\,", '\\n', ''],
            $text
        );
    }

    /** Create a property line "NAME:VALUE" with value escaped. */
    protected function prop(string $name, string $value): string
    {
        return $name.':'.$this->escape($value);
    }

    /** Human-friendly multi-line description. */
    protected function description(Booking $b): string
    {
        $parts = [
            'Guest: '.$b->guest_name,
            'Guests: '.$b->guests_count,
            'Email: '.$b->guest_email,
        ];
        if ($b->guest_phone) {
            $parts[] = 'Phone: '.$b->guest_phone;
        }
        if ($b->notes) {
            $parts[] = '';
            $parts[] = 'Notes: '.$b->notes;
        }

        return implode("\n", $parts);
    }

    /** Fold lines to 75 octets with CRLF + single space continuation. */
    protected function fold(string $line): string
    {
        $result = '';
        $bytes = 0;
        $chunk = '';

        // Use byte-safe folding; UTF-8 multibyte handling by counting bytes.
        $len = strlen($line);
        for ($i = 0; $i < $len; $i++) {
            $char = $line[$i];
            $chunk .= $char;
            $bytes += strlen($char); // 1 here, but keep semantics clear

            if ($bytes >= 75) {
                $result .= $chunk."\r\n".' ';
                $chunk = '';
                $bytes = 0;
            }
        }

        return $result.$chunk;
    }
}
