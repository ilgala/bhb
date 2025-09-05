<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Support\Audit;
use Illuminate\Database\Seeder;

class BookingAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::get()->each(function (Booking $booking) {
            Audit::record($booking, 'create', ['status' => strtolower($booking->status->name)]);
            if ($booking->isAccepted()) {
                Audit::record($booking, 'approve', ['previous_status' => 'pending']);
            } elseif ($booking->isDeclined()) {
                Audit::record($booking, 'decline', ['previous_status' => 'pending', 'reason' => 'Seeder reason']);
            }
        });
    }
}
