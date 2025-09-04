<main>
    <!-- ====== Section 1: Hero (full page on md+, compact on mobile) ====== -->
    <section class="relative">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- On mobile: not full height; on md+: fill viewport (minus header) -->
            <div class="py-12 md:min-h-[calc(100vh-4rem)] md:flex md:flex-col md:items-start md:justify-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Welcome to your <span class="text-emerald-600 dark:text-emerald-400">Beach House</span>
                </h1>
                <p class="mt-6 max-w-2xl text-base sm:text-lg text-slate-600 dark:text-slate-300">
                    A quiet corner by the sea where mornings start with sunlight on the terrace and evenings fade with the sound of waves.
                    Explore our availability below and plan your stay with ease.
                </p>

                <div class="mt-8 flex gap-3">
                    <a href="#availability"
                       class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-white font-medium shadow hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        Check Availability
                    </a>
                    <a href="{{ route('book') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-slate-700 font-medium hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Subtle decorative gradient -->
        <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-24 h-48 bg-gradient-to-b from-emerald-100/40 to-transparent dark:from-emerald-400/10"></div>
    </section>

    <!-- ====== Section 2: Availability ====== -->
    <section id="availability" class="py-14 sm:py-16 lg:py-20 bg-slate-50 dark:bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 sm:mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Upcoming availability
                </h2>
                <p class="mt-2 text-slate-600 dark:text-slate-300">
                    These slots are currently open. First come, first served. For special requests, add a note during booking.
                </p>
            </div>

            <!-- Availability list/grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- TEMPLATE: repeat this card per available slot -->
                <!-- In Blade/Livewire you’ll loop and replace the placeholders -->
                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                Jun 12 → Jun 16, 2025
                            </h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                4 nights • up to 6 guests
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-300">
                Available
              </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-600 line-clamp-3 dark:text-slate-300">
                        Sun terrace • Sea breeze • Walkable to the beach.
                    </p>
                </article>

                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                Jun 24 → Jun 28, 2025
                            </h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                4 nights • up to 6 guests
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-300">
                Available
              </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-600 line-clamp-3 dark:text-slate-300">
                        Shaded patio • Outdoor dining • Nearby marina.
                    </p>
                </article>

                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                Jul 3 → Jul 7, 2025
                            </h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                4 nights • up to 6 guests
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-300">
                Available
              </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-600 line-clamp-3 dark:text-slate-300">
                        Morning light • Close to cafes • Quiet street.
                    </p>
                </article>

                <!-- /TEMPLATE -->
            </div>

            <!-- Micro legend (optional) -->
            <div class="mt-6 text-xs text-slate-500 dark:text-slate-400">
          <span class="inline-flex items-center gap-2">
            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500/80"></span>
            Open slot
          </span>
                <span class="mx-3">•</span>
                <span>Dates are indicative; final confirmation is sent by email after approval.</span>
            </div>
        </div>
    </section>

    <!-- ====== Section 3: CTA to booking ====== -->
    <section class="py-14 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white p-8 sm:p-10 lg:p-14 shadow-lg">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">
                    Found your dates? Let’s make it official.
                </h2>
                <p class="mt-2 max-w-2xl text-white/90">
                    Submit your request in a minute. We’ll review and send you a signed approval link with all the details.
                </p>
                <div class="mt-6">
                    <a href="{{ route('book') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 font-semibold text-emerald-700 shadow hover:shadow-md hover:bg-emerald-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70">
                        Go to Booking
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
