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
use Illuminate\View\View;
use App\Helpers\AccessHelper;

class ProfileController extends Controller {
    use AuthorizesRequests;

    /**
     * Display a user’s profile page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\View\View
     */
    // Optimized Version
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

        // 3. Permission Data Retrieval (Optimized)
        // getAllPermissions() usually handles roles, so let's rely on it.
        $permanentPermissions = $profileUser->getAllPermissions()->pluck('name')->toArray();

        $tempPerms = AccessHelper::getActiveTemporaryPermissions($profileUser->id);
        $forbiddenKeys = $profileUser->forbids->pluck('permission_name')->toArray(); // Use eager loaded data

        // Merge and get unique keys
        $allPermissions = array_unique(array_merge($permanentPermissions, $tempPerms, $forbiddenKeys));

        // Prepare for faster lookup during map
        $directPermissions = $profileUser->permissions->pluck('name')->toArray(); // Use eager loaded data
        $permissionsConfig = config('permissions.list', []); // Load config once

        // 4. Permission Details Mapping (Optimized for Lookups)
        $permissions = collect($allPermissions)->map(function ($perm) use (
            $directPermissions,
            $forbiddenKeys,
            $tempPerms,
            $permissionsConfig
        ) {
            $config = $permissionsConfig[$perm] ?? [];

            $isForbidden = in_array($perm, $forbiddenKeys);
            $isTemporary = in_array($perm, $tempPerms);
            $isDirect = in_array($perm, $directPermissions); // Faster lookup than 'contains'

            // Detect source (Priority: Forbid > Temporary > Direct > Role)
            $source = 'role'; // default
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

        // 5. Recent activity (No change needed)
        $activities = UserActivityTrail::query()
            ->where('user_id', $profileUser->id)
            ->latest()
            ->limit(6)
            ->get();

        // 6. Pass to view (No change needed)
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
        $authUser = $request->user();
        $profileUser = $user ?? $authUser;

        $isSelf = $profileUser->id === $authUser->id;

        if ($isSelf) {
            AccessHelper::authorize('user_view_self');
        } else {
            AccessHelper::authorize('user_view_any');
        }

        return view('pages.user.settings', compact('profileUser'))
            ->with('user', $profileUser);
    }

    /**
     * Update the user’s profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse {
        AccessHelper::authorize('user_edit_self');

        $user = $request->user();
        $validated = $request->validated();

        $user->fill($validated);

        // Reset email verification if changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            // Delete old avatar if exists
            if (
                $user->avatar &&
                $user->avatar !== 'assets/media/avatars/blank.png' &&
                \Storage::disk('public')->exists($user->avatar)
            ) {
                \Storage::disk('public')->delete($user->avatar);
            }

            // Generate hashed filename
            $extension = $avatar->getClientOriginalExtension();
            $filename = hash('sha256', $user->public_id . now() . $avatar->getClientOriginalName()) . '.' . $extension;
            $path = $avatar->storeAs("avatars/{$user->public_id}", $filename, 'public');
            $user->avatar = $path;
        }

        $saved = $user->isDirty() ? $user->save() : true;

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message'    => $saved
                    ? ($user->wasChanged() ? 'Profile updated successfully!' : 'No changes detected.')
                    : 'Failed to update profile.',
                'status'     => $saved ? 'profile-updated' : 'update-failed',
                'avatar_url' => $user->avatar
                    ? asset("storage/{$user->avatar}")
                    : asset('assets/media/avatars/blank.png'),
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
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
