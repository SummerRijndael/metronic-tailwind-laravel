<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserForbid extends Model {
    protected $fillable = [
        'user_id',
        'permission_name',
        'scope',
        'notes',
        'created_by',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }
}
