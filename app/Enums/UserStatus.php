<?php

namespace App\Enums;

enum UserStatus
{
    case ACTIVE;
    case DEACTIVATED;

    public static function values(): array
    {
        return [
            self::ACTIVE->name,
            self::DEACTIVATED->name,
        ];
    }
}
