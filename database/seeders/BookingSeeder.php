<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory()->count(10)->pending()->create();
        Booking::factory()->count(10)->accepted()->create();
        Booking::factory()->count(5)->declined()->create();
        Booking::factory()->count(5)->canceled()->create();
    }
}
