<?php

namespace App\Enums;

use RuntimeException;

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

    public static function from(string $role): UserRole
    {
        return match (strtoupper($role)) {
            self::ADMIN->name => UserRole::ADMIN,
            self::STANDARD->name => UserRole::STANDARD,
            default => throw new RuntimeException("Unknown role: $role")
        };
    }
}
