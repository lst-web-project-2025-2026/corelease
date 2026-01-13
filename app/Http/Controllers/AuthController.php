<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle application submission.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'profession' => 'required|string|max:255',
            'user_justification' => 'required|string|min:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if application already exists
        if (Application::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'An application with this email already exists.'])->withInput();
        }

        // Check if user already exists
        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'An account with this email already exists.'])->withInput();
        }

        Application::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profession' => $request->profession,
            'user_justification' => $request->user_justification,
            'status' => 'Pending',
        ]);

        return redirect('/')->with('message', 'Your application has been submitted successfully. You can check its status on the home page using your email and password.');
    }

    /**
     * Handle application status check.
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $application = Application::where('email', $request->email)->first();

        if (!$application || !Hash::check($request->password, $application->password)) {
            return back()->withErrors(['status_email' => 'No application found with these credentials.'])->withFragment('#status-checker');
        }

        return back()->with([
            'status_result' => [
                'status' => $application->status,
                'admin_justification' => $application->admin_justification,
                'created_at' => $application->created_at->format('M d, Y'),
            ]
        ])->withFragment('#status-checker');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
