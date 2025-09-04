<?php

namespace App\Dto;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class Booking implements Arrayable, Jsonable, JsonSerializable
{
    public function __construct(
        public string $guestName,
        public string $guestEmail,
        public string $guestPhone,
        public int $guestCount,
        public DateTimeInterface $startAt,
        public DateTimeInterface $endAt,
        public ?string $notes = null
    ) {}

    public static function from(array $data): Booking
    {
        return new self(
            guestName: $data['guest_name'],
            guestEmail: $data['guest_email'],
            guestPhone: $data['guest_phone'],
            guestCount: $data['guests_count'] ?? 1,
            startAt: Carbon::make($data['start_at']),
            endAt: Carbon::make($data['end_at']),
            notes: $data['notes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone' => $this->guestPhone,
            'guests_count' => $this->guestCount,
            'start_at' => $this->startAt->format('Y-m-d'),
            'end_at' => $this->endAt->format('Y-m-d'),
            'notes' => $this->notes,
        ];
    }

    public function toJson($options = 0): false|string
    {
        return json_encode($this->toArray());
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
