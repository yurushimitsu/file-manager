<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login() {
        if(auth()->check() && auth()->user()->name) {
            return redirect()->route('dashboard');
        } else {
            return view('login');
        }
    }

    public function loginEmail(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember') ? true : false;

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $remember)) {
            // If login is successful, redirect to the intended page (or dashboard)
            return redirect()->intended('/dashboard');
        } else {
            // If login fails, redirect back with an error message
            return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showChangePass(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('login');
        } else {
            return view('dashboard.change_password');
        }
    }

    public function postChangePass(Request $request) {
        $oldPassword = $request->input('old-password');
        $newPassword = $request->input('new-password');
        $confirmPassword = $request->input('confirm-password');

        $user = User::where('email', auth()->user()->email)->first();

        // If new user only confirm the new password
        if (auth()->user()->new_user) {
            if ($newPassword === $confirmPassword) {
                $user->password = Hash::make($confirmPassword);
                $user->new_user = false;
                $user->save();

                return response()->json(['success' => true, 'message' => 'Password Changed Successfully']);  
            } else {
                return response()->json(['success' => false, 'message' => "New Password Doesn't Match"]);
            }
        } else {
            if ($user && Hash::check($oldPassword, $user->password)) {
                if ($newPassword === $confirmPassword) {
                    $user->password = Hash::make($confirmPassword);
                    $user->new_user = false;
                    $user->save();
    
                    return response()->json(['success' => true, 'message' => 'Password Changed Successfully']);  
                } else {
                    return response()->json(['success' => false, 'message' => "New Password Doesn't Match"]);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Wrong Password']);
            }
        }
    }
}