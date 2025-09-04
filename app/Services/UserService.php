<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserService as UserContract;
use Illuminate\Database\Eloquent\Collection;

class UserService implements UserContract
{
    public function adminUsers(): Collection
    {
        return User::query()->onlyAdmin()->get();
    }
}
