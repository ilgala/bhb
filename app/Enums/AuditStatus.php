<?php

namespace App\Enums;

enum AuditStatus
{
    case APPROVE;
    case DECLINE;
    case CANCEL;
    case INVITE;
    case ROLE_CHANGE;

    public static function values(): array
    {
        return [
            AuditStatus::APPROVE->name,
            AuditStatus::DECLINE->name,
            AuditStatus::CANCEL->name,
            AuditStatus::INVITE->name,
            AuditStatus::ROLE_CHANGE->name,
        ];
    }
}
