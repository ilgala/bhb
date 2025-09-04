<?php

return [
    'max_guests' => env('BOOKING_MAX_GUESTS', 10),
    'approval_ttl' => (int) env('BOOKING_APPROVAL_TTL_HOURS', 24),
    'location' => env('BHB_LOCATION', 'Beach House'),
    'google' => [
        // Either inline JSON or a path to the JSON file
        'credentials_json' => env('GOOGLE_CREDENTIALS_JSON'),
        'calendar_id' => env('GOOGLE_CALENDAR_ID', 'primary'),
        'timezone' => env('APP_TIMEZONE', 'Europe/Rome'),
        // If using a service account with domain-wide delegation:
        'impersonate' => env('GOOGLE_IMPERSONATE', null), // user@domain.com or null
    ],
];
