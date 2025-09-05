<?php

namespace App\Enums;

use RuntimeException;

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

    public static function from(string $status): UserStatus
    {
        return match (strtoupper($status)) {
            self::ACTIVE->name => self::ACTIVE,
            self::DEACTIVATED->name => self::DEACTIVATED,
            default => throw new RuntimeException("Unknown status: $status"),
        };
    }
}
