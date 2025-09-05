<?php

namespace App\Services;

use App\Dto\User as UserDto;
use App\Enums\UserStatus;
use App\Mail\NewUserWelcomeMail;
use App\Models\User;
use App\Services\Contracts\UserService as UserContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserService implements UserContract
{
    public function adminUsers(): Collection
    {
        return User::query()->onlyAdmin()->get();
    }

    public function store(UserDto $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $user = new User;
            $password = str()->random(16);

            $user->fill([
                ...$dto->toArray(),
                'password' => bcrypt($password),
            ]);

            return tap($user, function ($user) use ($password) {
                $user->save();

                Mail::to($user->email)->queue(
                    (new NewUserWelcomeMail($user, $password))
                        ->onQueue('users')
                        ->afterCommit()
                );
            });
        });
    }

    public function update(UserDto $dto, User $user): User
    {
        return tap($user->fill($dto->toArray()))->save();
    }

    public function toggleStatus(User $user): User
    {
        return tap($user->fill([
            'status' => $user->status === UserStatus::ACTIVE
                ? UserStatus::DEACTIVATED
                : UserStatus::ACTIVE,
        ]))->save();
    }
}
