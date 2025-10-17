<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Helpers\AccessHelper;
use App\Helpers\ActiveUserHelper; // OPTIMIZATION: Use the dedicated helper for active users
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache; // DEV NOTE: Only needed if interacting with Cache directly

class DashboardController extends Controller {
    /**
     * Show the admin dashboard for user management metrics.
     *
     * DEV NOTES:
     * - Requires 'user_view_any' permission via AccessHelper.
     * - OPTIMIZATION: Uses ActiveUserHelper to cleanly fetch online users/counts.
     * - Uses a single raw DB query for fast user status counts.
     */
    public function index() {

        // 1. Authorization Check
        AccessHelper::authorize('user_view_any');

        // 2. Online Users (OPTIMIZATION: Use the dedicated helper)
        $onlineUsers = ActiveUserHelper::getActiveUsers();
        $onlineCount = $onlineUsers->count(); // This will be 1!
        //dd($onlineCount);

        // 3. User Status Counts (Single Query - Highly Efficient)
        $userStatusCounts = User::selectRaw("
            COUNT(*) as totalUsers,
            SUM(status = 'active') as activeUsers,
            SUM(status = 'blocked') as blockedUsers,
            SUM(status = 'suspended') as suspendedUsers,
            SUM(status = 'disabled') as disabledUsers,
            SUM(email_verified_at IS NOT NULL) as verifiedUsers,
            SUM(email_verified_at IS NULL) as unverifiedUsers
        ")->first();


        // 5. Role Counts (OPTIMIZATION: Single query for common roles using Spatie)
        $rolesToCount = ['Admin', 'Editor', 'User'];
        $roleCounts = Role::whereIn('name', $rolesToCount)
            ->withCount('users')
            ->get()
            ->keyBy('name')
            ->map->users_count;

        // Count users who are assigned *any* role (for context)
        $viaRbacRole = User::role($rolesToCount)->count();
        // Count users with no role assigned
        $directlyAssigned = User::whereDoesntHave('roles')->count();
        $permOverrides = 0; // Placeholder

        return view('welcome', [
            // Status Metrics
            'totalUsers'       => $userStatusCounts->totalUsers,
            'loggedInNow' => $onlineCount, // Renamed to clearly indicate a count
            'activeUsers'      => $userStatusCounts->activeUsers,
            'blockedUsers'     => $userStatusCounts->blockedUsers,
            'suspendedUsers'   => $userStatusCounts->suspendedUsers,
            'disabledUsers'    => $userStatusCounts->disabledUsers,
            'verifiedUsers'    => $userStatusCounts->verifiedUsers,
            'unverifiedUsers'  => $userStatusCounts->unverifiedUsers,

            // Role Metrics
            'administrators'   => $roleCounts->get('Admin', 0),
            'editors'          => $roleCounts->get('Editor', 0),
            'standardUsers'    => $roleCounts->get('User', 0),
            'viaRbacRole'      => $viaRbacRole,
            'directlyAssigned' => $directlyAssigned,
            'permOverrides'    => $permOverrides,


        ]);
    }

    // -------------------------------------------------------------------------
    // User List for Datatable
    // -------------------------------------------------------------------------

    /**
     * Handles the AJAX request for the user list datatable.
     *
     * DEV NOTES:
     * - Robust input sanitation and validation for security.
     * - Uses Eloquent eager loading (`->with()`) and pagination for performance.
     * - The `is_online` accessor is automatically available on User models via the new setup.
     */
    public function list(Request $request) {
        // 1. Authorization and Input Validation
        AccessHelper::authorize('user_view_any');

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
                // DEV NOTE: Use try-catch to safely parse dates if needed, but Carbon::parse is robust.
                $dateRange = [
                    'start' => Carbon::parse($parts[0])->startOfDay(),
                    'end' => Carbon::parse($parts[1])->endOfDay()
                ];
            }
        }

        // 3. Build Query
        $query = User::with(['roles:id,name'])
            ->select('id', 'public_id', 'avatar', 'name', 'lastname', 'email', 'created_at', 'two_factor_secret', 'status');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // OPTIMIZATION: Use CONCAT for full name search for better DB performance/index utilization
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
            // OPTIMIZATION: Maintain sort consistency for full_name
            $query->orderBy('name', $sortOrder)->orderBy('lastname', $sortOrder);
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        // 5. Paginate and Format
        $usersPaginator = $query->paginate($pageSize, ['*'], 'page', $page);

        $formattedUsers = $usersPaginator->getCollection()->map(function ($user) {
            return [
                'id'          => $user->public_id ?? '—',
                // OPTIMIZATION: Use accessor for avatar URL for cleaner output
                'avatar'      => $user->avatar_url,
                'full_name'   => trim(($user->name ?? '') . ' ' . ($user->lastname ?? '')) ?: 'Unnamed User',
                'email'       => $user->email ?? '—',
                'role_name'   => $user->roles->pluck('name')->first() ?? 'Standard User', // DEV NOTE: Use 'Standard User' instead of '—'
                'created_at'  => optional($user->created_at)->format('Y-m-d H:i:s'),
                'twfa_stat'   => !empty($user->two_factor_secret) ? 'Active' : 'Disabled',
                'status'      => $user->status ?? 'unknown',
                'statusLabel' => ucfirst($user->status ?? 'Unknown'),
                // Use the is_online ACCESSOR
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
}
