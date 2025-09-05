<?php

namespace App\Livewire\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\Contracts\BookingService;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Calendar extends Component
{
    use WithPagination;

    public string $viewMode = 'daily';

    public string $status = 'pending';

    public ?string $selectedId = null;

    public ?string $dateStart = null;

    public ?string $dateEnd = null;

    public ?string $adminComment = null;

    public function render(): View
    {
        return view('livewire.admin.calendar', [
            'bookings' => $this->bookings,
            'selectedBooking' => $this->selectedBooking,
        ]);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function select(string $booking): void
    {
        $this->selectedId = $booking;
    }

    public function getSelectedBookingProperty(): ?Booking
    {
        $id = $this->selectedId ?? optional($this->bookings->first())->id;

        return $id
            ? Booking::with(['audits.user'])->find($id)
            : null;
    }

    public function getBookingsProperty(): LengthAwarePaginator
    {
        $query = Booking::query()->latest('start_at');

        if ($this->status !== 'all') {
            $query->where('status', BookingStatus::from($this->status));
        }

        if ($this->dateStart || $this->dateEnd) {
            $start = $this->dateStart
                ? Carbon::parse($this->dateStart)->startOfDay()
                : null;

            $end = $this->dateEnd
                ? Carbon::parse($this->dateEnd)->endOfDay()
                : null;

            $query->where(function (Builder $subQuery) use ($start, $end) {
                if ($start) {
                    $subQuery->where('start_at', '<', $end);
                }
                if ($end) {
                    $subQuery->where('end_at', '>', $start);
                }
            });
        }

        return $query->paginate(8);
    }

    public function approve(string $bookingId, BookingService $bookingService): void
    {
        $booking = Booking::findOrFail($bookingId);
        if ($booking->status === BookingStatus::ACCEPTED) {
            session()->flash('ok', 'This booking was already accepted.');
        } elseif ($booking->status === BookingStatus::DECLINED) {
            session()->flash('ok', 'This booking was already declined.');
        } elseif ($booking->status !== BookingStatus::PENDING) {
            session()->flash('ok', 'Booking is not pending anymore.');
        }

        DB::transaction(function () use ($booking, $bookingService) {
            $bookingService->accept($booking);
        });

        session()->flash('ok', 'Booking accepted. Guest will be notified.');
    }

    public function decline(string $bookingId, BookingService $bookingService): void
    {
        $booking = Booking::findOrFail($bookingId);
        if ($booking->status === BookingStatus::ACCEPTED) {
            session()->flash('ok', 'This booking was already accepted.');
        } elseif ($booking->status === BookingStatus::DECLINED) {
            session()->flash('ok', 'This booking was already declined.');
        } elseif ($booking->status !== BookingStatus::PENDING) {
            session()->flash('ok', 'Booking is not pending anymore.');
        }

        DB::transaction(function () use ($booking, $bookingService) {
            $bookingService->decline($booking, [
                'admin_comment' => $this->adminComment,
            ]);
        });

        $this->adminComment = null;
        session()->flash('ok', 'Booking declined. Guest will be notified.');
        $this->dispatch('$refresh');
    }

    public function cancel(string $bookingId, BookingService $bookingService): void
    {
        $booking = Booking::findOrFail($bookingId);
        if ($booking->status !== BookingStatus::ACCEPTED) {
            session()->flash('ok', 'Only accepted bookings can be canceled.');

            return;
        }

        $bookingService->cancel($booking);

        session()->flash('ok', 'Booking canceled. Guest will be notified.');
        $this->dispatch('$refresh');
    }
}
