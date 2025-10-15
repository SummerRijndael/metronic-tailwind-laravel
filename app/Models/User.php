<?php

namespace App\Models;

use App\Helpers\AccessHelper; // DEV NOTE: Assuming AccessHelper is used for external checks
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon; // For type hinting and clarity on dates

class User extends Authenticatable implements MustVerifyEmail {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles {
        // Alias the trait method so we can call it inside our override
        hasPermissionTo as protected traitHasPermissionTo;
    }

    // DEV NOTE: Explicitly set $table if it differs from 'users'.
    // public $table = 'users';

    public $timestamps = true;

    // --- Caching Properties for Performance ---

    /**
     * Cache for results of hasPermissionTo checks within the current request.
     * key: 'permission_name' => bool (true if granted by role/temp)
     * @var array<string, bool>
     */
    protected array $permissionCheckCache = [];

    /**
     * Cache for results of isForbidden checks within the current request.
     * key: 'permission_name:scope' => bool (true if forbidden)
     * @var array<string, bool>
     */
    protected array $forbidCheckCache = [];

    // --- Permission Overrides and Helpers ---

    /**
     * Override hasPermissionTo to include temporary permissions.
     *
     * OPTIMIZATION: Consolidates Spatie check and Temporary check.
     * The result is cached for the entire request lifecycle.
     */
    public function hasPermissionTo($permission, $guardName = null): bool {
        // 1. Check cache first. This prevents redundant DB queries for temp permissions.
        if (array_key_exists($permission, $this->permissionCheckCache)) {
            return $this->permissionCheckCache[$permission];
        }

        // 2. Check regular Spatie permissions (Roles/Direct)
        if ($this->traitHasPermissionTo($permission, $guardName)) {
            // Cache and return TRUE immediately
            return $this->permissionCheckCache[$permission] = true;
        }

        // 3. Query temporary permissions (only if Spatie check failed)
        $hasTemp = $this->temporaryPermissions()
            ->where('permission_name', $permission)
            // Use Carbon::now() instead of now() if Carbon import is missing,
            // but now() is fine if globally available (Laravel default).
            ->where('expires_at', '>', now())
            ->exists();

        // Cache the result (TRUE or FALSE) and return
        return $this->permissionCheckCache[$permission] = $hasTemp;
    }

    /**
     * Convenience helper to check for a forbidden permission.
     *
     * OPTIMIZATION: Uses explicit caching to prevent repeated database lookups.
     * SECURITY: Forbidden check should ideally be run BEFORE hasPermissionTo.
     */
    public function isForbidden(string $permission, ?string $scope = null): bool {
        // Use a consistent cache key format
        $cacheKey = $permission . ':' . ($scope ?? 'global');

        // Check cache
        if (array_key_exists($cacheKey, $this->forbidCheckCache)) {
            return $this->forbidCheckCache[$cacheKey];
        }

        $query = $this->forbids()->where('permission_name', $permission);

        // Use an explicit where condition for scope, allowing NULL to be queried
        if ($scope !== null) {
            $query->where('scope', $scope);
        } else {
            // DEV NOTE: Assuming 'scope' in the UserForbid table is nullable.
            // Adjust this if 'scope' defaults to a string like 'global' in the DB.
            $query->whereNull('scope');
        }

        $isForbidden = $query->exists();

        // Cache and return
        return $this->forbidCheckCache[$cacheKey] = $isForbidden;
    }

    // --- Relationships ---

    /**
     * Relationship to temporary permissions (e.g., UserTemporaryPermission).
     * @return HasMany<UserTemporaryPermission>
     */
    public function temporaryPermissions(): HasMany {
        return $this->hasMany(UserTemporaryPermission::class);
    }

    /**
     * Relationship to forbidden permissions (e.g., UserForbid).
     * @return HasMany<UserForbid>
     */
    public function forbids(): HasMany {
        return $this->hasMany(UserForbid::class);
    }

    // --- Mutators/Accessors/Helpers (Management) ---

    /**
     * Add a forbid permission to the user.
     * Clears the relevant cache property.
     *
     * @param string $permission The permission name to forbid.
     * @param string|null $scope Optional scope.
     * @param int|null $by User ID of the administrator who created the forbid.
     * @param string|null $notes Administrative notes.
     * @return UserForbid
     */
    public function forbid(string $permission, ?string $scope = null, ?int $by = null, ?string $notes = null): UserForbid {
        // SECURITY/PERFORMANCE: Clear the cache to ensure subsequent isForbidden() calls re-query.
        $this->forbidCheckCache = [];

        // DEV NOTE: The second argument is the array of attributes to apply if the record is created.
        return $this->forbids()->firstOrCreate(
            ['permission_name' => $permission, 'scope' => $scope],
            ['created_by' => $by, 'notes' => $notes]
        );
    }

    /**
     * Remove a forbid permission from the user.
     * Clears the relevant cache property.
     *
     * @param string $permission The permission name to unforbid.
     * @param string|null $scope Optional scope.
     * @return int Number of records deleted.
     */
    public function unforbid(string $permission, ?string $scope = null): int {
        // PERFORMANCE: Clear the cache immediately.
        $this->forbidCheckCache = [];

        $q = $this->forbids()->where('permission_name', $permission);
        if ($scope !== null) {
            $q->where('scope', $scope);
        }

        return $q->delete();
    }

    // --- Accessors/Mutators (Data Cleaning & Display) ---

    /**
     * Mutator for the 'name' (first name) field.
     * Enforces consistency by trimming whitespace and applying title case.
     */
    protected function name(): Attribute {
        return Attribute::make(
            set: fn(string $value) => ucwords(strtolower(trim($value))),
        );
    }

    /**
     * Mutator for the 'lastname' field.
     * Enforces consistency by trimming whitespace and applying title case.
     */
    protected function lastname(): Attribute {
        return Attribute::make(
            set: fn(string $value) => ucwords(strtolower(trim($value))),
        );
    }

    /**
     * Accessor for avatar URL.
     * Handles the default 'blank.png' state and returns an absolute URL.
     * @return string
     */
    public function getAvatarUrlAttribute(): string {
        // DEV NOTE: Check if the path is the default placeholder or empty.
        $path = (empty($this->avatar) || $this->avatar === 'blank.png')
            ? 'assets/media/avatars/blank.png' // Assumes 'assets' is public
            : 'storage/' . $this->avatar;       // Assumes files are in storage/app/public/ and served via /storage link

        return asset($path);
    }

    // --- User Status Logic (Availability Layer) ---

    /**
     * Checks if the user is available to perform actions (not blocked, disabled, or suspended).
     * @return bool
     */
    public function isActive(): bool {
        // 1. Permanently blocked or disabled users are never active
        if (in_array($this->status, ['blocked', 'disabled'])) {
            return false;
        }

        // 2. Suspended users are not active until suspension expires
        // DEV NOTE: Checks if status is 'suspended' AND if the expiration time is still in the future.
        if ($this->status === 'suspended' && $this->suspended_until && now()->lt($this->suspended_until)) {
            return false;
        }

        // If status is 'active' or 'suspended' with an expired timestamp
        return true;
    }

    /**
     * Checks if the user is currently under an active suspension.
     * @return bool
     */
    public function isSuspended(): bool {
        // DEV NOTE: Uses the same explicit check as isActive() for clarity and consistency.
        return $this->status === 'suspended' && $this->suspended_until && now()->lt($this->suspended_until);
    }

    /**
     * Returns a human-readable string for the remaining suspension time (e.g., '1h 30m').
     * @return string|null
     */
    public function remainingSuspension(): ?string {
        if (!$this->isSuspended()) return null;

        // DEV NOTE: Uses diffForHumans with short parts for a concise output.
        return $this->suspended_until->diffForHumans(now(), [
            'parts' => 2,
            'short' => true,
        ]);
    }

    // --- Defaults and Configuration ---

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'settings', // DEV NOTE: Used for user preferences/non-critical data
        'sex',
        'bday',
        'mobile',
        'avatar',
        'password_changed_at',
        'status', // Required for the isActive logic
        'public_id', // Add public_id to fillable if it's set in mass assignment
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes', // SECURITY: These should always be hidden
        'two_factor_secret',         // SECURITY: These should always be hidden
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'bday' => 'datetime',
        'password_changed_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed', // Laravel's automatic hashing
        'settings' => 'array',
        'suspended_until' => 'datetime', // CRITICAL for handling temporary suspensions
    ];

    /**
     * Booting model event to set unique default values on creation.
     */
    protected static function booted() {
        static::creating(function ($user) {
            // DEV NOTE: Use is_null for robust check, or check if the key is empty
            // (if the key is not in fillable, it might not be set by the Request).

            // SECURITY: Use a unique ID (UUID) for public visibility, preventing
            // sequence enumeration attacks via /user/1, /user/2, etc.
            if (is_null($user->public_id)) {
                $user->public_id = (string) Str::uuid();
            }
            // Default avatar path.
            if (is_null($user->avatar)) {
                $user->avatar = 'blank.png';
            }
        });
    }

    public function invalidateAllSessions(): void {
        if (config('session.driver') === 'database') {
            // Database session driver
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $this->id)
                ->delete();
        } elseif (config('session.driver') === 'file') {
            // File-based sessions
            $path = storage_path('framework/sessions');
            foreach (glob("$path/*") as $file) {
                if (strpos(file_get_contents($file), $this->id) !== false) {
                    @unlink($file);
                }
            }
        } elseif (config('session.driver') === 'redis') {
            // Redis sessions (advanced case)
            $redis = app('redis')->connection(config('session.connection'));
            $keys = $redis->keys(config('session.prefix', 'laravel:') . 'sessions:*');
            foreach ($keys as $key) {
                $session = $redis->get($key);
                if ($session && str_contains($session, (string) $this->id)) {
                    $redis->del($key);
                }
            }
        }
    }


    /**
     * Uses the public_id as the route key instead of the primary ID.
     * SECURITY: Prevents guessing user IDs in public URLs.
     * @return string
     */
    public function getRouteKeyName(): string {
        return 'public_id';
    }
}
