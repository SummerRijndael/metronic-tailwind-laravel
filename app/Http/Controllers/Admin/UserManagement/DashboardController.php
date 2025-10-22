<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Enums\ActivityAction;
use App\Enums\ActivityCategory;
use App\Enums\ActivityLevel;
use App\Enums\ActivitySource;
use App\Enums\ActivitySubject;
use App\Enums\ActivityTarget;
use App\Helpers\ActivityLogger;

use App\Helpers\AccessHelper;
use App\Helpers\ActiveUserHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemActivityLog;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Services\SessionManagerService;
use Illuminate\Database\Eloquent\Collection; // 🚀 Use Collection type hint


class DashboardController extends Controller {

    /**
     * DEV NOTE: Use constructor property promotion for clean dependency injection.
     * The SessionManagerService is injected and assigned to a protected property.
     */
    public function __construct(protected SessionManagerService $sessionManager) {
        //
    }

    /**
     * Show the admin dashboard for user management metrics.
     * Uses highly optimized single-query methods for counts.
     */
    public function index(): View {
        // DEV NOTE: Authorization Check should always be the first line of any secured method.
        AccessHelper::authorize('user_view_any');

        // 2. Online Users (OPTIMIZATION: ActiveUserHelper handles this efficiently, no change needed)
        $onlineUsers = ActiveUserHelper::getActiveUsers();
        $onlineCount = $onlineUsers->count();

        // 3. User Status Counts (Highly Efficient: Single COUNT/SUM query)
        $userStatusCounts = User::selectRaw("
            COUNT(*) as totalUsers,
            SUM(status = 'active') as activeUsers,
            SUM(status = 'blocked') as blockedUsers,
            SUM(status = 'suspended') as suspendedUsers,
            SUM(status = 'disabled') as disabledUsers,
            SUM(email_verified_at IS NOT NULL) as verifiedUsers,
            SUM(email_verified_at IS NULL) as unverifiedUsers
        ")->first();

        // 5. Role Counts (Efficient: Uses withCount() on the Role model)
        $rolesToCount = ['Admin', 'Editor', 'User'];
        $roleCounts = Role::whereIn('name', $rolesToCount)
            ->withCount('users')
            ->get()
            ->keyBy('name')
            ->map->users_count;

        // Count users who are assigned *any* role (for context)
        // DEV NOTE: Check if $rolesToCount is too restrictive; sometimes all roles are desired.
        $viaRbacRole = User::role($rolesToCount)->count();
        // Count users with no role assigned
        $directlyAssigned = User::whereDoesntHave('roles')->count();
        $permOverrides = 0; // Placeholder for future feature

        return view('pages.user-management.user-management-dash', [
            // Status Metrics (All from the single query result)
            'totalUsers'       => $userStatusCounts->totalUsers,
            'loggedInNow'      => $onlineCount, // Renamed to clearly indicate a count
            'activeUsers'      => $userStatusCounts->activeUsers,
            'blockedUsers'     => $userStatusCounts->blockedUsers,
            'suspendedUsers'   => $userStatusCounts->suspendedUsers,
            'disabledUsers'    => $userStatusCounts->disabledUsers,
            'verifiedUsers'    => $userStatusCounts->verifiedUsers,
            'unverifiedUsers'  => $userStatusCounts->unverifiedUsers,

            // Role Metrics (All from the efficient Role::withCount query)
            'administrators'   => $roleCounts->get('Admin', 0),
            'editors'          => $roleCounts->get('Editor', 0),
            'standardUsers'    => $roleCounts->get('User', 0),
            'viaRbacRole'      => $viaRbacRole,
            'directlyAssigned' => $directlyAssigned,
            'permOverrides'    => $permOverrides,
        ]);
    }

    // ---
    // User List for Datatable
    // ---

    /**
     * Handles the AJAX request for the user list datatable.
     */
    public function list(Request $request): JsonResponse {
        // 1. Authorization and Input Validation
        AccessHelper::authorize('user_view_any');

        // DEV NOTE: Destructure and validate input using Laravel's request validation for cleaner code,
        // but the manual validation here is clear and functional for the data table.
        $search       = trim(strip_tags($request->input('search', '')));
        $sortField    = $request->input('sortField', 'created_at');
        $sortOrder    = strtolower($request->input('sortOrder', 'desc'));
        $pageSize     = (int) $request->input('size', 10);
        $page         = (int) $request->input('page', 1);
        $statusFilter = trim(strip_tags($request->input('status', '')));
        $colSortParam = trim(strip_tags($request->input('col_sort', '')));

        // OPTIMIZATION: Define defaults and safe range check in one go.
        $validSortFields = ['id', 'name', 'lastname', 'email', 'created_at', 'full_name', 'status'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'created_at';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';
        $pageSize = ($pageSize > 0 && $pageSize <= 100) ? $pageSize : 10;
        $page = $page > 0 ? $page : 1;

        // 2. Handle optional date range
        $dateRange = null;
        if (!empty($colSortParam)) {
            $parts = array_map('trim', explode(' to ', $colSortParam));
            if (count($parts) === 2) {
                // DEV NOTE: Carbon::parse is robust but adding an explicit try/catch for production safety is good practice.
                try {
                    $dateRange = [
                        'start' => Carbon::parse($parts[0])->startOfDay(),
                        'end'   => Carbon::parse($parts[1])->endOfDay()
                    ];
                } catch (\Exception $e) {
                    // Log invalid date range but continue without the filter
                    \Log::warning("Invalid date range received: {$colSortParam}");
                }
            }
        }

        // 3. Build Query
        // DEV NOTE: Select only the necessary columns (already doing this, which is great).
        $query = User::with(['roles:id,name'])
            ->select('id', 'public_id', 'avatar', 'name', 'lastname', 'email', 'created_at', 'two_factor_secret', 'status');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // OPTIMIZATION: CONCAT is great for search; ensure you have appropriate indexes on 'name' and 'lastname'.
                $q->where(DB::raw("CONCAT(name, ' ', lastname)"), 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        if ($dateRange) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        // 4. Sorting
        if ($sortField === 'full_name') {
            // OPTIMIZATION: Order by name and then lastname for consistent sorting of full names.
            $query->orderBy('name', $sortOrder)->orderBy('lastname', $sortOrder);
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        // 5. Paginate and Format
        $usersPaginator = $query->paginate($pageSize, ['*'], 'page', $page);

        $formattedUsers = $usersPaginator->getCollection()->map(function ($user) {
            // DEV NOTE: Using accessors like $user->avatar_url and $user->is_online is clean and highly recommended.
            $fullName = trim(($user->name ?? '') . ' ' . ($user->lastname ?? ''));
            return [
                'id'          => $user->public_id ?? '—',
                'avatar'      => $user->avatar_url,
                'full_name'   => $fullName ?: 'Unnamed User',
                'email'       => $user->email ?? '—',
                // OPTIMIZATION: Simplified role access.
                'role_name'   => $user->roles->first()->name ?? 'Standard User',
                'created_at'  => optional($user->created_at)->format('Y-m-d H:i:s'),
                'twfa_stat'   => !empty($user->two_factor_secret) ? 'Active' : 'Disabled',
                'status'      => $user->status ?? 'unknown',
                'statusLabel' => ucfirst($user->status ?? 'Unknown'),
                'is_online'   => $user->is_online,
            ];
        });

        return response()->json([
            'data'        => $formattedUsers,
            'page'        => $usersPaginator->currentPage(),
            'pageSize'    => $usersPaginator->perPage(),
            'totalPages'  => $usersPaginator->lastPage(),
            'totalCount'  => $usersPaginator->total(),
        ]);
    }

    // ---
    // User Profile Show
    // ---

    /**
     * Show (ADMIN) - retrieves a full, structured payload for a user's admin profile.
     * @param Request $request The request instance.
     * @param User $user The User model instance retrieved by route model binding.
     * @return View
     */
    public function show(Request $request, User $user): View {
        // DEV NOTE: When using Type Hinting (User $user) with no '?' nullable check, Laravel automatically 404s
        // if the model isn't found, making the manual `is_null($user)` check redundant.
        // If your route uses the public_id for lookup, ensure your route model binding configuration is correct.

        // Admin authorization
        AccessHelper::authorize('user_view_any');

        // Eager load necessary relations (sessions are typically handled by the service, but roles/permissions are needed)
        // OPTIMIZATION: Use select() on relations to limit columns if possible, but the original is fine.
        $profileUser = $user->load(['permissions', 'forbids', 'roles']);

        // Fetch transformed sessions via service (frontend-ready)
        $activeSessions = $this->sessionManager->getActiveSessionsForUser($profileUser);

        // -----------------------
        // Permissions Preparation
        // OPTIMIZATION: Combine temporary, forbidden, and permanent permissions into a single, clean map.
        // -----------------------
        $permissionsFlat = $this->preparePermissionsForUser($profileUser);

        // Group by category for front-end consumption
        $permissionsGrouped = $permissionsFlat->groupBy('category')->map(function ($items) {
            return $items->values();
        })->toArray();

        // -----------------------
        // Activities: all logs related to this user (limit 50)
        // OPTIMIZATION: Move the complex transformation logic to a dedicated private method for readability.
        // -----------------------
        $rawActivities = SystemActivityLog::query()
            ->where('user_id', $profileUser->id)
            ->latest()
            ->limit(50)
            ->get();

        $activities = $this->formatSystemActivities($rawActivities);

        // -----------------------
        // Sessions: service returned (ensure array safe shape)
        // -----------------------
        $sessions = collect($activeSessions)->map(function ($s) {
            // DEV NOTE: Normalizing session data returned from the service.
            // Assuming the SessionManagerService returns keys like 'last_active_at_absolute'.
            return [
                'id'         => $s['id'] ?? null,
                'ip_address' => $s['ip_address'] ?? null,
                'device'     => $s['device'] ?? ($s['user_agent'] ?? 'Unknown Device'),
                'location'   => $s['location'] ?? 'Unknown Location',
                'is_current' => $s['is_current'] ?? false,
                'last_active' => [
                    'relative' => $s['last_active_at_relative'] // 1. Try service-provided relative time
                        ?? optional(isset($s['last_active_at']) ? Carbon::parse($s['last_active_at']) : null)->diffForHumans() // 2. If not, safely check for 'last_active_at'
                        ?? 'just now', // 3. Fallback text
                    'absolute' => $s['last_active_at_absolute'] ?? null,
                ],
            ];
        })->values()->toArray();


        // -----------------------
        // Build user / security / system clusters (balanced & safe)
        // -----------------------
        $userCluster = [
            'public_id'  => $profileUser->public_id,
            'full_name'  => trim(($profileUser->name ?? '') . ' ' . ($profileUser->lastname ?? '')),
            'first_name' => $profileUser->name ?? null,
            'last_name'  => $profileUser->lastname ?? null,
            'email'      => $profileUser->email ?? null,
            'avatar'     => $profileUser->avatar_url ?? null, // Accessor is cleaner
            'role'       => $profileUser->getRoleNames()->first() ?? null,
        ];

        $securityCluster = [
            // OPTIMIZATION: $profileUser->hasTwoFactorEnabled() is often a cleaner accessor/method on the User model
            'two_factor_enabled' => !empty($profileUser->two_factor_confirmed_at),
            'status'             => $profileUser->status ?? 'unknown',
            'is_online'          => $profileUser->is_online ?? false, // Accessor is preferred
            'is_locked'          => $profileUser->is_locked ?? false,
        ];

        $systemCluster = [
            'created_at' => [
                'relative' => optional($profileUser->created_at)->diffForHumans(),
                'absolute' => optional($profileUser->created_at)->format('Y-m-d H:i:s'),
            ],
            'last_updated_at' => [
                'relative' => optional($profileUser->updated_at)->diffForHumans(),
                'absolute' => optional($profileUser->updated_at)->format('Y-m-d H:i:s'),
            ],
        ];

        // -----------------------
        // Final payload under $data root
        // -----------------------
        $data = [
            'user'        => $userCluster,
            'security'    => $securityCluster,
            'system'      => $systemCluster,
            'permissions' => $permissionsGrouped,
            'sessions'    => $sessions,
            'activities'  => $activities,
        ];

        // -----------------------
        // Activity Logging (Optimized with Debounce)
        // -----------------------
        // DEV NOTE: Debounce the 'USER_VIEWED' log to prevent excessive database writes
        // when an admin refreshes the page multiple times.
        $shouldLogView = SystemActivityLog::query()
            ->where('user_id', $request->user()->id) // The admin performing the action
            ->where('action', ActivityAction::USER_VIEWED)
            ->whereJsonContains('meta->viewed_user_id', $profileUser->id) // The user being viewed
            ->where('created_at', '>', Carbon::now()->subMinutes(5)) // Check if a log exists in the last 5 minutes
            ->doesntExist(); // Only log if no recent activity exists

        if ($shouldLogView) {
            try {
                ActivityLogger::category(ActivityCategory::USER)
                    ->action(ActivityAction::USER_VIEWED)
                    ->subject(ActivitySubject::USER)
                    ->target(ActivityTarget::OTHER)
                    ->level(ActivityLevel::INFO)
                    ->message("Viewed user profile: {$profileUser->email}")
                    ->meta([
                        'viewed_user_public_id' => $profileUser->public_id,
                        'viewed_user_id' => $profileUser->id,
                    ])
                    ->user($request->user())
                    ->source(ActivitySource::WEB->value)
                    ->log();
            } catch (\Throwable $e) {
                \Log::warning('ActivityLogger failed during admin view logging', ['error' => $e->getMessage()]);
            }
        } else {
            // Optional: Log a debug message if the log was skipped
            \Log::debug('Skipped USER_VIEWED log due to 5-minute debounce period.');
        }

        // Return namespaced data root (Option 2)
        return view('pages.user-management.admin-user-update', ['data' => $data]);
    }

    // ---
    // Session Management Endpoints
    // ---

    /**
     * Revokes a specific active session for a given user.
     * @param User $user The User model instance.
     * @param string $sessionId The unique ID of the session to revoke.
     * @return JsonResponse
     */
    public function revokeSession(User $user, string $sessionId): JsonResponse {
        // Authorization Check
        AccessHelper::authorize('user_view_any');

        // DEV NOTE: Delegating the core logic to the injected SessionManagerService is correct.
        $deletedCount = $this->sessionManager->revokeSession($user, $sessionId);

        if ($deletedCount > 0) {
            // OPTIMIZATION: Use ActivityLogger for consistent logging, instead of SystemActivityLog::create.
            $this->logSessionAction('single', $user, $deletedCount);

            return response()->json([
                'message'       => 'Session successfully terminated.',
                'deleted_count' => $deletedCount
            ], 200);
        }

        return response()->json([
            'message'       => 'Error: Session not found or could not be terminated (session ID did not match the user).',
            'deleted_count' => 0
        ], 404);
    }

    /**
     * Revokes ALL active sessions for a given user using the SessionManagerService.
     * @param User $user The User model instance resolved by the {user} public_id.
     * @return JsonResponse
     */
    public function revokeAllSessions(User $user): JsonResponse {
        // 1. Authorization Check (Must be an admin)
        AccessHelper::authorize('user_view_any');

        // Prevent accidental lockout of the currently authenticated admin
        if (auth()->id() === $user->id) {
            return response()->json([
                'message'       => 'Error: Cannot revoke all sessions for the currently logged-in administrator.',
                'deleted_count' => 0
            ], 403);
        }

        // 2. Perform mass revocation using the injected service
        $deletedCount = $this->sessionManager->revokeAllSessionsForUser($user);

        if ($deletedCount > 0) {
            // OPTIMIZATION: Use ActivityLogger for consistent logging, instead of SystemActivityLog::create.
            $this->logSessionAction('all', $user, $deletedCount);

            return response()->json([
                'message'       => "Successfully terminated {$deletedCount} active session(s) for the user.",
                'deleted_count' => $deletedCount
            ], 200);
        }

        return response()->json([
            'message'       => 'No active sessions found for this user to terminate.',
            'deleted_count' => 0
        ], 200);
    }

    // ---
    // Private Helper Methods (for optimization and cleanliness)
    // ---

    /**
     * DEV NOTE: Private method to centralize permission preparation logic for 'show'.
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    private function preparePermissionsForUser(User $user): \Illuminate\Support\Collection {
        $permanentPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        $tempPerms = AccessHelper::getActiveTemporaryPermissions($user->id);
        $forbiddenKeys = $user->forbids->pluck('permission_name')->toArray();
        $allPermissions = array_unique(array_merge($permanentPermissions, $tempPerms, $forbiddenKeys));

        $directPermissions = $user->permissions->pluck('name')->toArray();
        $permissionsConfig = config('permissions.list', []);

        return collect($allPermissions)->map(function ($perm) use ($directPermissions, $forbiddenKeys, $tempPerms, $permissionsConfig) {
            $config = $permissionsConfig[$perm] ?? [];

            $isForbidden = in_array($perm, $forbiddenKeys, true);
            $isTemporary = in_array($perm, $tempPerms, true);
            $isDirect = in_array($perm, $directPermissions, true);

            // Determine source priority: Forbid > Temporary > Direct > Role
            $source = 'role';
            if ($isDirect)    $source = 'direct';
            if ($isTemporary) $source = 'temporary';
            if ($isForbidden) $source = 'forbid';

            return [
                'name'      => $perm,
                'label'     => $config['label'] ?? $perm,
                'category'  => $config['category'] ?? 'Misc',
                'source'    => $source,
                'forbidden' => $isForbidden,
                'temporary' => $isTemporary,
            ];
        })->values();
    }

    /**
     * DEV NOTE: Private method to format SystemActivityLog entries for the frontend.
     * @param Collection<SystemActivityLog> $rawActivities
     * @return array
     */
    private function formatSystemActivities(Collection $rawActivities): array {
        // Helper to make nice labels from raw enum/string
        $labelize = function (?string $raw): string {
            if (empty($raw)) return 'Unspecified';
            $rawStr = (string) $raw;
            // OPTIMIZATION: Simplified label generation using text helpers if available, or just the current method.
            return ucwords(str_replace(['_', '-'], ' ', strtolower($rawStr)));
        };

        return $rawActivities->map(function (SystemActivityLog $log) use ($labelize) {
            // OPTIMIZATION: Accessing enum values directly if your model casts are configured, otherwise stick to current.
            // Assuming your log model uses $casts for the Enum types, you can access $log->action->value.
            $actionRaw = (string) ($log->action?->value ?? $log->action);
            $levelRaw  = (string) ($log->level?->value ?? $log->level);
            $categoryRaw = (string) ($log->category?->value ?? $log->category);
            $subjectRaw = (string) ($log->subject?->value ?? $log->subject);
            $targetRaw = (string) ($log->target?->value ?? $log->target);

            $absolute = optional($log->created_at)->format('Y-m-d H:i:s');
            $relative = optional($log->created_at)->diffForHumans();

            // Handle meta field decoding
            $meta = $log->meta;
            if (is_string($meta)) {
                $decoded = json_decode($meta, true);
                $meta = json_last_error() === JSON_ERROR_NONE ? $decoded : $meta;
            }

            return [
                'id'       => $log->id,
                'action'   => ['raw' => $actionRaw, 'label' => $labelize($actionRaw)],
                'level'    => ['raw' => $levelRaw, 'label' => $labelize($levelRaw)],
                'category' => ['raw' => $categoryRaw, 'label' => $labelize($categoryRaw)],
                'subject'  => ['raw' => $subjectRaw, 'label' => $labelize($subjectRaw)],
                'target'   => ['raw' => $targetRaw, 'label' => $labelize($targetRaw)],
                'message'  => $log->description ?? $log->message ?? '',
                'meta'     => $meta,
                'ip_address' => $log->ip_address ?? null,
                'user_agent' => $log->user_agent ?? null,
                'created_at' => ['relative' => $relative, 'absolute' => $absolute],
            ];
        })->values()->toArray();
    }

    /**
     * DEV NOTE: Private method to centralize activity logging for session revocation actions.
     * @param string $type 'single' or 'all'
     * @param User $user The target user.
     * @param int $count The number of sessions deleted.
     */
    private function logSessionAction(string $type, User $user, int $count): void {
        $message = $type === 'all'
            ? "Revoked ALL ({$count}) sessions for user: {$user->email}"
            : "Revoked single session for user: {$user->email}";

        $action = $type === 'all' ? ActivityAction::SESSIONS_REVOKED_ALL : ActivityAction::SESSION_REVOKED_SINGLE;

        try {
            ActivityLogger::category(ActivityCategory::SECURITY)
                ->action($action)
                ->subject(ActivitySubject::USER)
                ->target(ActivityTarget::OTHER)
                ->level(ActivityLevel::CRITICAL)
                ->message($message)
                ->meta([
                    'target_user_public_id' => $user->public_id,
                    'target_user_id'        => $user->id,
                    'count'                 => $count,
                ])
                ->user(auth()->user())
                ->source(ActivitySource::WEB->value)
                ->log();
        } catch (\Throwable $e) {
            \Log::error('ActivityLogger failed during session revocation logging', ['error' => $e->getMessage()]);
        }
    }
}
