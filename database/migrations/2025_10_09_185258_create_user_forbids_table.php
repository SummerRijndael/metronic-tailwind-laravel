<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_forbids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('permission_name', 120);            // e.g. 'edit_settings', 'delete_user'
            $table->string('scope')->nullable();   // optional context (team, org, etc.)
            $table->text('notes')->nullable();     // optional admin notes
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'permission_name', 'scope']);
            $table->index('permission_name');
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_forbids');
    }
};
