<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTemporaryPermission extends Model {
    protected $fillable = [
        'user_id',
        'permission_name',
        'expires_at',
        'granted_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function granter(): BelongsTo {
        return $this->belongsTo(User::class, 'granted_by');
    }
}
