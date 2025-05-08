<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {
        try {
            // Get the user information from Google
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
        }

        // Check if the user already exists in the database
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // Log the user in if they already exist
            Auth::login($existingUser);

            if ($existingUser->new_user) {
                return redirect()->route('showChangePass')->with('error', 'Please change your password before proceeding');
            }
        } else {
            // Otherwise, create a new user and log them in
            $newUser = User::updateOrCreate([
                'email' => $user->email
            ], [
                'name' => $user->name,
                'avatar' => $user->getAvatar(),
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
                'new_user' => true,
                'storage_gb' => 1,
            ]);
            Auth::login($newUser);
            Storage::makeDirectory('public/user/' . $newUser->id);

            return redirect()->route('showChangePass')->with('error', 'Please change your password before proceeding');
        }

        // Redirect the user to the dashboard or any other secure page
        return redirect('/dashboard');
    }
}
