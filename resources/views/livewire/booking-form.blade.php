<main>
    <div>
        {{-- Page intro / hero --}}
        <section class="py-10 sm:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Request your stay
                </h1>
                <p class="mt-3 text-slate-600 dark:text-slate-300">
                    Fill in the details below. We’ll check availability and email you a signed approval link.
                </p>
            </div>
        </section>

        {{-- Success alert --}}
        @if (session('success'))
            <div
                class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 my-4"
                x-data
                x-init="$el.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                aria-live="polite"
            >
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Validation summary (optional, helps screen readers) --}}
        @if ($errors->any())
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 my-4" aria-live="assertive">
                <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800 dark:border-rose-900/40 dark:bg-rose-900/20 dark:text-rose-200">
                    <p class="font-semibold">Please correct the errors below.</p>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <section id="booking-form" class="pb-16">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <form wire:submit.prevent="createBooking" class="space-y-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">

                    {{-- Your details --}}
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Your details</h2>
                        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">

                            {{-- Name --}}
                            <div class="sm:col-span-2">
                                <label for="guest_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full name *</label>
                                <input
                                    id="guest_name"
                                    type="text"
                                    wire:model.defer="form.guest_name"
                                    @error('form.guest_name') aria-invalid="true" @enderror
                                    class="mt-2 px-2 py-1.5 block w-full rounded-lg shadow-sm focus:ring-emerald-500
                  @error('form.guest_name') border-rose-500 focus:border-rose-500 focus:ring-rose-500
                  @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                                    placeholder="Jane Doe"
                                >
                                @error('form.guest_name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="guest_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email *</label>
                                <input
                                    id="guest_email"
                                    type="email"
                                    wire:model.defer="form.guest_email"
                                    @error('form.guest_email') aria-invalid="true" @enderror
                                    class="mt-2 px-2 py-1.5 block w-full rounded-lg shadow-sm focus:ring-emerald-500
                  @error('form.guest_email') border-rose-500 focus:border-rose-500 focus:ring-rose-500
                  @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                                    placeholder="jane@example.com"
                                >
                                @error('form.guest_email')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="guest_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone (optional)</label>
                                <input
                                    id="guest_phone"
                                    type="tel"
                                    wire:model.defer="form.guest_phone"
                                    @error('form.guest_phone') aria-invalid="true" @enderror
                                    class="mt-2 px-2 py-1.5 block w-full rounded-lg shadow-sm focus:ring-emerald-500
                  @error('form.guest_phone') border-rose-500 focus:border-rose-500 focus:ring-rose-500
                  @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                                    placeholder="xxx xxx xxxx" maxlength="40"
                                >
                                @error('form.guest_phone')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">

                    {{-- Dates & Guests --}}
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Dates & guests</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Check-in and out are flexible unless stated otherwise.</p>

                        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">

                            {{-- Start --}}
                            <div>
                                <label for="start_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Start *</label>
                                <input
                                    id="start_at"
                                    type="date"
                                    wire:model.defer="form.start_at"
                                    @error('form.start_at') aria-invalid="true" @enderror
                                    class="mt-2 px-2 py-1.5 block w-full rounded-lg shadow-sm focus:ring-emerald-500
                  @error('form.start_at') border-rose-500 focus:border-rose-500 focus:ring-rose-500
                  @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                                >
                                @error('form.start_at')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End --}}
                            <div>
                                <label for="end_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300">End *</label>
                                <input
                                    id="end_at"
                                    type="date"
                                    wire:model.defer="form.end_at"
                                    @error('form.end_at') aria-invalid="true" @enderror
                                    class="mt-2 px-2 py-1.5 block w-full rounded-lg shadow-sm focus:ring-emerald-500
                  @error('form.end_at') border-rose-500 focus:border-rose-500 focus:ring-rose-500
                  @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                                >
                                @error('form.end_at')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Guests --}}
                            <div class="sm:col-span-2 max-w-xs">
                                <label for="guests_count" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Guests *</label>
                                <input
                                    id="guests_count"
                                    type="number"
                                    min="1" max="12"
                                    wire:model.defer="form.guests_count"
                                    @error('form.guests_count') aria-invalid="true" @enderror
                                    class="mt-2 px-2 py-1.5 block w-full rounded-lg shadow-sm focus:ring-emerald-500
                  @error('form.guests_count') border-rose-500 focus:border-rose-500 focus:ring-rose-500
                  @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                                    placeholder="2"
                                >
                                <p class="mt-2 px-2 py-1.5 text-xs text-slate-500 dark:text-slate-400">Up to 12 guests.</p>
                                @error('form.guests_count')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">

                    {{-- Notes --}}
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Notes</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Allergies, kids, special arrival times… tell us anything helpful.</p>

                        <textarea
                            id="notes"
                            rows="5"
                            maxlength="2000"
                            wire:model.defer="form.notes"
                            @error('form.notes') aria-invalid="true" @enderror
                            class="mt-3 block w-full rounded-lg shadow-sm focus:ring-emerald-500
              @error('form.notes') border-rose-500 focus:border-rose-500 focus:ring-rose-500
              @else border-slate-300 dark:border-slate-700 dark:bg-slate-900 @enderror"
                            placeholder="Optional message for the host"
                        ></textarea>
                        @error('form.notes')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info box --}}
                    <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:ring-slate-800">
                        This is a <strong>request</strong>. We’ll email an approval link if your dates are available.
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between gap-4">
                        <a href="/#availability" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">
                            ← Back to availability
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-white font-semibold shadow hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 disabled:opacity-70"
                            wire:loading.attr="disabled"
                            wire:target="createBooking"
                        >
                            <svg
                                wire:loading
                                wire:target="createBooking"
                                class="mr-2 h-4 w-4 animate-spin"
                                viewBox="0 0 24 24" fill="none"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            Submit booking request
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>
