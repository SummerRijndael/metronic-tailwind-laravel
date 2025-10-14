<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordController extends Controller {
    /**
     * Update the authenticated user's password directly and return a custom toast/JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function update(Request $request): mixed {
        // 1. Validation
        // This validates current_password against the database and checks new password rules.
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // 2. Database Update
        // Update the password and add your custom 'password_changed_at' timestamp.
        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(), // Custom field retained
        ]);

        // 3. Prepare the Custom Response (Toast/JSON)
        $toast = [
            'message' => 'Password updated successfully.',
            'type' => 'success',
        ];

        // 4. Handle Response

        // Return JSON for AJAX/API requests.
        if ($request->wantsJson()) {
            return response()->json($toast, 200);
        }

        // Redirect back for standard form submissions.
        return back()->with('status', 'password-updated');
    }
}
