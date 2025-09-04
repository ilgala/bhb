<?php

namespace App\Services;

use App\Dto\Booking as BookingDto;
use App\Enums\BookingStatus;
use App\Mail\AdminApprovalRequestMail;
use App\Models\Booking;
use App\Services\Contracts\BookingService as BookingServiceContract;
use App\Services\Contracts\UserService;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BookingService implements BookingServiceContract
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * Return true if there is ANY booking that conflicts with the given window.
     * Conflict is defined as:
     *  - existing.start_at BETWEEN [start, end]  (inclusive)
     *  - OR existing.end_at   BETWEEN [start, end]  (inclusive)
     *  - OR existing fully wraps the window: existing.start_at <= start AND existing.end_at >= end
     *
     * If you do NOT want “touching” windows to count as conflict (e.g., existing.end_at == start),
     * switch to strict comparisons in the whereBetween alternatives (see note below).
     */
    public function bookingExists(DateTimeInterface $startAt, DateTimeInterface $endAt): bool
    {
        return Booking::query()
            ->whereIn('status', [BookingStatus::PENDING, BookingStatus::ACCEPTED])
            ->where(function ($query) use ($startAt, $endAt) {
                $query->whereBetween('start_at', [$startAt, $endAt])            // start inside requested window
                    ->orWhereBetween('end_at', [$startAt, $endAt])                  // end inside requested window
                    ->orWhere(function ($query) use ($startAt, $endAt) {            // existing fully wraps requested
                        $query->where('start_at', '<=', $startAt)
                            ->where('end_at', '>=', $endAt);
                    });
            })
            ->exists();
    }

    public function store(BookingDto $dto): Booking
    {
        return DB::transaction(function () use ($dto) {

            $booking = new Booking;
            $booking->fill([
                ...$dto->toArray(),
                'status' => BookingStatus::PENDING,
                'approval_token' => $this->createToken(),
                'approval_expires_at' => now()->addHours(config('bhb.approval_ttl', 24)),
                'google_event_id' => null,
            ]);

            return tap($booking, function (Booking $booking) {
                $booking->save();
                $approvalUrl = $this->approvalUrl($booking);

                Mail::to($this->userService->adminUsers())->queue(
                    (new AdminApprovalRequestMail($booking, $approvalUrl))
                        ->onQueue('booking-email')  // optional
                        ->afterCommit()                   // already declared in the constructor
                );
            });
        });
    }

    protected function createToken(): string
    {
        return Str::random(40);
    }

    protected function approvalUrl(Booking $booking): string
    {
        return URL::temporarySignedRoute(
            'approve.show',
            $booking->approval_expires_at,
            ['booking' => $booking->approval_token]
        );
    }
}
