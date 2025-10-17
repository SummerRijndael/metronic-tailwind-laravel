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
}
