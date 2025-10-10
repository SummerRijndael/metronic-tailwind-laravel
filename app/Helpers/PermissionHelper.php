<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserForbid;
use Illuminate\Support\Collection;

class PermissionHelper {
    /**
     * Get all permissions assigned to the user (role-based + direct),
     * annotated with display label, category, and forbid status.
     */
    public static function getUserPermissions(User $user): Collection {
        $config = config('permissions.list', []);
        $forbids = UserForbid::where('user_id', $user->id)->pluck('permission_name')->toArray();

        // Get permissions from roles + direct user assignment
        $rolePerms = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $directPerms = $user->permissions->pluck('name')->toArray();

        // Merge all permissions
        $merged = collect(array_unique(array_merge($rolePerms, $directPerms)));

        return $merged->map(function ($perm) use ($rolePerms, $directPerms, $forbids, $config) {
            $data = $config[$perm] ?? ['label' => $perm, 'category' => 'Uncategorized'];

            return [
                'name' => $perm,
                'label' => $data['label'],
                'category' => $data['category'],
                'source' => in_array($perm, $forbids)
                    ? 'forbidden'
                    : (in_array($perm, $directPerms) ? 'direct' : 'role'),
                'forbidden' => in_array($perm, $forbids),
            ];
        })->sortBy('category')->values();
    }

    /**
     * Get the human-readable label for a permission.
     */
    public static function getLabel(string $perm): string {
        return config("permissions.list.$perm.label", $perm);
    }
}
