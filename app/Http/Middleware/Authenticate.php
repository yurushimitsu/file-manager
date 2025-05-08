<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        // If the user is not authenticated, redirect to login page
        if (!Auth::check()) {
            return $request->expectsJson() ? null : route('login');
        }

        // Get the currently authenticated user
        $user = auth()->user();

        // Check if the user is a new user and redirect them to the change password page
        if ($user->new_user) {
            return redirect()->route('showChangePass')->with('error', 'Please change your password before proceeding');
        }
    }
}
