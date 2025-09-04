<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        // random date window in the next 60 days
        $start = Carbon::now()->addDays(fake()->numberBetween(1, 60))->setTime(15, 0);
        $end = (clone $start)->addDays(fake()->numberBetween(2, 7))->setTime(10, 0);

        return [
            'guest_name' => fake()->name(),
            'guest_email' => fake()->unique()->safeEmail(),
            'guest_phone' => fake()->optional()->e164PhoneNumber(),
            'start_at' => $start,
            'end_at' => $end,
            'guests_count' => fake()->numberBetween(1, 12),
            'notes' => fake()->boolean(30) ? fake()->sentence(12) : null,
            'status' => fake()->randomElement(BookingStatus::cases()),
            'admin_comment' => null,
            'approval_token' => Str::random(40),
            'approval_expires_at' => Carbon::now()->addHours(24),
            'google_event_id' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => BookingStatus::PENDING]);
    }

    public function accepted(): static
    {
        return $this->state(fn () => ['status' => BookingStatus::ACCEPTED]);
    }

    public function declined(): static
    {
        return $this->state(fn () => ['status' => BookingStatus::DECLINED]);
    }

    public function canceled(): static
    {
        return $this->state(fn () => ['status' => BookingStatus::CANCELED]);
    }
}
