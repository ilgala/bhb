<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Database\Factories\BookingFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property string $guest_name
 * @property string $guest_email
 * @property string $guest_phone
 * @property int $guests_count
 * @property BookingStatus $status
 * @property string|null $notes
 * @property string|null $admin_comment
 * @property string $approval_token
 * @property Carbon $approval_expires_at
 * @property string|null $google_event_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static BookingFactory factory($count = null, $state = [])
 * @method static Builder<static>|Booking newModelQuery()
 * @method static Builder<static>|Booking newQuery()
 * @method static Builder<static>|Booking query()
 * @method static Builder<static>|Booking whereAdminComment($value)
 * @method static Builder<static>|Booking whereApprovalExpiresAt($value)
 * @method static Builder<static>|Booking whereApprovalToken($value)
 * @method static Builder<static>|Booking whereCreatedAt($value)
 * @method static Builder<static>|Booking whereEndAt($value)
 * @method static Builder<static>|Booking whereGoogleEventId($value)
 * @method static Builder<static>|Booking whereGuestCount($value)
 * @method static Builder<static>|Booking whereGuestEmail($value)
 * @method static Builder<static>|Booking whereGuestName($value)
 * @method static Builder<static>|Booking whereGuestPhone($value)
 * @method static Builder<static>|Booking whereId($value)
 * @method static Builder<static>|Booking whereNotes($value)
 * @method static Builder<static>|Booking whereStartAt($value)
 * @method static Builder<static>|Booking whereStatus($value)
 * @method static Builder<static>|Booking whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'start_at',
        'end_at',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guests_count',
        'status',
        'notes',
        'admin_comment',
        'approval_token',
        'approval_expires_at',
        'google_event_id',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'status' => BookingStatus::class,
        'approval_expires_at' => 'date',
    ];
}
