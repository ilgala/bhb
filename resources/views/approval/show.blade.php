<x-layouts.front_office :title="$title ?? null">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">

        {{-- Flash success --}}
        @if (session('ok'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                {{ session('ok') }}
            </div>
        @endif

        {{-- Global errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800 dark:border-rose-900/40 dark:bg-rose-900/20 dark:text-rose-200">
                <p class="font-semibold">Please review the information below.</p>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Booking request</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Link valid until <strong>{{ $signedUntil }}</strong>
            </p>

            <dl class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                    <dt class="text-sm text-slate-500 dark:text-slate-400">Guest</dt>
                    <dd class="mt-1 font-medium">{{ $booking->guest_name }}</dd>
                </div>
                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                    <dt class="text-sm text-slate-500 dark:text-slate-400">Email</dt>
                    <dd class="mt-1 font-medium">{{ $booking->guest_email }}</dd>
                </div>
                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                    <dt class="text-sm text-slate-500 dark:text-slate-400">Phone</dt>
                    <dd class="mt-1 font-medium">{{ $booking->guest_phone ?? '—' }}</dd>
                </div>
                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                    <dt class="text-sm text-slate-500 dark:text-slate-400">Guests</dt>
                    <dd class="mt-1 font-medium">{{ $booking->guests_count }}</dd>
                </div>
                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800 sm:col-span-2">
                    <dt class="text-sm text-slate-500 dark:text-slate-400">Dates</dt>
                    <dd class="mt-1 font-medium">
                        {{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}
                    </dd>
                </div>
                @if ($booking->notes)
                    <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200 dark:bg-slate-900 dark:ring-slate-800 sm:col-span-2">
                        <dt class="text-sm text-slate-500 dark:text-slate-400">Notes</dt>
                        <dd class="mt-1 font-medium whitespace-pre-line">{{ $booking->notes }}</dd>
                    </div>
                @endif
            </dl>

            <div class="mt-8 grid grid-cols-1 gap-6">
                {{-- Approve form --}}
                <form method="POST" action="{{ $approveUrl }}">
                    @csrf
                    {{-- Carry token/signature from GET to POST --}}
                    <input type="hidden" name="token" value="{{ request('token') }}">
                    <input type="hidden" name="expires" value="{{ request('expires') }}">
                    <input type="hidden" name="signature" value="{{ request('signature') }}">

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-3 text-white font-semibold shadow hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        Approve
                    </button>
                </form>

                <h4 class="font-medium text-lg">Not a valid booking request?</h4>

                {{-- Decline form --}}
                <form method="POST" action="{{ $declineUrl }}" class="space-y-3">
                    @csrf
                    {{-- Carry token/signature from GET to POST --}}
                    <input type="hidden" name="token" value="{{ request('token') }}">
                    <input type="hidden" name="expires" value="{{ request('expires') }}">
                    <input type="hidden" name="signature" value="{{ request('signature') }}">

                    <div>
                        <label for="admin_comment" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reason (required)</label>
                        <textarea id="admin_comment" name="admin_comment" rows="3"
                                  class="mt-2 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 dark:border-slate-700 dark:bg-slate-900"
                                  placeholder="Why are we declining this request?" required>{{ old('admin_comment') }}</textarea>
                        @error('admin_comment')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-lg bg-rose-600 px-5 py-3 text-white font-semibold shadow hover:bg-rose-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500">
                        Decline
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.front_office>
