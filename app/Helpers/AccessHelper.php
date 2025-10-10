<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserTemporaryPermission; // Explicitly use the model
use App\Models\User; // Use for type hinting

class AccessHelper {
    /**
     * Cache for active temporary permissions within a single request lifecycle.
     * @var array<int, array<string>>
     */
    protected static array $tempPermissionsCache = [];

    /**
     * Check if the current user can perform an action.
     *
     * @param string $permission The permission string to check (e.g., 'user_view_self').
     * @param string|null $scope Optional scope/context.
     * @return bool
     */
    public static function can(string $permission, ?string $scope = null): bool {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // 1. Check forbid lookup (user-level block always takes highest precedence)
        // If the user is forbidden, we return false immediately.
        if ($user->isForbidden($permission, $scope)) {
            return false;
        }

        // 2. Direct permission check (matches exactly or via role)
        if ($user->can($permission)) {
            return true;
        }

        // 3. Implicit hierarchy logic: Check for the broader '_any' permission.
        // We only check this if the direct permission failed and the permission ends in '_self'.
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
     * @return array<string>
     */
    public static function getActiveTemporaryPermissions(int $userId): array {
        if (isset(self::$tempPermissionsCache[$userId])) {
            return self::$tempPermissionsCache[$userId];
        }

        $permissions = UserTemporaryPermission::query()
            ->where('user_id', $userId)
            // Use now() from Laravel/Carbon which is typically faster and more correct than raw SQL NOW()
            ->where('expires_at', '>', now())
            ->pluck('permission_name')
            ->toArray();

        // Cache and return
        return self::$tempPermissionsCache[$userId] = $permissions;
    }

    /**
     * Authorize the action or abort with a 403 error.
     *
     * @param string $permission
     * @return void
     */
    public static function authorize(string $permission): void {
        if (!self::can($permission)) {
            // Using the native Laravel helper for a clean 403 response
            abort(403, 'Access denied.');
        }
    }

    /**
     * Maps and enriches a list of permission names with configuration details and status flags.
     *
     * Optimization: Pre-loads config and uses O(1) hash map lookups.
     *
     * @param array<string> $userPermissions All unique permission names (direct, role, temp, forbid).
     * @param array<string> $forbiddenKeys List of forbidden permission names.
     * @param array<string> $tempPerms List of temporary permission names.
     * @return array<array<string, mixed>>
     */
    public static function describePermissions(array $userPermissions, array $forbiddenKeys, array $tempPerms): array {
        // 1. Load the entire config list once (Optimization)
        $permissionsConfig = config('permissions.list', []);

        // 2. Create O(1) lookup hash maps (Optimization)
        $forbiddenLookup = array_flip($forbiddenKeys);
        $temporaryLookup = array_flip($tempPerms);

        return collect($userPermissions)->map(function ($perm) use ($permissionsConfig, $forbiddenLookup, $temporaryLookup) {
            $config = $permissionsConfig[$perm] ?? [];

            // Use faster isset() check instead of slow in_array()
            $isForbidden = isset($forbiddenLookup[$perm]);
            $isTemporary = isset($temporaryLookup[$perm]);

            return [
                'name'        => $perm,
                'label'       => $config['label'] ?? $perm,
                'category'    => $config['category'] ?? 'Uncategorized',
                'forbidden'   => $isForbidden,
                'temporary'   => $isTemporary,
            ];
        })->toArray();
    }
}
