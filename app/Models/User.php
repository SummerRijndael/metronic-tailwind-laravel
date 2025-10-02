<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'bday' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array', // <-- important
            'settings' => 'array',
        ];
    }


      // Check if user has a specific permission
    public function hasPermission(string $permission): bool
    {
        return !empty($this->permissions[$permission]) && $this->permissions[$permission] === true;
    }

    /**
     * Boot method to automatically assign UUID to public_id
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->public_id)) {
                $user->public_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Override route key to use public_id in URLs
     */
    public function getRouteKeyName()
    {
        return 'public_id';
    }
}
