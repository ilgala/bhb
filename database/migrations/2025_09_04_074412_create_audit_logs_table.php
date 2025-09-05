<?php

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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->ulidMorphs('auditable');        // auditable_type + auditable_id (use Booking as primary target)
            $table->foreignUlid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 50);           // approve|decline|cancel|create|update|invite|role_change...
            $table->json('metadata')->nullable();   // reason, diffs, previous_status, ip, ua...

            $table->timestamps();
            $table->index(['auditable_type', 'auditable_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
