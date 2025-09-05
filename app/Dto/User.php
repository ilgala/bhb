<?php

namespace App\Dto;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class User implements Arrayable, Jsonable, JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly UserRole $role,
        public readonly UserStatus $status,
    ) {}

    public static function from(array $data): User
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            role: UserRole::from(strtoupper($data['role'])),
            status: UserStatus::from(strtoupper($data['status']))
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }

    public function toJson($options = 0): false|string
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
