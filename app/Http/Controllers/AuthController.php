<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show the registration / application form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle login submission.
     */
    public function login(Request $request)
    {
        // Placeholder for auth logic
        return back()->with('message', 'Login logic not implemented yet.');
    }

    /**
     * Handle application submission.
     */
    public function register(Request $request)
    {
        // Placeholder for application logic
        return redirect('/')->with('message', 'Application submitted (placeholder).');
    }
}
