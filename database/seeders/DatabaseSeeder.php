<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->active()->create([
            'name' => 'Admin active',
            'email' => 'admin@example.com',
        ]);

        User::factory()->admin()->deactivated()->create([
            'name' => 'Admin',
            'email' => 'admin-deactivated@example.com',
        ]);

        User::factory()->standard()->active()->create([
            'name' => 'Admin',
            'email' => 'standard@example.com',
        ]);

        User::factory()->standard()->deactivated()->create([
            'name' => 'Admin',
            'email' => 'standard-deactivated@example.com',
        ]);

        $this->call(BookingSeeder::class);
    }
}
