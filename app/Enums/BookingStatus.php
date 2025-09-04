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
}
