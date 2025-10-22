<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// If your Enums live elsewhere, update these namespaces:
use App\Enums\ActivityLevel;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;
use App\Enums\ActivityTarget;
use App\Enums\ActivitySubject;
use App\Enums\ActivitySource;

return new class extends Migration {
    public function up(): void {
        Schema::create('system_activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Nullable because system/console or unauthenticated events may exist
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Core enums with locked defaults (as agreed)
            $table->enum('level', array_column(ActivityLevel::cases(), 'value'))
                ->default(ActivityLevel::INFO->value);

            $table->enum('category', array_column(ActivityCategory::cases(), 'value'));

            $table->enum('action', array_column(ActivityAction::cases(), 'value'));

            $table->enum('target', array_column(ActivityTarget::cases(), 'value'))
                ->default(ActivityTarget::SELF->value);

            $table->enum('subject', array_column(ActivitySubject::cases(), 'value'))
                ->default(ActivitySubject::USER->value);

            $table->enum('source', array_column(ActivitySource::cases(), 'value'))
                ->default(ActivitySource::Web->value);

            // Optional human-readable text
            $table->text('message')->nullable();

            // Flexible payload (diffs, context, flags, etc.)
            $table->json('meta')->nullable();

            // Request context
            $table->string('ip_address', 45)->nullable(); // IPv4/IPv6
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Helpful indexes for common queries
            $table->index(['category', 'action']);
            $table->index(['level']);
            $table->index(['source']);
            $table->index(['user_id', 'created_at']);
            $table->index(['subject', 'target']);
            $table->index(['created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('system_activity_logs');
    }
};
