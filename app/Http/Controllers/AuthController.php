<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginInput = trim($request->input('login', $request->input('email', '')));

        $request->validate([
            'email' => ['sometimes', 'nullable', 'string'],
            'login' => ['sometimes', 'nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (empty($loginInput)) {
            return back()->withErrors([
                'email' => 'Please provide your registered email address or mobile number.',
            ])->onlyInput('email');
        }

        $throttleKey = Str::transliterate(Str::lower($loginInput) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many failed sign-in attempts. For your security, access is temporarily locked. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // Support login by either email or mobile phone
        $user = null;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', strtolower($loginInput))->first();
        } else {
            $cleaned = preg_replace('/[^0-9]/', '', $loginInput);
            $last10 = strlen($cleaned) >= 10 ? substr($cleaned, -10) : $cleaned;
            if (!empty($last10)) {
                $user = User::where('phone', 'like', "%{$last10}%")->first();
            }
        }

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            RateLimiter::clear($throttleKey);
            return back()->withErrors([
                'email' => 'Your cadet account has been suspended or deactivated by the administration. Please contact support@nirmaan.edu.in for assistance.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, Administrator!');
        }

        return redirect()->intended(route('student.dashboard'))
            ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'target_exam' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'email.unique' => 'This email address is already registered. Please sign in instead.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Password must be at least 6 characters in length.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'target_exam' => $validated['target_exam'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')
            ->with('success', 'Account created successfully! Welcome to Nirmaan Defence Academy.');
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', strtolower(trim($request->email)))->first();

        if (!$user) {
            // Security best practice: don't reveal if user exists
            return back()->with('status', 'If your email is registered in our portal, password reset instructions have been generated.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        return back()
            ->with('status', 'Password reset instructions have been generated.')
            ->with('simulation_token', $token)
            ->with('simulation_email', $user->email);
    }

    public function showResetPassword($token, Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', strtolower(trim($request->email)))
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors([
                'email' => 'This password reset link is invalid or has expired.',
            ])->onlyInput('email');
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors([
                'email' => 'This password reset link has expired. Please request a new one.',
            ])->onlyInput('email');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User account not found.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')
            ->with('success', 'Your password has been securely reset and you are signed in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been securely signed out.');
    }
}
