<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\Booking\Decline;
use App\Mail\GuestBookingAcceptedMail;
use App\Mail\GuestBookingDeclinedMail;
use App\Models\Booking;
use App\Services\Contracts\GoogleCalendarService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ApprovalController extends Controller
{
    public function show(Booking $booking): View
    {
        return view('approval.show', [
            'booking' => $booking,
            'approveUrl' => route('approve.accept', $booking),
            'declineUrl' => route('approve.decline', $booking),
            'signedUntil' => $booking->approval_expires_at->toDayDateTimeString(),
        ]);
    }

    public function accept(Booking $booking, GoogleCalendarService $google): RedirectResponse
    {
        if ($booking->status === BookingStatus::ACCEPTED) {
            return back()->with('ok', 'This booking was already accepted.');
        } else if ($booking->status === BookingStatus::DECLINED) {
            return back()->with('ok', 'This booking was already declined.');
        } else if ($booking->status !== BookingStatus::PENDING) {
            return back()->withErrors(['booking' => 'Booking is not pending anymore.']);
        }

        DB::transaction(function () use ($booking, $google) {
            $booking->status = BookingStatus::ACCEPTED;
            $booking->save();

            try {
                $eventId = $google->createEvent($booking);
                $booking->forceFill(['google_event_id' => $eventId])->save();
            } catch (Throwable $e) {
                report($e);
            }

            // Email the guest (queued)
            Mail::to($booking->guest_email)->queue(
                (new GuestBookingAcceptedMail($booking /* , $icsPath if you add it here */))
                    ->onQueue('booking-email')
                    ->afterCommit()
            );
        });

        return back()->with('ok', 'Booking accepted. Guest will be notified shortly.');
    }

    public function decline(Decline $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validated();

        if ($booking->status === BookingStatus::ACCEPTED) {
            return back()->with('ok', 'This booking was already accepted.');
        } else if ($booking->status === BookingStatus::DECLINED) {
            return back()->with('ok', 'This booking was already declined.');
        } else if ($booking->status !== BookingStatus::PENDING) {
            return back()->withErrors(['booking' => 'Booking is not pending anymore.']);
        }

        DB::transaction(function () use ($booking, $validated) {
            $booking->status = BookingStatus::DECLINED;
            $booking->admin_comment = $validated['admin_comment'];
            $booking->save();

            // Email the guest (queued)
            Mail::to($booking->guest_email)->queue(
                (new GuestBookingDeclinedMail($booking))
                    ->onQueue('booking-email')
                    ->afterCommit()
            );
        });

        return back()->with('ok', 'Booking declined. Guest will be notified.');
    }
}
