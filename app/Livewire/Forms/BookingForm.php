<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class BookingForm extends Form
{
    public string $guest_name = '';

    public string $guest_email = '';

    public ?string $guest_phone = null;

    public ?string $start_at = null;

    public ?string $end_at = null;

    public int $guests_count = 2;

    public ?string $notes = null;

    protected function rules(): array
    {
        return [
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:40'],
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:'.config('bhb.max_guests', 12)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
