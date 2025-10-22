<?php

// app/Models/Session.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Session extends Model {
    // 1. Table configuration
    protected $table = 'sessions';
    public $incrementing = false; // The 'id' is a string, not an auto-incrementing integer
    protected $keyType = 'string';
    public $timestamps = false; // Laravel does not manage created_at/updated_at here

    // 2. Accessors for utility
    protected $casts = [
        'last_activity' => 'int', // Treat as a Unix timestamp
    ];

    // 3. Relationships
    public function user(): BelongsTo {
        // Assumes your User model is at App\Models\User
        return $this->belongsTo(User::class);
    }

    // 4. Scopes (Custom Query Helpers)

    /**
     * Scope a query to only include sessions that are currently active.
     */
    public function scopeActive(Builder $query): void {
        // Get the session lifetime in minutes from config (default 120)
        $lifetimeMinutes = config('session.lifetime', 120);

        // Calculate the Unix timestamp boundary
        $expirationTime = Carbon::now()->subMinutes($lifetimeMinutes)->timestamp;

        $query->where('last_activity', '>=', $expirationTime);
    }
}
