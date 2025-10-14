<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomResetPasswordViewResponse implements ResetPasswordViewResponse {
    public function toResponse($request) {
        $email = $request->query('email');
        $token = $request->route('token');

        // Check if token exists in password_resets table
        $exists = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->exists();

        if (! $exists) {
            throw new NotFoundHttpException('Invalid or expired reset link.');
        }

        return view('auth.reset-password', [
            'request' => $request,
            'email' => $email,
            'token' => $token,
        ]);
    }
}
