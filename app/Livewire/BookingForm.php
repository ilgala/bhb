<?php

namespace App\Livewire;

use App\Dto\Booking as BookingDto;
use App\Livewire\Forms\BookingForm as Booking;
use App\Services\Contracts\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class BookingForm extends Component
{
    public Booking $form;

    #[Layout('components.layouts.front_office')]
    public function render()
    {
        return view('livewire.booking-form');
    }

    public function createBooking(BookingService $bookingService): void
    {
        $validated = $this->validate();

        $dto = BookingDto::from($validated);
        if ($bookingService->bookingExists($dto->startAt, $dto->endAt)) {
            $this->form->addError('start_at', 'Sorry, there\'s no availability for the given dates');

            return;
        }

        $bookingService->store($dto);

        $this->reset();
        session()->flash('success', 'Your request has been sent. Check your inbox for the approval link.');
    }
}
