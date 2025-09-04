<?php

namespace App\Enums;

enum UserRole
{
    case ADMIN;
    case STANDARD;

    public static function values(): array
    {
        return [
            self::ADMIN->name,
            self::STANDARD->name,
        ];
    }
}
