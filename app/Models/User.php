<?php

namespace App\Models;

use App\Helpers\ActiveUserHelper; // DEV NOTE: Use the newly created helper
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // OPTIMIZATION: Required for database session invalidation
use Illuminate\Support\Facades\Cache; // OPTIMIZATION: Required for consistency
use Illuminate\Support\Facades\Redis; // OPTIMIZATION: Required for redis session invalidation

class User extends Authenticatable implements MustVerifyEmail {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles {
        // Alias the trait method so we can call it inside our override
        hasPermissionTo as protected traitHasPermissionTo;
    }

    public $timestamps = true;

    // -------------------------------------------------------------------------
    // Caching Properties for Performance
    // -------------------------------------------------------------------------

    /**
     * Cache for results of hasPermissionTo checks within the current request.
     * key: 'permission_name' => bool (true if granted by role/temp)
     * DEV NOTE: Used to prevent multiple, identical, costly DB/Spatie checks within a single request.
     * @var array<string, bool>
     */
    protected array $permissionCheckCache = [];

    /**
     * Cache for results of isForbidden checks within the current request.
     * key: 'permission_name:scope' => bool (true if forbidden)
     * DEV NOTE: Used to prevent repeated database queries for forbid status.
     * @var array<string, bool>
     */
    protected array $forbidCheckCache = [];

    // -------------------------------------------------------------------------
    // Permission Overrides and Helpers
    // -------------------------------------------------------------------------

    /**
     * Override hasPermissionTo to include temporary permissions.
     *
     * OPTIMIZATION: Consolidates Spatie check and Temporary check.
     * The result is cached for the entire request lifecycle.
     * @param string $permission
     * @param string|null $guardName
     * @return bool
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
        // OPTIMIZATION: The `where('expires_at', '>', now())` is critical.
        $hasTemp = $this->temporaryPermissions()
            ->where('permission_name', $permission)
            ->where('expires_at', '>', now())
            ->exists();

        // Cache the result (TRUE or FALSE) and return
        return $this->permissionCheckCache[$permission] = $hasTemp;
    }

    /**
     * Accessor to check the user's online status based on the ActiveUserHelper.
     *
     * OPTIMIZATION: Uses the Attribute cast for cleaner syntax than a traditional accessor method.
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isOnline(): Attribute {
        return Attribute::make(
            // DEV NOTE: Uses the ActiveUserHelper::isUserActive() which utilizes Cache::has().
            get: fn() => ActiveUserHelper::isUserActive($this->id),
        );
    }

    /**
     * Convenience helper to check for a forbidden permission.
     *
     * OPTIMIZATION: Uses explicit caching to prevent repeated database lookups.
     * SECURITY: Forbidden checks should ideally be run BEFORE hasPermissionTo checks.
     * @param string $permission
     * @param string|null $scope
     * @return bool
     */
    public function isForbidden(string $permission, ?string $scope = null): bool {
        // Use a consistent cache key format
        $cacheKey = $permission . ':' . ($scope ?? 'global');

        // Check cache
        if (array_key_exists($cacheKey, $this->forbidCheckCache)) {
            return $this->forbidCheckCache[$cacheKey];
        }

        $query = $this->forbids()->where('permission_name', $permission);

        // OPTIMIZATION: Use conditional query construction
        $scope === null
            ? $query->whereNull('scope')
            : $query->where('scope', $scope);

        $isForbidden = $query->exists();

        // Cache and return
        return $this->forbidCheckCache[$cacheKey] = $isForbidden;
    }

    // --- Relationships ---

    /**
     * Relationship to temporary permissions (e.g., UserTemporaryPermission).
     * DEV NOTE: Requires UserTemporaryPermission model to exist.
     * @return HasMany
     */
    public function temporaryPermissions(): HasMany {
        return $this->hasMany(UserTemporaryPermission::class);
    }

    /**
     * Relationship to forbidden permissions (e.g., UserForbid).
     * DEV NOTE: Requires UserForbid model to exist.
     * @return HasMany
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

        // DEV NOTE: Uses firstOrCreate for idempotent operation.
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

        // OPTIMIZATION: Use conditional query construction for consistency.
        $scope !== null
            ? $q->where('scope', $scope)
            : $q->whereNull('scope');

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
            // DEV NOTE: Checks if $value is null or an empty string and returns it immediately.
            set: fn(?string $value) => ($value === null || trim($value) === '')
                ? $value
                : ucwords(strtolower(trim($value))),
        );
    }

    /**
     * Accessor for avatar URL.
     * Handles the default 'blank.png' state and returns an absolute URL.
     * @return string
     */
    public function getAvatarUrlAttribute(): string {
        // DEV NOTE: Use ternary operator for concise logic flow.
        $path = (empty($this->avatar) || $this->avatar === 'blank.png')
            ? 'assets/media/avatars/blank.png' // Assumes public assets folder
            : 'storage/' . $this->avatar;       // Assumes files served via /storage link

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
        if ($this->isSuspended()) {
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
        // DEV NOTE: Uses a simpler truth check (will be false if $this->suspended_until is null)
        return $this->status === 'suspended' && $this->suspended_until && now()->lt($this->suspended_until);
    }

    /**
     * Returns a human-readable string for the remaining suspension time (e.g., '1h 30m').
     * @return string|null
     */
    public function remainingSuspension(): ?string {
        if (!$this->isSuspended()) return null;

        // DEV NOTE: Uses diffForHumans with short parts for a concise output.
        // OPTIMIZATION: Use $this->suspended_until which is cast to a Carbon instance.
        return $this->suspended_until->diffForHumans(now(), [
            'parts' => 2,
            'short' => true,
            'syntax' => Carbon::DIFF_ABSOLUTE, // 🚀 CRITICAL FIX
        ]);
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
        'status',
        'public_id',
        'suspended_until', // OPTIMIZATION: Add to fillable for easy mass assignment of suspensions
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'bday' => 'datetime',
        'password_changed_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
        'suspended_until' => 'datetime', // CRITICAL for handling temporary suspensions
    ];

    /**
     * Booting model event to set unique default values on creation.
     */
    protected static function booted() {
        static::creating(function ($user) {
            // SECURITY: Use a unique ID (UUID) for public visibility.
            if (is_null($user->public_id)) {
                $user->public_id = (string) Str::uuid();
            }
            // Default avatar path.
            if (is_null($user->avatar)) {
                $user->avatar = 'blank.png';
            }
        });
    }

    /**
     * OPTIMIZATION: Invalidates all user sessions across supported drivers.
     *
     * DEV NOTE: Requires the use of DB and Redis facades.
     * This method is complex due to file/redis session storage implementation details.
     * SECURITY: Crucial for immediate logouts upon password change, suspension, etc.
     * @return void
     */
    public function invalidateAllSessions(): void {
        $driver = config('session.driver');

        // OPTIMIZATION: Use a switch statement for cleaner driver handling
        switch ($driver) {
            case 'database':
                // Database session driver (easiest to invalidate)
                DB::table(config('session.table', 'sessions'))
                    ->where('user_id', $this->id)
                    ->delete();
                break;

            case 'file':
                // File-based sessions (requires reading files, which is slow/risky)
                $path = storage_path('framework/sessions');
                // DEV NOTE: Iterating over files is slow, but necessary for file driver.
                // Using glob is slightly better than DirectoryIterator for simple cases.
                foreach (glob("$path/*") as $file) {
                    if (str_contains(file_get_contents($file), (string) $this->id)) {
                        @unlink($file);
                    }
                }
                break;

            case 'redis':
                // Redis sessions (requires scanning keys, which can be slow on large datasets)
                $redis = Redis::connection(config('session.connection'));
                // OPTIMIZATION: Use SCAN instead of KEYS for large datasets to avoid blocking the server.
                // However, for simplicity, using KEYS is acceptable in smaller projects.
                $prefix = config('session.prefix', 'laravel:');
                $keys = $redis->keys("{$prefix}sessions:*");

                // OPTIMIZATION: Use Redis::pipeline for faster bulk operations
                $pipeline = $redis->pipeline();
                foreach ($keys as $key) {
                    // This is still slow, as it requires getting/checking the payload
                    $session = $redis->get($key);
                    if ($session && str_contains($session, (string) $this->id)) {
                        $pipeline->del($key);
                    }
                }
                $pipeline->exec();
                break;

            case 'cache':
                // If using cache driver (e.g., Memcached, Redis via Cache facade)
                // This is generally impossible without manually querying keys, so it's often ignored.
                // If using Redis as cache, the keys logic above is the best approach.
                break;
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
