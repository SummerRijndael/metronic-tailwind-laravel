<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller {
    /**
     * Update the user's password.
     */
    public function update(Request $request): mixed {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        // Prepare toast response
        $toast = [
            'message' => 'Password updated successfully.',
            'type' => 'success', // could be 'success', 'destructive', 'warning'
        ];

        // Check if the request expects JSON (AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($toast, 200);
        }

        // Otherwise normal redirect back
        return back()->with('status', 'password-updated');
    }
}
