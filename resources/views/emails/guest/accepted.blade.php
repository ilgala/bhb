<x-mail::message>
# Your booking is confirmed 🎉

**Dates:** {{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}
**Guests:** {{ $booking->guests_count }}

We’ve attached a calendar file (.ics) so you can add the stay to your calendar.

@if($booking->notes)
**Your notes:**
{{ $booking->notes }}
@endif

If you need to make changes, just reply to this email.

Thanks,
**Beach House**
</x-mail::message>
