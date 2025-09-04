<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\GoogleCalendarService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminCalendar extends Component
{
    use WithPagination;

    public string $viewMode = 'daily';     // daily|weekly|monthly (UI only for now)

    public string $status = 'pending';   // pending|accepted|declined|canceled|all


    public Booking|null $selectedBooking = null;

    public ?string $admin_comment = null;

    public function render(): View
    {
        return view('livewire.admin-calendar', [
            'bookings' => $this->bookings,
            'selectedBooking' => $this->selectedBooking,
        ]);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function select(Booking $booking): void
    {
        $this->selectedBooking = $booking;
    }

    public function getSelectedBookingProperty(): ?Booking
    {
        return $this->selectedId
            ? Booking::find($this->selectedId)
            : ($this->bookings->first() ?: null);
    }

    public function getBookingsProperty(): LengthAwarePaginator
    {
        $q = Booking::query()->latest('start_at');

        if ($this->status !== 'all') {
            $q->where('status', BookingStatus::from($this->status));
        }

        return $q->paginate(8);
    }

    public function approve(Booking $booking, GoogleCalendarService $google): void
    {
        if ($booking->status === BookingStatus::ACCEPTED) {
            session()->flash('ok', 'This booking was already accepted.');
        } elseif ($booking->status === BookingStatus::DECLINED) {
            session()->flash('ok', 'This booking was already declined.');
        } elseif ($booking->status !== BookingStatus::PENDING) {
            session()->flash('ok', 'Booking is not pending anymore.');
        }

        DB::transaction(function () use ($booking) {
            $this->bookingService->accept($booking);
        });

        session()->flash('ok', 'Booking accepted. Guest will be notified.');
    }

    public function decline(Booking $booking): void
    {
        if ($booking->status === BookingStatus::ACCEPTED) {
            session()->flash('ok', 'This booking was already accepted.');
        } elseif ($booking->status === BookingStatus::DECLINED) {
            session()->flash('ok', 'This booking was already declined.');
        } elseif ($booking->status !== BookingStatus::PENDING) {
            session()->flash('ok', 'Booking is not pending anymore.');
        }

        DB::transaction(function () use ($booking) {
            $this->bookingService->decline($booking, [
                'admin_comment' => $this->admin_comment,
            ]);
        });

        $this->admin_comment = null;
        session()->flash('ok', 'Booking declined. Guest will be notified.');
        $this->dispatch('$refresh');
    }
}
