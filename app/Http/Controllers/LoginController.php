<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}