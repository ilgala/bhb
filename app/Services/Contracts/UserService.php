<?php

namespace App\Services\Contracts;

use App\Dto\User as UserDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserService
{
    public function adminUsers(): Collection;

    public function store(UserDto $dto): User;

    public function update(UserDto $dto, User $user): User;

    public function toggleStatus(User $user): User;
}
