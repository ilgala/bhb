<x-mail::message>
# Booking canceled

We’re sorry to let you know that your Beach House booking has been **canceled**.

**Dates:** {{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}
**Guests:** {{ $booking->guests_count }}

@if($booking->admin_comment)
**Note from the host:**
{{ $booking->admin_comment }}
@endif

If you’d like to choose different dates, you can submit a new request anytime on our booking page.

Thanks,
**Beach House**
</x-mail::message>
