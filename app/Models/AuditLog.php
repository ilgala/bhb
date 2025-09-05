<?php

namespace App\Models;

use Database\Factories\AuditLogFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $user_id
 * @property string $booking_id
 * @property string $action
 * @property string $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static AuditLogFactory factory($count = null, $state = [])
 * @method static Builder<static>|AuditLog newModelQuery()
 * @method static Builder<static>|AuditLog newQuery()
 * @method static Builder<static>|AuditLog query()
 * @method static Builder<static>|AuditLog whereAction($value)
 * @method static Builder<static>|AuditLog whereBookingId($value)
 * @method static Builder<static>|AuditLog whereCreatedAt($value)
 * @method static Builder<static>|AuditLog whereId($value)
 * @method static Builder<static>|AuditLog whereMetadata($value)
 * @method static Builder<static>|AuditLog whereUpdatedAt($value)
 * @method static Builder<static>|AuditLog whereUserId($value)
 *
 * @mixin Eloquent
 */
class AuditLog extends Model
{
    /** @use HasFactory<AuditLogFactory> */
    use HasFactory, HasUlids;

    protected $fillable = ['action', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
