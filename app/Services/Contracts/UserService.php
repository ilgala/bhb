<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface UserService
{
    public function adminUsers(): Collection;
}
