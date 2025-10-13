<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany; // Use for type hinting
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail; // <-- 1. Import this
// Note: UserForbid and UserTemporaryPermission models are explicitly defined below,
// but their 'use' statements are not strictly required here if using ::class notation.

class User extends Authenticatable implements MustVerifyEmail {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, TwoFactorAuthenticatable, HasRoles {
        // Alias the trait method so we can call it inside our override
        hasPermissionTo as protected traitHasPermissionTo;
    }

    public $timestamps = true;

    // --- Caching Properties for Performance ---

    /**
     * Cache for results of hasPermissionTo checks within the current request.
     * @var array<string, bool>
     */
    protected array $tempPermissionCheckCache = [];

    /**
     * Cache for results of isForbidden checks within the current request.
     * @var array<string, bool>
     */
    protected array $forbidCheckCache = [];

    // --- Permission Overrides and Helpers ---

    /**
     * Override hasPermissionTo to include temporary permissions.
     *
     * Optimization: Caches the result of the temporary permission check
     * to prevent repeated database queries in the same request.
     */
    public function hasPermissionTo($permission, $guardName = null): bool {
        // 1. First check regular Spatie permissions (Roles/Direct)
        if ($this->traitHasPermissionTo($permission, $guardName)) {
            return true;
        }

        // 2. Check temporary permissions cache
        if (array_key_exists($permission, $this->tempPermissionCheckCache)) {
            return $this->tempPermissionCheckCache[$permission];
        }

        // 3. Query temporary permissions
        $hasTemp = $this->temporaryPermissions()
            ->where('permission_name', $permission)
            ->where('expires_at', '>', now())
            ->exists();

        // Cache and return
        return $this->tempPermissionCheckCache[$permission] = $hasTemp;
    }

    /**
     * Convenience helper to check for a forbidden permission.
     *
     * Optimization: Caches the result to prevent repeated database lookups.
     */
    public function isForbidden(string $permission, ?string $scope = null): bool {
        $cacheKey = $permission . ':' . ($scope ?? 'global');

        // Check cache
        if (array_key_exists($cacheKey, $this->forbidCheckCache)) {
            return $this->forbidCheckCache[$cacheKey];
        }

        $query = $this->forbids()->where('permission_name', $permission);
        if ($scope !== null) {
            $query->where('scope', $scope);
        }

        $isForbidden = $query->exists();

        // Cache and return
        return $this->forbidCheckCache[$cacheKey] = $isForbidden;
    }

    // --- Relationships ---

    /**
     * Relationship to temporary permissions.
     * @return HasMany<UserTemporaryPermission>
     */
    public function temporaryPermissions(): HasMany {
        return $this->hasMany(UserTemporaryPermission::class);
    }

    /**
     * Relationship to forbidden permissions.
     * @return HasMany<UserForbid>
     */
    public function forbids(): HasMany {
        return $this->hasMany(UserForbid::class);
    }

    // --- Mutators/Accessors/Helpers ---

    /**
     * Add a forbid.
     *
     * Optimization: Corrected keys for firstOrCreate (assuming UserForbid uses 'permission_name').
     */
    public function forbid(string $permission, ?string $scope = null, ?int $by = null, ?string $notes = null): UserForbid {
        // Clear the forbid cache for this permission to reflect the change immediately
        $this->forbidCheckCache = [];

        return $this->forbids()->firstOrCreate(
            ['permission_name' => $permission, 'scope' => $scope],
            ['created_by' => $by, 'notes' => $notes]
        );
    }

    /**
     * Remove a forbid.
     *
     * Optimization: Added cache clearing.
     */
    public function unforbid(string $permission, ?string $scope = null): int {
        // Clear the forbid cache for this permission to reflect the change immediately
        $this->forbidCheckCache = [];

        $q = $this->forbids()->where('permission_name', $permission);
        if ($scope !== null) {
            $q->where('scope', $scope);
        }

        return $q->delete();
    }

    /**
     * Accessor for avatar URL.
     *
     * Optimization: Used string concatenation with ternary operator for conciseness.
     */
    public function getAvatarUrlAttribute(): string {
        $path = (empty($this->avatar) || $this->avatar === 'blank.png')
            ? 'assets/media/avatars/blank.png'
            : 'storage/' . $this->avatar;

        return asset($path);
    }

    // --- Defaults and Configuration ---

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'settings',
        'sex',
        'bday',
        'mobile',
        'avatar',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        // 'two_factor_recovery_codes', // You might want to hide these
        // 'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'bday' => 'datetime',
        'password_changed_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
    ];

    protected static function booted() {
        static::creating(function ($user) {
            // Use logical checks over empty() for robust defaults
            if (is_null($user->public_id)) {
                $user->public_id = (string) Str::uuid();
            }
            if (is_null($user->avatar)) {
                $user->avatar = 'blank.png';
            }
        });
    }

    public function getRouteKeyName(): string {
        return 'public_id';
    }
}
