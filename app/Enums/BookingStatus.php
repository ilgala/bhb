<?php

namespace App\Enums;

enum BookingStatus
{
    case PENDING;
    case ACCEPTED;
    case DECLINED;
    case CANCELED;

    public static function values(): array
    {
        return [
            BookingStatus::PENDING->name,
            BookingStatus::ACCEPTED->name,
            BookingStatus::DECLINED->name,
            BookingStatus::CANCELED->name,
        ];
    }

    public static function from(string $status): BookingStatus
    {
        return match (strtoupper($status)) {
            BookingStatus::PENDING->name => BookingStatus::PENDING,
            BookingStatus::ACCEPTED->name => BookingStatus::ACCEPTED,
            default => throw new \RuntimeException("Unknown status: $status"),
        };
    }
}
