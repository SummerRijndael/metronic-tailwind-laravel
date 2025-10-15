<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'blocked', 'suspended', 'disabled'])
                ->default('active')
                ->after('remember_token');

            $table->timestamp('suspended_until')->nullable()->after('status');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'suspended_until']);
        });
    }
};
