<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\Booking\Decline;
use App\Models\Booking;
use App\Services\Contracts\BookingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {}

    public function show(Booking $booking): View
    {
        return view('approval.show', [
            'booking' => $booking,
            'approveUrl' => route('approve.accept', $booking),
            'declineUrl' => route('approve.decline', $booking),
            'signedUntil' => $booking->approval_expires_at->toDayDateTimeString(),
        ]);
    }

    public function accept(Booking $booking): RedirectResponse
    {
        if ($booking->status === BookingStatus::ACCEPTED) {
            return back()->with('ok', 'This booking was already accepted.');
        } elseif ($booking->status === BookingStatus::DECLINED) {
            return back()->with('ok', 'This booking was already declined.');
        } elseif ($booking->status !== BookingStatus::PENDING) {
            return back()->withErrors(['booking' => 'Booking is not pending anymore.']);
        }

        DB::transaction(function () use ($booking) {
            $this->bookingService->accept($booking);
        });

        return back()->with('ok', 'Booking accepted. Guest will be notified shortly.');
    }

    public function decline(Decline $request, Booking $booking): RedirectResponse
    {
        if ($booking->status === BookingStatus::ACCEPTED) {
            return back()->with('ok', 'This booking was already accepted.');
        } elseif ($booking->status === BookingStatus::DECLINED) {
            return back()->with('ok', 'This booking was already declined.');
        } elseif ($booking->status !== BookingStatus::PENDING) {
            return back()->withErrors(['booking' => 'Booking is not pending anymore.']);
        }

        DB::transaction(function () use ($booking, $request) {
            $this->bookingService->decline($booking, $request->validated());
        });

        return back()->with('ok', 'Booking declined. Guest will be notified.');
    }
}
