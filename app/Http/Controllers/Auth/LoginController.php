<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        // Validate login input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Login success: redirect to dashboard or home
            return redirect()->intended('dashboard');
        }

        // Login failed: redirect back with error
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    // Optional: Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
