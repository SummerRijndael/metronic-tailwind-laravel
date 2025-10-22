<?php

namespace App\Models;

use App\Enums\ActivityAction;
use App\Enums\ActivityCategory;
use App\Enums\ActivityLevel;
use App\Enums\ActivitySubject;
use App\Enums\ActivityTarget;
use App\Enums\ActivitySource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemActivityLog extends Model {
    protected $table = 'system_activity_logs';

    protected $fillable = [
        'user_id',
        'level',
        'category',
        'action',
        'target',
        'subject',
        'source',
        'message',
        'meta',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'meta'     => 'array',
        'level'    => ActivityLevel::class,
        'category' => ActivityCategory::class,
        'action'   => ActivityAction::class,
        'target'   => ActivityTarget::class,
        'subject'  => ActivitySubject::class,
        'source'   => ActivitySource::class,
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(config('auth.providers.users.model', \App\Models\User::class));
    }

    // Convenience scope examples
    public function scopeByUser($query, $userId) {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query) {
        return $query->orderByDesc('created_at');
    }
}
