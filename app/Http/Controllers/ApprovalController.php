<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\Contracts\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    public function show(Request $request, Booking $booking)
    {
        return view('approval.show', [
            'booking' => $booking,
            'approveUrl' => route('approve.accept', $booking),
            'declineUrl' => route('approve.decline', $booking),
            'signedUntil' => $booking->approval_expires_at?->toDayDateTimeString(),
        ]);
    }

    public function accept(
        Request $request,
        Booking $booking,
        GoogleCalendarService $google
    ) {
        if ($booking->status === BookingStatus::ACCEPTED) {
            return back()->with('ok', 'This booking was already accepted.');
        }

        if ($booking->status !== BookingStatus::PENDING) {
            return back()->withErrors(['booking' => 'Booking is not pending anymore.']);
        }

        DB::transaction(function () use ($booking, $google) {
            $booking->status = BookingStatus::ACCEPTED;
            $booking->save();

            try {
                $eventId = $google->createEvent($booking);
                $booking->forceFill(['google_event_id' => $eventId])->save();
            } catch (\Throwable $e) {
                report($e);
            }

            // Email the guest (queued)
            Mail::to($booking->guest_email)->queue(
                (new GuestBookingAcceptedMail($booking /* , $icsPath if you add it here */))
                    ->onQueue('mail')
                    ->afterCommit()
            );
        });

        return back()->with('ok', 'Booking accepted. Guest will be notified shortly.');
    }

    public function decline(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'admin_comment' => ['required', 'string', 'max:2000'],
        ]);

        if ($booking->status === BookingStatus::DECLINED) {
            return back()->with('ok', 'This booking was already declined.');
        }

        if ($booking->status !== BookingStatus::PENDING) {
            return back()->withErrors(['booking' => 'Booking is not pending anymore.']);
        }

        DB::transaction(function () use ($booking, $validated) {
            $booking->status = BookingStatus::DECLINED;
            $booking->admin_comment = $validated['admin_comment'];
            $booking->save();

            // Email the guest (queued)
            Mail::to($booking->guest_email)->queue(
                (new GuestBookingDeclinedMail($booking))
                    ->onQueue('mail')
                    ->afterCommit()
            );
        });

        return back()->with('ok', 'Booking declined. Guest will be notified.');
    }
}
