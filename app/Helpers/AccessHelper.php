<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserTemporaryPermission; // Explicitly use the model
use App\Models\User; // Use for type hinting
use Illuminate\Support\Str;

/**
 * AccessHelper
 *
 * Provides a standardized and request-optimized layer for checking user permissions.
 * It integrates standard user permissions, temporary permissions, and explicit
 * forbidden checks (user-level blocks) into a clear precedence order.
 *
 * NOTE: This helper relies on the underlying User model providing methods for
 * isForbidden() and can() (e.g., via a Spatie or custom permission package).
 */
class AccessHelper {
    /**
     * Cache for active temporary permissions within a single request lifecycle (Memoization).
     *
     * This prevents multiple database lookups for the same user's temporary permissions
     * during a single request (e.g., during menu rendering or multiple policy checks).
     *
     * @var array<int, array<string>> Key is user ID, value is array of active permission names.
     */
    protected static array $tempPermissionsCache = [];

    /**
     * Check if the current user can perform an action.
     *
     * Permission Precedence (highest to lowest):
     * 1. Explicit Forbid (Block)
     * 2. Direct/Role Permission
     * 3. Implicit Hierarchy (e.g., 'user_any' implies 'user_self')
     * 4. Default Deny
     *
     * @param string $permission The permission string to check (e.g., 'user_view_self').
     * @param string|null $scope Optional scope/context for the check (used by underlying permission package).
     * @return bool
     */
    public static function can(string $permission, ?string $scope = null): bool {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            // Cannot grant permission if the user is not authenticated.
            return false;
        }

        // 1. Check forbid lookup (user-level block always takes highest precedence)
        // Check if the permission has been explicitly denied to this user.
        if ($user->isForbidden($permission, $scope)) {
            return false;
        }

        // 2. Direct permission check (matches exactly or via assigned role)
        // This relies on the standard Laravel Auth / Permission package integration.
        if ($user->can($permission)) {
            return true;
        }

        // 3. Implicit hierarchy logic: Check for the broader '_any' permission.
        // If 'user_view_self' failed, check if they have the broader 'user_view_any'.
        if (str_ends_with($permission, '_self')) {
            $anyPermission = str_replace('_self', '_any', $permission);

            if ($user->can($anyPermission)) {
                return true;
            }
        }

        // 4. Default deny
        return false;
    }

    /**
     * Retrieves and caches the active temporary permissions for a user.
     *
     * Optimization: Uses static caching (memoization) to prevent repeated
     * database queries within the same request lifecycle.
     *
     * @param int $userId
     * @return array<string> List of active temporary permission names.
     */
    public static function getActiveTemporaryPermissions(int $userId): array {
        // Return cached result if available
        if (isset(self::$tempPermissionsCache[$userId])) {
            return self::$tempPermissionsCache[$userId];
        }

        // Query database for non-expired temporary permissions
        $permissions = UserTemporaryPermission::query()
            ->where('user_id', $userId)
            // Use now() to compare against the 'expires_at' column.
            ->where('expires_at', '>', now())
            ->pluck('permission_name')
            ->toArray();

        // Cache the result for this request and return
        return self::$tempPermissionsCache[$userId] = $permissions;
    }

    /**
     * Authorize the action or abort with a 403 error.
     *
     * This is a simple wrapper for `can()` that enforces a security boundary.
     * Intended for use in controllers, middleware, or service classes.
     *
     * @param string $permission The permission to check.
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 on failure.
     */
    public static function authorize(string $permission): void {
        if (!self::can($permission)) {
            // Using the native Laravel helper for a clean 403 forbidden response.
            abort(403, 'Access denied.');
        }
    }

    /**
     * Maps and enriches a list of permission names with configuration details and status flags.
     *
     * Optimization: Pre-loads the configuration array and uses O(1) hash map lookups
     * (`array_flip` + `isset`) instead of the slower O(N) `in_array()` check.
     *
     * @param array<string> $userPermissions All unique permission names the user has (direct, role, temp, forbid).
     * @param array<string> $forbiddenKeys List of forbidden permission names.
     * @param array<string> $tempPerms List of active temporary permission names.
     * @return array<array<string, mixed>> Detailed list of permission objects for display/UI.
     */
    public static function describePermissions(array $userPermissions, array $forbiddenKeys, array $tempPerms): array {
        // 1. Load the entire config list once (Optimization)
        $permissionsConfig = config('permissions.list', []);

        // 2. Create O(1) lookup hash maps (Optimization)
        // Flip arrays to allow instantaneous key lookups instead of iterating.
        $forbiddenLookup = array_flip($forbiddenKeys);
        $temporaryLookup = array_flip($tempPerms);

        return collect($userPermissions)->map(function ($perm) use ($permissionsConfig, $forbiddenLookup, $temporaryLookup) {
            $config = $permissionsConfig[$perm] ?? [];

            // Use faster isset() check instead of slow in_array()
            $isForbidden = isset($forbiddenLookup[$perm]);
            $isTemporary = isset($temporaryLookup[$perm]);

            return [
                'name'        => $perm,
                'label'       => $config['label'] ?? Str::title(str_replace('_', ' ', $perm)), // Default to title-cased name if label is missing
                'category'    => $config['category'] ?? 'Uncategorized',
                'forbidden'   => $isForbidden,
                'temporary'   => $isTemporary,
            ];
        })->toArray();
    }
}
