<?php

namespace App\Services\Contracts;

use App\Dto\Booking as BookingDto;
use App\Models\Booking;
use DateTimeInterface;

interface BookingService
{
    public function bookingExists(DateTimeInterface $startAt, DateTimeInterface $endAt): bool;

    public function store(BookingDto $dto): Booking;

    public function accept(Booking $booking): Booking;

    public function decline(Booking $booking, mixed $validated): Booking;
}
