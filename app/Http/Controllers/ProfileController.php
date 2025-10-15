<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityTrail;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // Use Storage facade directly
use Illuminate\Support\Facades\DB; // Use DB facade for transactions
use Illuminate\View\View;
use App\Helpers\AccessHelper;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller {
    use AuthorizesRequests;

    /**
     * Display a user’s profile page (Already optimized).
     */
    public function show(Request $request, ?User $user = null): View {
        $authUser = $request->user();

        // 1. Determine Profile User and Eager Load Relationships
        // Eager load 'permissions' and 'forbids' to prevent N+1 issues later.
        $profileUser = ($user ?? $authUser)->load(['permissions', 'forbids']);

        $isSelf = $profileUser->id === $authUser->id;

        // 2. Authorization Check (No change needed)
        if ($isSelf) {
            AccessHelper::authorize('user_view_self');
        } else {
            AccessHelper::authorize('user_view_any');
        }

        // 3. Permission Data Retrieval
        $permanentPermissions = $profileUser->getAllPermissions()->pluck('name')->toArray();

        // AccessHelper::getActiveTemporaryPermissions is assumed to be memoized
        $tempPerms = AccessHelper::getActiveTemporaryPermissions($profileUser->id);
        $forbiddenKeys = $profileUser->forbids->pluck('permission_name')->toArray();

        // Merge and get unique keys
        $allPermissions = array_unique(array_merge($permanentPermissions, $tempPerms, $forbiddenKeys));

        // Prepare for faster lookup during map
        $directPermissions = $profileUser->permissions->pluck('name')->toArray();
        $permissionsConfig = config('permissions.list', []);

        // 4. Permission Details Mapping
        $permissions = collect($allPermissions)->map(function ($perm) use (
            $directPermissions,
            $forbiddenKeys,
            $tempPerms,
            $permissionsConfig
        ) {
            $config = $permissionsConfig[$perm] ?? [];

            // These checks are now fast O(1) array lookups if forbiddenKeys/tempPerms
            // were converted to hash maps in AccessHelper::describePermissions,
            // but for simple array operations here, in_array is sufficient.
            $isForbidden = in_array($perm, $forbiddenKeys);
            $isTemporary = in_array($perm, $tempPerms);
            $isDirect = in_array($perm, $directPermissions);

            $source = 'role';
            if ($isDirect) {
                $source = 'direct';
            }
            if ($isTemporary) {
                $source = 'temporary';
            }
            if ($isForbidden) {
                $source = 'forbid';
            }

            return [
                'name'        => $perm,
                'label'       => $config['label'] ?? $perm,
                'category'    => $config['category'] ?? 'Misc',
                'source'      => $source,
                'forbidden'   => $isForbidden,
                'temporary'   => $isTemporary,
            ];
        })->values();

        // 5. Recent activity
        $activities = UserActivityTrail::query()
            ->where('user_id', $profileUser->id)
            ->latest()
            ->limit(6)
            ->get();

        // 6. Pass to view
        return view('pages.user.profile', compact('profileUser', 'activities'))
            ->with([
                'user' => $profileUser,
                'roles' => $profileUser->getRoleNames(),
                'permissions' => $permissions,
            ]);
    }


    /**
     * Display a user’s settings page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\View\View
     */
    public function settings(Request $request, ?User $user = null): View {
        // Optimization: Consolidate user logic and authorization check (similar to show)
        $authUser = $request->user();
        $profileUser = $user ?? $authUser;

        $isSelf = $profileUser->id === $authUser->id;

        if ($isSelf) {
            AccessHelper::authorize('user_view_self');
        } else {
            AccessHelper::authorize('user_view_any');
        }

        // OPTIMIZATION: Assign $profileUser to $user to use pure compact() style.
        $user = $profileUser;
        return view('pages.user.settings', compact('user', 'authUser'));
    }

    /**
     * Update the user’s profile information (Optimized for transactions and file handling).
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */

    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse {
        AccessHelper::authorize('user_edit_self');

        $user = $request->user();
        $validated = $request->validated();
        $oldAvatarPath = $user->avatar;
        $newAvatarPath = null;
        $saved = false;
        $errorMessage = 'Failed to update profile.';

        // 🧱 Start DB transaction
        DB::beginTransaction();

        try {
            $original = $user->getOriginal();
            $user->fill($validated);

            // Email reset if changed
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // Avatar handling
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                $pathPrefix = "avatars/{$user->public_id}";
                $filename = hash('sha256', $avatarFile->getClientOriginalName() . time()) . '.' .
                    $avatarFile->getClientOriginalExtension();

                $newAvatarPath = $avatarFile->storeAs($pathPrefix, $filename, 'public');
                $user->avatar = $newAvatarPath;
            }

            $saved = $user->isDirty() ? $user->save() : true;

            if (! $saved) {
                throw new \Exception('Database save failed.');
            }

            DB::commit();

            // 🧹 Post-commit cleanup (safe, outside transaction)
            if (
                $newAvatarPath &&
                $oldAvatarPath &&
                $oldAvatarPath !== 'blank.png' &&
                \Storage::disk('public')->exists($oldAvatarPath)
            ) {
                \Storage::disk('public')->delete($oldAvatarPath);
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($newAvatarPath) && \Storage::disk('public')->exists($newAvatarPath)) {
                \Storage::disk('public')->delete($newAvatarPath);
            }

            \Log::error("Profile update failed for user {$user->id}: " . $e->getMessage());
            $saved = false;
        }

        /**
         * --------------------------------
         * 🧾 Activity Trail (Outside Tx)
         * --------------------------------
         * DEV NOTE: Logging after commit ensures the logger runs
         * on a clean, non-transactional connection. This prevents
         * it from being rolled back or skipped silently.
         */
        if ($saved) {
            try {
                // Capture which fields were changed BEFORE save clears them
                $dirty = $user->getChanges(); // after save(), this shows saved changes

                unset($dirty['updated_at']); // ignore system timestamps

                $meta = [
                    'changed_fields' => array_keys($dirty),
                    'old_values'     => collect($dirty)->map(fn($v, $k) => $original[$k] ?? null)->toArray(),
                    'new_values'     => collect($dirty)->toArray(),
                    'ip'             => $request->ip(),
                    'user_agent'     => $request->userAgent(),
                ];

                logUserActivity('profile_updated', 'User updated profile information.', $meta, $user);
            } catch (\Throwable $logError) {
                $userId = $user instanceof \App\Models\User
                    ? $user->id
                    : (is_array($user) ? json_encode($user) : (string) $user);

                \Log::warning("Activity logging failed for user ID {$userId}", [
                    'error' => $logError->getMessage(),
                    'meta'  => $meta ?? [], // ✅ prevents undefined variable
                ]);
            }
        }

        // 🎯 Prepare response
        $statusMessage = $user->wasChanged() ? 'Profile updated successfully!' : 'No changes detected.';
        $finalMessage  = $saved ? $statusMessage : $errorMessage;
        $toastType     = $saved ? ($user->wasChanged() ? 'success' : 'info') : 'error';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message'    => $finalMessage,
                'status'     => $saved ? 'profile-updated' : 'update-failed',
                'avatar_url' => $user->getAvatarUrlAttribute(),
                'type'       => $toastType,
            ], $saved ? 200 : 500);
        }

        return redirect()
            ->route('profile_settings.show')
            ->with('status', $saved ? 'profile-updated' : 'update-failed');
    }


    /**
     * Delete the user’s account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse {
        // No major optimization needed here, but the file cleanup could be added (see notes below)
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Optional: Run deletion in a transaction for safety
        DB::transaction(function () use ($user) {
            // OPTIMIZATION IDEA: Add logic here to delete all user-related data (e.g., activity trail, avatars)
            // Example:
            // if ($user->avatar && $user->avatar !== 'blank.png') {
            //     Storage::disk('public')->deleteDirectory("avatars/{$user->public_id}");
            // }
            $user->delete();
        });


        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function list(Request $request) {
        // 1. Authorization check
        AccessHelper::authorize('user_view_any');

        // 2. Sanitize & Validate Inputs
        $search      = trim(strip_tags($request->input('search', '')));
        $sortField   = trim(strip_tags($request->input('sortField', 'created_at')));
        $sortOrder   = strtolower(trim(strip_tags($request->input('sortOrder', 'desc'))));
        $pageSize    = (int) $request->input('size', 10);
        $page        = (int) $request->input('page', 1);

        // ** NEW PAYLOAD PARSING **
        $statusFilter = trim(strip_tags($request->input('status', ''))); // 'active'
        $colSortParam = trim(strip_tags($request->input('col_sort', ''))); // '2025-10-17 to 2025-11-18'

        // 3. Normalize values & fallbacks
        // --- UPDATED SORT FIELDS ---
        // The valid fields must be actual database columns OR fields you handle with special sorting logic (like 'full_name')
        $validSortFields = ['id', 'name', 'lastname', 'email', 'created_at', 'full_name', 'status'];
        $validSortOrders = ['asc', 'desc'];

        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array($sortOrder, $validSortOrders)) {
            $sortOrder = 'desc';
        }

        if ($pageSize <= 0 || $pageSize > 100) {
            $pageSize = 10;
        }

        if ($page <= 0) {
            $page = 1;
        }

        // Parse the date range: 'col_sort=2025-10-17+to+2025-11-18'
        $dateRange = [];
        if (!empty($colSortParam)) {
            $parts = array_map('trim', explode(' to ', $colSortParam));
            if (count($parts) === 2) {
                // Use Carbon for safer date parsing if available, otherwise native date functions are fine.
                $startDate = date('Y-m-d', strtotime($parts[0]));
                $endDate = date('Y-m-d', strtotime($parts[1]));
                if ($startDate && $endDate) {
                    $dateRange = [
                        'start' => $startDate . ' 00:00:00',
                        'end' => $endDate . ' 23:59:59'
                    ];
                }
            }
        }

        // 4. Initial Query Setup (optimized)
        $query = User::with(['roles:id,name'])
            ->select('id', 'public_id', 'avatar', 'name', 'lastname', 'email', 'created_at', 'two_factor_secret', 'status'); // Ensure 'status' is selected

        // 5. Apply Search Filter (if provided)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ** APPLY NEW FILTERS **

        // Apply Status Filter ('status=active')
        if (!empty($statusFilter)) {
            // Assuming 'status' is a column on the User model
            $query->where('status', $statusFilter);
        }

        // Apply Date Range Filter ('col_sort=2025-10-17+to+2025-11-18')
        if (!empty($dateRange)) {
            // Assuming the date range filters the user's 'created_at' date
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }


        // 6. Apply Sorting
        // --- CUSTOM SORTING LOGIC ---
        if ($sortField === 'full_name') {
            // 1. Sort primarily by 'name' (FirstName)
            $query->orderBy('name', $sortOrder);

            // 2. Use a secondary sort for 'lastname' to break ties on 'name'.
            //    We use orderByRaw to ensure NULL values are treated predictably
            //    (e.g., placing users with no last name at the end of the group).

            if (strtolower($sortOrder) === 'asc') {
                // ASC: Sort non-null last names A-Z, then put NULLs (no last name) at the end.
                $query->orderByRaw('COALESCE(lastname), lastname ASC');
            } else {
                // DESC: Sort non-null last names Z-A, then put NULLs (no last name) at the end.
                $query->orderByRaw('COALESCE(lastname) ASC, lastname DESC');
                // Note: 'ISNULL(lastname) ASC' ensures nulls are always last in DESC sort.
            }
        } else {
            // Apply standard sorting for other database columns
            $query->orderBy($sortField, $sortOrder);
        }

        // 7. Paginate and Execute Query
        $usersPaginator = $query->paginate($pageSize, ['*'], 'page', $page);

        // 8. Format Data Safely
        $formattedUsers = $usersPaginator->getCollection()->map(function ($user) {
            $status = $user->status ?? 'unknown';
            return [
                'id'            => $user->public_id ?? '—',
                'avatar'        => $user->avatar ?? '/images/default-avatar.png',
                'full_name'     => trim(($user->name ?? '') . ' ' . ($user->lastname ?? '')) ?: 'Unnamed User',
                'email'         => $user->email ?? '—',
                'role_name'     => $user->roles->pluck('name')->first() ?? '—',
                'created_at'    => optional($user->created_at)->format('Y-m-d H:i:s') ?? '—',
                'twfa_stat'     => !empty($user->two_factor_secret) ? 'Active' : 'Disabled',
                'status'        => $status,
                'statusLabel'   => ucfirst($status),
            ];
        });

        // 9. Structure Response for KTDataTable
        $response = [
            'data'          => $formattedUsers,
            'page'          => $usersPaginator->currentPage(),
            'pageSize'      => $usersPaginator->perPage(),
            'totalPages'    => $usersPaginator->lastPage(),
            'totalCount'    => $usersPaginator->total(),
        ];

        return response()->json($response);
    }
}
