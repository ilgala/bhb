<x-mail::message>
# Booking approval needed

* **Guest:** {{ $booking->guest_name }}
* **Email:** {{ $booking->guest_email }}
* **Phone:** {{ $booking->guest_phone ?? '—' }}
* **Dates:** {{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}
* **Guests:** {{ $booking->guests_count }}

@if($booking->notes)
**Notes:**
{{ $booking->notes }}
@endif

<x-mail::button :url="$approvalUrl">
    Review & Approve / Decline
</x-mail::button>

This link expires on **{{ $booking->approval_expires_at->toDayDateTimeString() }}**.

Thanks,
**BHB**
</x-mail::message>
