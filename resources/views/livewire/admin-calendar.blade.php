<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT (1/3): Filters + List + Pagination --}}
        <section class="lg:col-span-1 space-y-4">

            {{-- Filters --}}
            <div class="rounded-xl border">
                <div class="p-4 space-y-6">
                    {{-- View mode --}}
                    <div>
                        <flux:heading size="sm">View</flux:heading>
                        <div class="mt-2 inline-flex gap-2" role="tablist" aria-label="View mode">
                            <flux:button :variant="$viewMode==='daily' ? 'primary' : 'subtle'" wire:click="$set('viewMode','daily')">Daily</flux:button>
                            <flux:button :variant="$viewMode==='weekly' ? 'primary' : 'subtle'" wire:click="$set('viewMode','weekly')">Weekly</flux:button>
                            <flux:button :variant="$viewMode==='monthly' ? 'primary' : 'subtle'" wire:click="$set('viewMode','monthly')">Monthly</flux:button>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <flux:heading size="sm" class="mb-2">Status</flux:heading>
                        <flux:select wire:model.live="status">
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="declined">Declined</option>
                            <option value="canceled">Canceled</option>
                            <option value="all">All</option>
                        </flux:select>
                    </div>

                    {{-- Date range --}}
                    <div>
                        <flux:heading size="sm" class="mb-2">Date range</flux:heading>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <flux:field>
                                <flux:label for="date_start">From</flux:label>
                                <flux:input id="date_start" type="date" wire:model.live="dateStart" />
                                <flux:error name="dateStart" />
                            </flux:field>
                            <flux:field>
                                <flux:label for="date_end">To</flux:label>
                                <flux:input id="date_end" type="date" wire:model.live="dateEnd" />
                                <flux:error name="dateEnd" />
                            </flux:field>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <flux:button variant="subtle" size="sm" wire:click="clearDateRange">Clear</flux:button>
                            <flux:text size="xs">Showing bookings overlapping the selected window.</flux:text>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bookings list --}}
            <div class="space-y-3">
                @forelse ($bookings as $booking)
                    <div wire:key="bk-{{ $booking->id }}" class="rounded-xl border">
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <flux:heading size="sm" class="truncate">
                                        {{ $booking->guest_name }}
                                    </flux:heading>
                                    <flux:text class="mt-1 truncate">
                                        {{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}
                                    </flux:text>
                                    <flux:text class="mt-1 text-xs truncate">
                                        {{ $booking->guest_email }} @if($booking->guest_phone) • {{ $booking->guest_phone }} @endif
                                    </flux:text>
                                </div>

                                {{-- Status badge (default Flux badge) --}}
                                <flux:badge>
                                    {{ ucfirst($booking->status->name) }}
                                </flux:badge>
                            </div>

                            @if($booking->notes)
                                <flux:text class="mt-3 line-clamp-2">
                                    {{ $booking->notes }}
                                </flux:text>
                            @endif

                            <div class="mt-3 flex items-center justify-between">
                                <flux:text size="xs">
                                    Guests: <strong>{{ $booking->guests_count }}</strong>
                                </flux:text>
                                <div class="flex items-center gap-2">
                                    <flux:button variant="subtle" size="sm" wire:click="select('{{ $booking->id }}')">
                                        Review
                                    </flux:button>
                                    {{--
                                    <flux:button href="{{ route('admin.bookings.show', $booking) }}" size="sm">
                                        Open
                                    </flux:button>
                                    --}}
                                    <flux:button href="#" size="sm">
                                        Open
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border p-6">
                        <flux:text>No bookings found for this filter.</flux:text>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div>
                {{ $bookings->onEachSide(1)->links() }}
            </div>
        </section>

        {{-- RIGHT (2/3): Approve / Decline panel --}}
        <aside class="lg:col-span-2">
            <div class="space-y-4">

                @if (session('ok'))
                    <div class="rounded-xl border p-4">
                        <flux:text>{{ session('ok') }}</flux:text>
                    </div>
                @endif

                @if ($selectedBooking)
                    <div class="rounded-2xl border">
                        <div class="p-6">
                            <flux:heading size="md">Booking review</flux:heading>
                            <flux:text size="sm" class="mt-1">Default selection is the first item in the list.</flux:text>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="rounded-lg border p-3">
                                    <flux:text size="xs">Guest</flux:text>
                                    <flux:heading size="sm" class="mt-1">{{ $selectedBooking->guest_name }}</flux:heading>
                                </div>
                                <div class="rounded-lg border p-3">
                                    <flux:text size="xs">Email</flux:text>
                                    <flux:heading size="sm" class="mt-1">{{ $selectedBooking->guest_email }}</flux:heading>
                                </div>
                                <div class="rounded-lg border p-3">
                                    <flux:text size="xs">Dates</flux:text>
                                    <flux:heading size="sm" class="mt-1">
                                        {{ $selectedBooking->start_at->format('Y-m-d H:i') }} → {{ $selectedBooking->end_at->format('Y-m-d H:i') }}
                                    </flux:heading>
                                </div>
                                <div class="rounded-lg border p-3">
                                    <flux:text size="xs">Guests</flux:text>
                                    <flux:heading size="sm" class="mt-1">{{ $selectedBooking->guests_count }}</flux:heading>
                                </div>

                                @if($selectedBooking->notes)
                                    <div class="sm:col-span-2 rounded-lg border p-3">
                                        <flux:text size="xs">Notes</flux:text>
                                        <flux:text class="mt-1 whitespace-pre-line">{{ $selectedBooking->notes }}</flux:text>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions (state-aware) --}}
                            <div class="mt-6 space-y-4">

                                {{-- Flash --}}
                                @if (session('ok'))
                                    <div class="rounded-xl border p-4">
                                        <flux:text>{{ session('ok') }}</flux:text>
                                    </div>
                                @endif

                                {{-- Pending -> Approve / Decline --}}
                                @if (strtolower($selectedBooking->status->name) === 'pending')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <flux:button
                                            wire:click="approve('{{ $selectedBooking->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="approve">
        <span wire:loading wire:target="approve" class="mr-2">
          <flux:icon.arrow-path class="animate-spin" />
        </span>
                                            Approve
                                        </flux:button>

                                        <flux:button
                                            variant="danger"
                                            x-data
                                            @click="$dispatch('toggle-decline')">
                                            Decline
                                        </flux:button>
                                    </div>

                                    {{-- Decline reason (only for pending) --}}
                                    <div x-data="{open:false}"
                                         x-on:toggle-decline.window="open = !open"
                                         x-show="open"
                                         x-transition
                                         class="mt-4 rounded-xl border p-4">
                                        <flux:field>
                                            <flux:label for="admin_comment">Reason</flux:label>
                                            <flux:textarea id="admin_comment" rows="3" wire:model.defer="admin_comment" />
                                            <flux:error name="admin_comment" />
                                        </flux:field>

                                        <div class="mt-3 flex justify-end">
                                            <flux:button
                                                variant="danger"
                                                wire:click="decline('{{ $selectedBooking->id }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="decline">
          <span wire:loading wire:target="decline" class="mr-2">
            <flux:icon.arrow-path class="animate-spin" />
          </span>
                                                Confirm decline
                                            </flux:button>
                                        </div>
                                    </div>

                                    {{-- Accepted -> Cancel --}}
                                @elseif (strtolower($selectedBooking->status->name) === 'accepted')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="sm:col-span-2">
                                            <flux:button
                                                variant="danger"
                                                wire:click="cancel('{{ $selectedBooking->id }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="cancel">
          <span wire:loading wire:target="cancel" class="mr-2">
            <flux:icon.arrow-path class="animate-spin" />
          </span>
                                                Cancel booking
                                            </flux:button>
                                        </div>
                                    </div>

                                    {{-- Declined / Canceled -> No CTAs --}}
                                @else
                                    <div class="rounded-xl border p-4">
                                        <flux:text>No actions available for this booking.</flux:text>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border p-6">
                        <flux:text>Select a booking from the list to review it.</flux:text>
                    </div>
                @endif

            </div>
        </aside>
    </div>
</div>
