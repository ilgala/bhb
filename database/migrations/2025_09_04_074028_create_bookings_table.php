<?php

use App\Enums\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->tinyInteger('guests_count');
            $table->enum('status', BookingStatus::values());
            $table->text('notes')->nullable();
            $table->text('admin_comment')->nullable();
            $table->string('approval_token')->unique();
            $table->dateTime('approval_expires_at');
            $table->string('google_event_id')->nullable();

            $table->timestamps();

            $table->index(['start_at']);
            $table->index(['end_at']);
            $table->index(['start_at', 'end_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
