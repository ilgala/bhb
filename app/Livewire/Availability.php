<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Availability extends Component
{
    #[Layout('components.layouts.front_office')]
    public function render()
    {
        return view('livewire.availability');
    }
}
