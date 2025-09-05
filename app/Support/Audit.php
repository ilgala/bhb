<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class Audit
{
    public static function record(Model $auditable, string $action, array $meta = [], ?int $userId = null): void
    {
        $auditable->audits()->create([
            'user_id' => $userId,
            'action' => $action,
            'metadata' => $meta ?: null,
        ]);
    }
}
