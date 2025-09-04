<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- LEFT COLUMN: Filters + List + Pagination --}}
        <section class="lg:col-span-7 space-y-4">
            {{-- Filters --}}
            <div class="rounded-xl border border-slate-200 bg-white/80 backdrop-blur p-4 dark:border-slate-800 dark:bg-slate-900/60">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    {{-- View mode segment: daily / weekly / monthly --}}
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white p-1 text-sm font-medium dark:border-slate-800 dark:bg-slate-900" role="tablist" aria-label="View mode">
                        <button type="button" wire:click="$set('viewMode','daily')"
                            @class([
                              'px-3 py-1.5 rounded-md',
                              'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $viewMode==='daily',
                              'text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' => $viewMode!=='daily',
                            ])>Daily</button>
                        <button type="button" wire:click="$set('viewMode','weekly')"
                            @class([
                              'px-3 py-1.5 rounded-md',
                              'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $viewMode==='weekly',
                              'text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' => $viewMode!=='weekly',
                            ])>Weekly</button>
                        <button type="button" wire:click="$set('viewMode','monthly')"
                            @class([
                              'px-3 py-1.5 rounded-md',
                              'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $viewMode==='monthly',
                              'text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' => $viewMode!=='monthly',
                            ])>Monthly</button>
                    </div>

                    {{-- Status filter --}}
                    <div class="flex items-center gap-2">
                        <label for="statusFilter" class="text-sm text-slate-600 dark:text-slate-300">Status</label>
                        <select id="statusFilter" wire:model.live="status"
                                class="rounded-lg border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-900">
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="declined">Declined</option>
                            <option value="canceled">Canceled</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- List (cards) --}}
            <div class="space-y-3">
                @forelse ($bookings as $booking)
                    <article
                        wire:key="bk-{{ $booking->id }}"
                        class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition dark:border-slate-800 dark:bg-slate-950"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                                    {{ $booking->guest_name }} <span class="text-slate-500">•</span>
                                    <span class="text-slate-600 dark:text-slate-300">{{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}</span>
                                </h3>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 truncate">
                                    {{ $booking->guest_email }} @if($booking->guest_phone) • {{ $booking->guest_phone }} @endif
                                </p>
                                <p class="mt-2 text-xs text-slate-600 line-clamp-2 dark:text-slate-300">
                                    {{ $booking->notes ?: '—' }}
                                </p>
                            </div>

                            {{-- Status chip --}}
                            <span @class([
                  'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset',
                  'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-300' => strtolower($booking->status->name)==='pending',
                  'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-300' => strtolower($booking->status->name)==='accepted',
                  'bg-slate-100 text-slate-700 ring-slate-600/20 dark:bg-slate-800/60 dark:text-slate-300' => in_array(strtolower($booking->status->name),['declined','canceled']),
                ])>
                {{ ucfirst(strtolower($booking->status->name)) }}
              </span>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                Guests: <span class="font-medium text-slate-700 dark:text-slate-200">{{ $booking->guests_count }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        wire:click="select('{{ $booking->id }}')"
                                        class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    Review
                                </button>
                                <a href="#"
                                   class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">
                                    Open
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300">
                        No bookings found for this filter.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="pt-2">
                {{ $bookings->onEachSide(1)->links() }}
            </div>
        </section>

        {{-- RIGHT COLUMN: Approve/Decline panel --}}
        <aside class="lg:col-span-5">
            <div class="sticky top-6 space-y-4">

                {{-- Selected booking meta --}}
                @if ($selectedBooking)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Booking review</h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Default: first item in list.</p>

                        <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                                <dt class="text-xs text-slate-500 dark:text-slate-400">Guest</dt>
                                <dd class="mt-0.5 text-sm font-medium">{{ $selectedBooking->guest_name }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                                <dt class="text-xs text-slate-500 dark:text-slate-400">Email</dt>
                                <dd class="mt-0.5 text-sm font-medium">{{ $selectedBooking->guest_email }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                                <dt class="text-xs text-slate-500 dark:text-slate-400">Dates</dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ $selectedBooking->start_at->format('Y-m-d H:i') }} → {{ $selectedBooking->end_at->format('Y-m-d H:i') }}
                                </dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                                <dt class="text-xs text-slate-500 dark:text-slate-400">Guests</dt>
                                <dd class="mt-0.5 text-sm font-medium">{{ $selectedBooking->guests_count }}</dd>
                            </div>
                            @if($selectedBooking->notes)
                                <div class="sm:col-span-2 rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                                    <dt class="text-xs text-slate-500 dark:text-slate-400">Notes</dt>
                                    <dd class="mt-0.5 text-sm font-medium whitespace-pre-line">{{ $selectedBooking->notes }}</dd>
                                </div>
                            @endif
                        </dl>

                        {{-- Approve / Decline form (same behavior as public approval page) --}}
                        <div class="mt-6 space-y-4">
                            @if (session('ok'))
                                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                                    {{ session('ok') }}
                                </div>
                            @endif
                            @error('admin_comment')
                            <div class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-rose-800 dark:border-rose-900/40 dark:bg-rose-900/20 dark:text-rose-200">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button type="button"
                                        wire:click="approve('{{ $selectedBooking->id }}')"
                                        wire:loading.attr="disabled" wire:target="approve"
                                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-white font-semibold shadow hover:bg-emerald-700 disabled:opacity-70">
                                    <svg wire:loading wire:target="approve" class="mr-2 h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                    </svg>
                                    Approve
                                </button>

                                <button type="button"
                                        x-data
                                        @click="$dispatch('toggle-decline')"
                                        class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-4 py-2.5 text-white font-semibold shadow hover:bg-rose-700">
                                    Decline
                                </button>
                            </div>

                            {{-- Decline reason --}}
                            <div x-data="{open:false}"
                                 x-on:toggle-decline.window="open = !open"
                                 x-show="open"
                                 x-transition
                                 class="rounded-lg border border-slate-200 p-3 dark:border-slate-800">
                                <label for="admin_comment" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reason</label>
                                <textarea id="admin_comment" rows="3" wire:model.defer="admin_comment"
                                          class="mt-2 block w-full rounded-lg border-slate-300 focus:border-rose-500 focus:ring-rose-500 dark:border-slate-700 dark:bg-slate-900"
                                          placeholder="Why are we declining this request?"></textarea>
                                <div class="mt-3 flex justify-end">
                                    <button type="button"
                                            wire:click="decline('{{ $selectedBooking->id }}')"
                                            wire:loading.attr="disabled" wire:target="decline"
                                            class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-4 py-2.5 text-white font-semibold shadow hover:bg-rose-700 disabled:opacity-70">
                                        <svg wire:loading wire:target="decline" class="mr-2 h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                        </svg>
                                        Confirm decline
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300">
                        Select a booking from the list to review it.
                    </div>
                @endif

            </div>
        </aside>
    </div>
</div>
