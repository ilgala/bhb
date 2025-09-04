<x-mail::message>
# About your booking request

Thanks for your interest in staying with us. Unfortunately, we can’t confirm these dates.

@if($booking->admin_comment)
**Reason from the host:**
{{ $booking->admin_comment }}
@endif

You can try different dates anytime via our booking page.

Thanks,
**Beach House**
</x-mail::message>
