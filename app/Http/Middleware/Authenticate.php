<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
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

        // If the user is authenticated and is not a new user, proceed with the request
        return $next($request);
    }
}
