<?php

namespace App\Http\Controllers;

use App\Helpers\ActiveUserHelper;
use App\Enums\ActivityAction;
use App\Enums\ActivityCategory;
use App\Enums\ActivityLevel;
use App\Enums\ActivitySource;
use App\Enums\ActivitySubject;
use App\Enums\ActivityTarget;
use App\Helpers\AccessHelper;
use App\Helpers\ActivityLogger;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\SystemActivityLog;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller {
    use AuthorizesRequests;

    /**
     * Display a user’s profile page.
     */
    public function show(Request $request, ?User $user = null): View {
        $authUser = $request->user();

        // Determine profile user and eager load relationships
        $profileUser = ($user ?? $authUser)->load(['permissions', 'forbids']);
        $isSelf = $profileUser->id === $authUser->id;

        // Authorization
        if ($isSelf) {
            AccessHelper::authorize('user_view_self');
        } else {
            AccessHelper::authorize('user_view_any');
        }

        //$profileUser->is_active = $profileUser->is_online;
        // Permissions
        $permanentPermissions = $profileUser->getAllPermissions()->pluck('name')->toArray();
        $tempPerms = AccessHelper::getActiveTemporaryPermissions($profileUser->id);
        $forbiddenKeys = $profileUser->forbids->pluck('permission_name')->toArray();
        $allPermissions = array_unique(array_merge($permanentPermissions, $tempPerms, $forbiddenKeys));

        $directPermissions = $profileUser->permissions->pluck('name')->toArray();
        $permissionsConfig = config('permissions.list', []);

        $permissions = collect($allPermissions)->map(function ($perm) use (
            $directPermissions,
            $forbiddenKeys,
            $tempPerms,
            $permissionsConfig
        ) {
            $config = $permissionsConfig[$perm] ?? [];

            $isForbidden = in_array($perm, $forbiddenKeys, true);
            $isTemporary = in_array($perm, $tempPerms, true);
            $isDirect = in_array($perm, $directPermissions, true);

            $source = 'role';
            if ($isDirect)    $source = 'direct';
            if ($isTemporary) $source = 'temporary';
            if ($isForbidden) $source = 'forbid';

            return [
                'name'       => $perm,
                'label'      => $config['label'] ?? $perm,
                'category'   => $config['category'] ?? 'Misc',
                'source'     => $source,
                'forbidden'  => $isForbidden,
                'temporary'  => $isTemporary,
            ];
        })->values();

        // Recent activity (actor == the profile user)
        $activities = SystemActivityLog::query()
            ->where('user_id', $profileUser->id)  // only logs THEY caused
            ->where('target', ActivityTarget::SELF->value) // strict filtering
            ->latest()
            ->limit(6)
            ->get();

        return view('pages.user.profile', compact('profileUser', 'activities'))
            ->with([
                'user'        => $profileUser,
                'roles'       => $profileUser->getRoleNames(),
                'permissions' => $permissions,
            ]);
    }

    /**
     * Display a user’s settings page.
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

        // pass normalized $user to the view
        $user = $profileUser;
        return view('pages.user.settings', compact('user', 'authUser'));
    }

    /**
     * Update the user’s profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse {
        AccessHelper::authorize('user_edit_self');

        $user = $request->user();
        $validated = $request->validated();
        $oldAvatarPath = $user->avatar;
        $newAvatarPath = null;
        $saved = false;

        DB::beginTransaction();
        try {
            $original = $user->getOriginal();
            $user->fill($validated);

            // Reset email verification if email changed
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // Avatar upload
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                $pathPrefix = "avatars/{$user->public_id}";
                $filename = hash('sha256', $avatarFile->getClientOriginalName() . time())
                    . '.' . $avatarFile->getClientOriginalExtension();

                $newAvatarPath = $avatarFile->storeAs($pathPrefix, $filename, 'public');
                $user->avatar = $newAvatarPath;
            }

            $saved = $user->isDirty() ? $user->save() : true;
            if (! $saved) {
                throw new \Exception('Database save failed.');
            }

            DB::commit();

            // Post-commit cleanup (delete old avatar)
            if (
                $newAvatarPath &&
                $oldAvatarPath &&
                $oldAvatarPath !== 'blank.png' &&
                Storage::disk('public')->exists($oldAvatarPath)
            ) {
                Storage::disk('public')->delete($oldAvatarPath);
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newAvatarPath && Storage::disk('public')->exists($newAvatarPath)) {
                Storage::disk('public')->delete($newAvatarPath);
            }

            \Log::error("Profile update failed for user {$user->id}: {$e->getMessage()}");
            $saved = false;
        }

        // Log (only when real changes occurred)
        if ($saved && $user->wasChanged()) {
            try {
                $dirty = $user->getChanges();
                unset($dirty['updated_at']);

                $meta = [
                    'changed_fields' => array_keys($dirty),
                    'old_values'     => collect($dirty)->map(fn($v, $k) => $original[$k] ?? null)->toArray(),
                    'new_values'     => collect($dirty)->toArray(),
                ];

                ActivityLogger::category(ActivityCategory::USER)
                    ->action(ActivityAction::USER_UPDATED)
                    ->subject(ActivitySubject::PROFILE)
                    ->target(ActivityTarget::SELF)
                    ->level(ActivityLevel::INFO)
                    ->message('User updated profile information.')
                    ->meta($meta)
                    ->user($user)
                    ->source(ActivitySource::WEB->value)
                    ->log();
            } catch (\Throwable $logError) {
                \Log::warning("Activity logging failed for user {$user->id}", [
                    'error' => $logError->getMessage(),
                ]);
            }
        }

        // Response
        $statusMessage = $user->wasChanged() ? 'Profile updated successfully!' : 'No changes detected.';
        $finalMessage  = $saved ? $statusMessage : 'Failed to update profile.';
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
     */
    public function destroy(Request $request): RedirectResponse {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        DB::transaction(function () use ($user) {
            if ($user->avatar && $user->avatar !== 'blank.png') {
                Storage::disk('public')->deleteDirectory("avatars/{$user->public_id}");
            }
            $user->delete();
        });

        // session cleanup
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log account deletion
        ActivityLogger::category(ActivityCategory::USER)
            ->action(ActivityAction::USER_DELETED)
            ->subject(ActivitySubject::USER)
            ->target(ActivityTarget::SELF)
            ->level(ActivityLevel::CRITICAL)
            ->message('User account deleted.')
            ->meta(['ip' => $request->ip(), 'user_agent' => $request->userAgent()])
            ->user($user)
            ->source(ActivitySource::WEB->value)
            ->log();

        return Redirect::to('/');
    }
}
