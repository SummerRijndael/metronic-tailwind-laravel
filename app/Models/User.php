<?php

namespace App\Models;

use App\Helpers\ActiveUserHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB; // Required for database session invalidation
use Illuminate\Support\Facades\Redis; // Required for redis session invalidation
use Illuminate\Support\Facades\File; // Required for file session invalidation

class User extends Authenticatable implements MustVerifyEmail {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles {
        // Alias the trait method so we can call it inside our override
        hasPermissionTo as protected traitHasPermissionTo;
    }

    // =========================================================================
    // ⬇️ CONFIGURATION & DEFAULTS ⬇️
    // =========================================================================

    public $timestamps = true;

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
        'suspended_until', // DEV NOTE: Add to fillable for easy mass assignment of suspensions
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
        'suspended_until' => 'datetime', // CRITICAL: For handling temporary suspensions
    ];

    /**
     * Cache for results of permission checks within the current request.
     * DEV NOTE: Used to prevent multiple, identical, costly DB/Spatie checks within a single request.
     * @var array<string, bool>
     */
    protected array $permissionCheckCache = [];

    /**
     * Cache for results of forbid checks within the current request.
     * DEV NOTE: Used to prevent repeated database queries for forbid status.
     * @var array<string, bool>
     */
    protected array $forbidCheckCache = [];

    // =========================================================================
    // ⬇️ BOOTING & ROUTE KEY ⬇️
    // =========================================================================

    /**
     * Booting model event to set unique default values on creation.
     */
    protected static function booted(): void {
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
     * Uses the public_id as the route key instead of the primary ID.
     * SECURITY: Prevents guessing user IDs in public URLs (e.g., /users/1).
     * @return string
     */
    public function getRouteKeyName(): string {
        return 'public_id';
    }

    // =========================================================================
    // ⬇️ RELATIONSHIPS ⬇️
    // =========================================================================

    /**
     * Relationship to the Laravel sessions table.
     * DEV NOTE: Requires the Session model to exist and the sessions table to be set up.
     */
    public function sessions(): HasMany {
        // Foreign key must be 'user_id' in the sessions table.
        return $this->hasMany(Session::class, 'user_id', 'id');
    }

    /**
     * Relationship to temporary permissions (e.g., UserTemporaryPermission).
     * DEV NOTE: Requires UserTemporaryPermission model to exist.
     */
    public function temporaryPermissions(): HasMany {
        return $this->hasMany(UserTemporaryPermission::class);
    }

    /**
     * Relationship to forbidden permissions (e.g., UserForbid).
     * DEV NOTE: Requires UserForbid model to exist.
     */
    public function forbids(): HasMany {
        return $this->hasMany(UserForbid::class);
    }

    // =========================================================================
    // ⬇️ PERMISSION OVERRIDES & HELPERS ⬇️
    // =========================================================================

    /**
     * Override hasPermissionTo to include temporary permissions.
     * OPTIMIZATION: Consolidates Spatie check and Temporary check. The result is cached for the request lifecycle.
     */
    public function hasPermissionTo($permission, $guardName = null): bool {
        // 1. Check request cache first.
        if (array_key_exists($permission, $this->permissionCheckCache)) {
            return $this->permissionCheckCache[$permission];
        }

        // 2. Check regular Spatie permissions (Roles/Direct)
        if ($this->traitHasPermissionTo($permission, $guardName)) {
            // Cache and return TRUE immediately
            return $this->permissionCheckCache[$permission] = true;
        }

        // 3. Query temporary permissions (only if Spatie check failed)
        // CRITICAL: Checks for non-expired permissions.
        $hasTemp = $this->temporaryPermissions()
            ->where('permission_name', $permission)
            ->where('expires_at', '>', now())
            ->exists();

        // Cache the final result (TRUE or FALSE) and return
        return $this->permissionCheckCache[$permission] = $hasTemp;
    }

    /**
     * Convenience helper to check for a forbidden permission.
     * OPTIMIZATION: Uses explicit caching to prevent repeated database lookups within a request.
     * SECURITY: Forbidden checks should ideally be run BEFORE hasPermissionTo checks in your Policy/Gate.
     */
    public function isForbidden(string $permission, ?string $scope = null): bool {
        $cacheKey = $permission . ':' . ($scope ?? 'global');

        // Check request cache
        if (array_key_exists($cacheKey, $this->forbidCheckCache)) {
            return $this->forbidCheckCache[$cacheKey];
        }

        $query = $this->forbids()->where('permission_name', $permission);

        // OPTIMIZATION: Conditional query construction for scope
        $scope === null
            ? $query->whereNull('scope')
            : $query->where('scope', $scope);

        $isForbidden = $query->exists();

        // Cache and return
        return $this->forbidCheckCache[$cacheKey] = $isForbidden;
    }

    // =========================================================================
    // ⬇️ ACCESSORS / MUTATORS (DATA CLEANING & DISPLAY) ⬇️
    // =========================================================================

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
            // DEV NOTE: Check for null/empty value before processing.
            set: fn(?string $value) => ($value === null || trim($value) === '')
                ? $value
                : ucwords(strtolower(trim($value))),
        );
    }

    /**
     * Accessor for avatar URL.
     * Handles the default 'blank.png' state and returns an absolute URL.
     */
    public function getAvatarUrlAttribute(): string {
        // DEV NOTE: Concise logic flow using ternary operator.
        $path = (empty($this->avatar) || $this->avatar === 'blank.png')
            ? 'assets/media/avatars/blank.png'    // Assumes public assets folder
            : 'storage/' . $this->avatar;         // Assumes files served via /storage link

        return asset($path);
    }

    /**
     * Get the user's online status based on the active marker in Redis/Cache.
     * This makes $this->is_online available.
     * OPTIMIZATION: Uses the Attribute cast for cleaner syntax.
     */
    protected function isOnline(): Attribute {
        return Attribute::make(
            // DEV NOTE: Uses the ActiveUserHelper::isUserActive() which utilizes Cache::has().
            get: fn() => ActiveUserHelper::isUserActive($this->id),
        );
    }

    // =========================================================================
    // ⬇️ USER STATUS LOGIC (AVAILABILITY) ⬇️
    // =========================================================================

    /**
     * Checks if the user is available to perform actions (not blocked, disabled, or actively suspended).
     */
    public function isActive(): bool {
        // 1. Permanently blocked or disabled users are never active
        if (in_array($this->status, ['blocked', 'disabled'])) {
            return false;
        }

        // 2. Suspended users are not active until suspension expires
        // DEV NOTE: Relies on the isSuspended() helper.
        if ($this->isSuspended()) {
            return false;
        }

        // Status is 'active' or 'suspended' with an expired timestamp
        return true;
    }

    /**
     * Checks if the user is currently under an active suspension.
     */
    public function isSuspended(): bool {
        // DEV NOTE: $this->suspended_until is cast to Carbon, so we check if it's not null AND in the future.
        return $this->status === 'suspended' && $this->suspended_until && now()->lt($this->suspended_until);
    }

    /**
     * Returns a human-readable string for the remaining suspension time (e.g., '1h 30m').
     */
    public function remainingSuspension(): ?string {
        if (!$this->isSuspended()) return null;

        // OPTIMIZATION: Use $this->suspended_until which is cast to a Carbon instance.
        // DEV NOTE: Uses diffForHumans with short parts for a concise output.
        return $this->suspended_until->diffForHumans(now(), [
            'parts' => 2,
            'short' => true,
            'syntax' => Carbon::DIFF_ABSOLUTE, // CRITICAL: Forces output like "1h 30m" instead of "in 1h 30m"
        ]);
    }

    // =========================================================================
    // ⬇️ MANAGEMENT ACTIONS (SESSIONS & FORBIDS) ⬇️
    // =========================================================================

    /**
     * Invalidates all user sessions across supported drivers.
     * SECURITY: Crucial for immediate logouts upon password change, suspension, etc.
     *
     * DEV NOTE: The Redis/File logic is inherently slow. If high-performance revocation is needed,
     * consider using a custom session handler (like Redis) and adding a 'user_id' field to the key/payload.
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
                // File-based sessions (slow/risky, requires reading file content)
                $path = storage_path('framework/sessions');
                $userIdString = (string) $this->id;

                // DEV NOTE: Using glob is a simple way, but slow on large session directories.
                foreach (File::glob("$path/*") as $file) {
                    if (str_contains(File::get($file), $userIdString)) {
                        @unlink($file); // @ hides permission errors
                    }
                }
                break;

            case 'redis':
                // Redis sessions (requires scanning keys and deserializing payload)
                $redis = Redis::connection(config('session.connection'));
                $prefix = config('session.prefix', 'laravel:');
                $cursor = 0;

                // OPTIMIZATION: Use SCAN instead of KEYS for large datasets to avoid blocking the server.
                do {
                    [$cursor, $keys] = $redis->scan($cursor, 'MATCH', "{$prefix}sessions:*", 'COUNT', 1000);
                    $pipeline = $redis->pipeline();

                    foreach ($keys as $key) {
                        // This is still slow, as it requires getting/checking the payload
                        $session = $redis->get($key);
                        if ($session && str_contains($session, (string) $this->id)) {
                            $pipeline->del($key);
                        }
                    }
                    $pipeline->exec();
                } while ($cursor !== 0);
                break;
                // No action for 'array' or 'cookie' drivers as they don't persist globally.
        }
    }


    /**
     * Terminates a specific active session belonging to this user.
     * CRITICAL: Relies on the sessions() relationship to ensure security.
     *
     * @param string $sessionId The unique ID of the session record (the 'id' column).
     * @return int The number of sessions deleted (0 or 1).
     */
    public function revokeSpecificSession(string $sessionId): int {
        // This is the secure call. Eloquent ensures the deletion
        // is scoped ONLY to sessions linked to $this user instance.
        return $this->sessions()
            ->where('id', $sessionId)
            ->delete();
    }

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
}
