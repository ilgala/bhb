<x-mail::message>
# Welcome to BHB

Hi {{ $user->name }},

Your account has been created. Here are your temporary credentials:

**Email:** {{ $user->email }}
**Temporary password:** `{{ $plainPassword }}`

> **Important:** Change this password as soon as you log in!

<x-mail::button :url="$appUrl">
Log in to BHB
</x-mail::button>

If the button doesn’t work, copy and paste this URL into your browser:
{{ $appUrl }}

Thanks,
**BHB Team**
</x-mail::message>
