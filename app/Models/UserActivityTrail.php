<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityTrail extends Model
{
    use HasFactory;

    protected $table = 'user_activity_trails';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'meta',
    ];

    // Optional: cast timestamps
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'meta' => 'array',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
