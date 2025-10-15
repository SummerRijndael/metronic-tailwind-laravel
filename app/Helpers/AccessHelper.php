<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserTemporaryPermission;
use App\Models\User;
use Illuminate\Support\Str;
use Throwable;

class AccessHelper {
    /**
     * @var array $tempPermissionsCache Caches active temporary permissions per user ID
     * for the duration of a single request to prevent redundant DB lookups.
     */
    protected static array $tempPermissionsCache = [];

    /**
     * @var array $criticalPermissions Base list of permissions deemed critical for auditing.
     * These should be strictly defined as actions that are sensitive, high-impact,
     * or related to security/finance.
     */
    protected static array $criticalPermissions = [
        'user_delete',
        'role_edit',
        'settings_edit_security',
        'report_view_financial',
        'post_publish',
    ];

    /**
     * Check if the current user can perform an action.
     * LOGGING OPTIMIZATION: Only logs access grants/denials if the permission is
     * defined as critical by getCriticalPermissions().
     *
     * @param string $permission The permission string (e.g., 'user_create', 'post_edit_self').
     * @param string|null $scope Optional scope identifier.
     * @return bool
     */
    public static function can(string $permission, ?string $scope = null): bool {
        /** @var User|null $user */
        $user = Auth::user();
        // DEV NOTE: Early exit if no authenticated user is present.
        if (!$user) return false;

        try {
            // OPTIMIZATION KEY: Determine upfront if the permission is worth logging.
            $isCritical = in_array($permission, self::getCriticalPermissions(), true);

            // --- 1. Explicit forbidden check (highest precedence)
            if ($user->isForbidden($permission, $scope)) {
                // SECURITY AUDIT: Log only if critical. A forbidden check is often
                // a security fail, so logging is essential here for critical actions.
                if ($isCritical) {
                    logUserActivity(
                        'access_denied',
                        "Critical permission '{$permission}' explicitly forbidden for user.",
                        [
                            'permission' => $permission,
                            'scope' => $scope,
                            'ip' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ],
                        $user
                    );
                }
                return false;
            }

            // --- 2. Direct or role-based permission check
            if ($user->can($permission)) {
                // SECURITY AUDIT: Log successful access to critical actions.
                if ($isCritical) {
                    logUserActivity(
                        'access_granted',
                        "Critical permission '{$permission}' granted via role or direct assignment.",
                        [
                            'permission' => $permission,
                            'scope' => $scope,
                            'ip' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ],
                        $user
                    );
                }
                return true;
            }

            // --- 3. Hierarchy fallback (_self → _any)
            if (str_ends_with($permission, '_self')) {
                $anyPermission = str_replace('_self', '_any', $permission);
                if ($user->can($anyPermission)) {
                    // SECURITY AUDIT: Log inherited access for critical actions.
                    if ($isCritical) {
                        logUserActivity(
                            'access_granted_inherited',
                            "Critical permission '{$permission}' granted via broader '{$anyPermission}'.",
                            [
                                'permission' => $permission,
                                'scope' => $scope,
                                'ip' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ],
                            $user
                        );
                    }
                    return true;
                }
            }

            // --- 4. Default deny (log only critical)
            // SECURITY AUDIT: Log a denial for a critical action if no grant was found.
            // This flags potential security holes or misconfiguration.
            if ($isCritical) {
                logUserActivity(
                    'access_denied',
                    "Critical permission '{$permission}' denied by default (no matching grants).",
                    [
                        'permission' => $permission,
                        'scope' => $scope,
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ],
                    $user
                );
            }
        } catch (Throwable $e) {
            // DEV NOTE: Catch any exceptions during the permission check/logging
            // itself. Log a warning but still fail safe by returning false below.
            \Log::warning("AccessHelper audit trail failed for {$permission}: {$e->getMessage()}", [
                'user_id' => $user->id ?? null,
            ]);
        }

        return false;
    }

    /**
     * Get active temporary permissions with caching.
     * NOTE: Removed the logging from this function to avoid bloat, assuming
     * temporary permission creation/deletion is logged elsewhere (e.g., Admin controller).
     * * @param int $userId The ID of the user to check.
     * @return array An array of active permission names.
     */
    public static function getActiveTemporaryPermissions(int $userId): array {
        if (isset(self::$tempPermissionsCache[$userId])) {
            return self::$tempPermissionsCache[$userId];
        }

        // Query the database for temporary permissions that have not yet expired.
        $permissions = UserTemporaryPermission::query()
            ->where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->pluck('permission_name')
            ->toArray();

        // Cache the result for the rest of the request lifecycle.
        return self::$tempPermissionsCache[$userId] = $permissions;
    }

    /**
     * Authorize and abort if access is denied.
     * Logs unauthorized attempt only if the permission is critical.
     *
     * @param string $permission The permission string to check.
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public static function authorize(string $permission): void {
        if (!self::can($permission)) {
            // SECURITY AUDIT: Separate check for logging unauthorized attempts,
            // maintaining the critical-only log policy.
            if (in_array($permission, self::getCriticalPermissions(), true)) {
                logUserActivity(
                    'unauthorized_attempt',
                    "Critical unauthorized attempt: {$permission}.",
                    [
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ],
                    Auth::user()
                );
            }
            // Always terminate the request on denial.
            abort(403, 'Access denied.');
        }
    }

    /**
     * Dynamically pulls critical permissions based on hardcoded list and configuration.
     *
     * @return array The merged, unique list of critical permission keys.
     */
    protected static function getCriticalPermissions(): array {
        // DEV NOTE: This method dynamically extends the hardcoded list with
        // permissions defined in config/permissions.php that match sensitive categories.
        $list = config('permissions.list', []);
        $critical = [];

        foreach ($list as $key => $item) {
            if (isset($item['category']) && in_array($item['category'], [
                'Access Control',
                'Finance',
                'Reports' // Sensitive categories
            ])) {
                $critical[] = $key;
            }
        }

        // Merge hardcoded list with dynamically determined list and ensure uniqueness.
        return array_unique(array_merge(self::$criticalPermissions, $critical));
    }

    /**
     * Takes a raw list of user permissions and enriches them with labels, categories,
     * and status flags (forbidden, temporary) for display in a user interface.
     *
     * @param array $userPermissions All granted permission keys.
     * @param array $forbiddenKeys All explicitly forbidden permission keys.
     * @param array $tempPerms All active temporary permission keys.
     * @return array
     */
    public static function describePermissions(array $userPermissions, array $forbiddenKeys, array $tempPerms): array {
        $permissionsConfig = config('permissions.list', []);
        // DEV NOTE: Using array_flip for O(1) lookups.
        $forbiddenLookup = array_flip($forbiddenKeys);
        $temporaryLookup = array_flip($tempPerms);

        return collect($userPermissions)->map(function ($perm) use ($permissionsConfig, $forbiddenLookup, $temporaryLookup) {
            $config = $permissionsConfig[$perm] ?? [];

            return [
                'name'         => $perm,
                // Fallback to title-cased string if no config label is found
                'label'        => $config['label'] ?? Str::title(str_replace('_', ' ', $perm)),
                'category'     => $config['category'] ?? 'Uncategorized',
                'forbidden'    => isset($forbiddenLookup[$perm]),
                'temporary'    => isset($temporaryLookup[$perm]),
            ];
        })->toArray();
    }
}
