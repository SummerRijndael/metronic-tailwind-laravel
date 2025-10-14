<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('user_activity_trails', function (Blueprint $table) {
            $table->id();

            // ----------------------------------------------------------------------------------
            // OPTIMIZATION & AUDIT FIX:
            // ----------------------------------------------------------------------------------
            // 1. ->nullable(): Allows logging of Anonymous (failed login) or System (cron job) actions.
            // 2. ->onDelete('set null'): Changes 'cascade' (which deletes the log) to 'set null'.
            //    This is crucial for auditing—it retains the log entry even if the associated user is deleted.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            // ----------------------------------------------------------------------------------

            $table->string('action'); // e.g., login, logout, update_profile, view_page
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6. Nullable for system actions.
            $table->string('user_agent')->nullable(); // browser, device info. Nullable for system actions.
            $table->text('description')->nullable(); // optional detailed description
            $table->json('meta')->nullable(); // optional extra data (e.g., previous values)

            $table->timestamps(); // created_at = when the action happened, updated_at = rarely used
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('user_activity_trails');
    }
};
