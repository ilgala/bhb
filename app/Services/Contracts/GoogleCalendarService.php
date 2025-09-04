<?php

namespace App\Services\Contracts;

use App\Models\Booking;
use DateTimeInterface;

interface GoogleCalendarService
{
    public function createEvent(Booking $booking): string;

    public function deleteEvent(?string $eventId): void;

    public function hasConflict(DateTimeInterface $start, DateTimeInterface $end): bool;
}
