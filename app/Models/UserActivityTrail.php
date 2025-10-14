<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivityTrail extends Model {
    // Ensure all fields used in logUserActivity are fillable
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'meta',
    ];

    // Ensure 'meta' is handled correctly as JSON
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get the user that performed the activity.
     *
     * The relationship now correctly reflects the nullable user_id column.
     * When the user is deleted, user_id is set to NULL, and this relationship
     * will return null.
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
        // No explicit change is needed here, as BelongsTo automatically handles nullable foreign keys.
    }
}
