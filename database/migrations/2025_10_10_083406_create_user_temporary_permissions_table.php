<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_temporary_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('permission_name'); // corresponds to permission key (ex: 'post_edit_self')
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'permission_name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_temporary_permissions');
    }
};
