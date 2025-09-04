<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class Decline extends FormRequest
{
    public function rules(): array
    {
        return [
            'admin_comment' => ['required', 'string', 'max:2000'],
        ];
    }
}
