<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_email_otps_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('email_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 60); // Store the HASHED code (use a size adequate for bcrypt)
            $table->timestamp('expires_at');
            $table->timestamps();

            // Add an index for faster lookups
            $table->index(['user_id', 'expires_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('email_otps');
    }
};
