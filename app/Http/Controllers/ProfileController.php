<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityTrail;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProfileController extends Controller
{
     /**
     * Show a user's profile.
     *
     * @param Request $request
     * @param User|null $user
     * @return View
     */
     use AuthorizesRequests; // 👈 add this trait

    public function show(Request $request, ?User $user = null): View
    {
        // Current logged-in user
        $currentUser = $request->user();

        // If no user passed, show current user
        $profileUser = $user ?? $currentUser;

        // Debug: see what user object is loaded
        // Remove this after testing
        /*dd([
            'route_param_user' => $user,       // User model resolved via UUID if passed
            'current_user' => $currentUser,
            'profile_user' => $profileUser,
        ]);*/

        // Permission check if viewing someone else
        $this->authorize('view', $profileUser); // ✅ blocks unauthorized users

        $activities = UserActivityTrail::where('user_id', $profileUser->id)
        ->orderByDesc('created_at')
        ->limit(6)
        ->get();    

        return view('pages.user.profile', [
            'user' => $profileUser,
            'activities' => $activities,
        ]);
    }

    /**
     * Optional: set the route key to UUID so Laravel resolves {user} automatically.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
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
