<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;

class UpdatePasswordController extends Controller {
    /**
     * Update the authenticated user's password directly and return a custom toast/JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function update(Request $request): mixed {
        // 1️⃣ Validation
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        // 2️⃣ Database Update
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        // 3️⃣ Activity Logging
        $meta = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'ki-filled ki-lock',
            'color' => 'bg-info/60',
        ];

        ActivityLogger::category(ActivityCategory::AUTH)
            ->action(ActivityAction::PASSWORD_CHANGED)
            ->message("User {$user->name} updated their password.")
            ->user($user)
            ->meta($meta)
            ->source('system')
            ->log();

        // 4️⃣ Prepare Custom Response
        $toast = [
            'message' => 'Password updated successfully.',
            'type' => 'success',
        ];

        // Return JSON for AJAX/API requests
        if ($request->wantsJson()) {
            return response()->json($toast, 200);
        }

        // Redirect back for standard form submissions
        return back()->with('status', 'password-updated');
    }
}
