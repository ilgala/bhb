<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-white text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">
<a href="#availability" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 bg-amber-100 text-amber-900 px-3 py-1 rounded">
    Skip to availability
</a>

<!-- ====== Site Header / Nav ====== -->
<header class="border-b border-slate-200/70 dark:border-slate-800 bg-white/75 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:bg-slate-950/60">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            <!-- Logo (text only) -->
            <a href="/" class="font-semibold tracking-tight text-lg sm:text-xl">
                Beach House
            </a>

            <!-- Nav -->
            <nav aria-label="Primary" class="flex items-center gap-6 text-sm font-medium">
                <a href="/#availability" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Availability</a>
                <a href="{{ route('book') }}" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Booking</a>
                <a href="/login" class="text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Log in</a>
            </nav>
        </div>
    </div>
</header>

{{ $slot }}

<!-- ====== Footer ====== -->
<footer class="border-t border-slate-200/70 py-6 text-center text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400">
    made with ❤️ and AI — TheGalaDev for MalcaCorp — © {{ date('Y') }}
</footer>
</body>
</html>
