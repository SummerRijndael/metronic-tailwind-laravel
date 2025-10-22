<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserTemporaryPermission;
use App\Models\User;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;
use App\Enums\ActivitySource;
use Illuminate\Support\Str;
use Throwable;

class AccessHelper {
    protected static array $tempPermissionsCache = [];

    protected static array $criticalPermissions = [
        'user_delete',
        'role_edit',
        'settings_edit_security',
        'report_view_financial',
        'post_publish',
    ];

    public static function can(string $permission, ?string $scope = null): bool {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) return false;

        try {
            $isCritical = in_array($permission, self::getCriticalPermissions(), true);

            // --- 1. Forbidden
            if ($user->isForbidden($permission, $scope)) {
                if ($isCritical) {
                    self::logActivity($user, ActivityAction::ACCESS_DENIED, [
                        'permission' => $permission,
                        'scope' => $scope,
                        'icon' => 'ki-filled ki-lock',
                        'color' => 'bg-danger/60',
                    ], "Critical permission '{$permission}' explicitly forbidden for user.");
                }
                return false;
            }

            // --- 2. Granted directly or via role
            if ($user->can($permission)) {
                if ($isCritical) {
                    self::logActivity($user, ActivityAction::ACCESS_GRANTED, [
                        'permission' => $permission,
                        'scope' => $scope,
                        'icon' => 'ki-filled ki-badge-check',
                        'color' => 'bg-success/60',
                    ], "Critical permission '{$permission}' granted via role or direct assignment.");
                }
                return true;
            }

            // --- 3. Inherited (_self → _any)
            if (str_ends_with($permission, '_self')) {
                $anyPermission = str_replace('_self', '_any', $permission);
                if ($user->can($anyPermission)) {
                    if ($isCritical) {
                        self::logActivity($user, ActivityAction::ACCESS_GRANTED_INHERITED, [
                            'permission' => $permission,
                            'scope' => $scope,
                            'icon' => 'ki-filled ki-hierarchy',
                            'color' => 'bg-info/60',
                        ], "Critical permission '{$permission}' granted via broader '{$anyPermission}'.");
                    }
                    return true;
                }
            }

            // --- 4. Default deny
            if ($isCritical) {
                self::logActivity($user, ActivityAction::ACCESS_DENIED, [
                    'permission' => $permission,
                    'scope' => $scope,
                    'icon' => 'ki-filled ki-shield',
                    'color' => 'bg-danger/60',
                ], "Critical permission '{$permission}' denied by default (no matching grants).");
            }
        } catch (Throwable $e) {
            \Log::warning("AccessHelper audit trail failed for {$permission}: {$e->getMessage()}", [
                'user_id' => $user->id ?? null,
            ]);
        }

        return false;
    }

    public static function getActiveTemporaryPermissions(int $userId): array {
        if (isset(self::$tempPermissionsCache[$userId])) {
            return self::$tempPermissionsCache[$userId];
        }

        $permissions = UserTemporaryPermission::query()
            ->where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->pluck('permission_name')
            ->toArray();

        return self::$tempPermissionsCache[$userId] = $permissions;
    }

    public static function authorize(string $permission): void {
        if (!self::can($permission)) {
            if (in_array($permission, self::getCriticalPermissions(), true)) {
                self::logActivity(Auth::user(), ActivityAction::UNAUTHORIZED_ATTEMPT, [
                    'permission' => $permission,
                    'icon' => 'ki-filled ki-alert',
                    'color' => 'bg-warning/60',
                ], "Critical unauthorized attempt: {$permission}.");
            }
            abort(403, 'Access denied.');
        }
    }

    protected static function getCriticalPermissions(): array {
        $list = config('permissions.list', []);
        $critical = [];

        foreach ($list as $key => $item) {
            if (isset($item['category']) && in_array($item['category'], [
                'Access Control',
                'Finance',
                'Reports'
            ])) {
                $critical[] = $key;
            }
        }

        return array_unique(array_merge(self::$criticalPermissions, $critical));
    }

    public static function describePermissions(array $userPermissions, array $forbiddenKeys, array $tempPerms): array {
        $permissionsConfig = config('permissions.list', []);
        $forbiddenLookup = array_flip($forbiddenKeys);
        $temporaryLookup = array_flip($tempPerms);

        return collect($userPermissions)->map(function ($perm) use ($permissionsConfig, $forbiddenLookup, $temporaryLookup) {
            $config = $permissionsConfig[$perm] ?? [];

            return [
                'name'      => $perm,
                'label'     => $config['label'] ?? Str::title(str_replace('_', ' ', $perm)),
                'category'  => $config['category'] ?? 'Uncategorized',
                'forbidden' => isset($forbiddenLookup[$perm]),
                'temporary' => isset($temporaryLookup[$perm]),
            ];
        })->toArray();
    }

    /**
     * Centralized structured logger
     */

    protected static function logActivity(User $user, ActivityAction $action, array $meta, string $message): void {
        // Determine source automatically
        $source = app()->runningInConsole()
            ? ActivitySource::SYSTEM->value
            : ActivitySource::WEB->value;

        // Minimal enum usage: Category, Action, Source
        ActivityLogger::category(ActivityCategory::SECURITY)
            ->action($action)
            ->source($source)
            ->message($message)
            ->meta(array_merge($meta, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ]))
            ->user($user)
            ->log();
    }
}
