<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\OtpService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    protected $otpService;

    public function __construct(AuthService $authService, OtpService $otpService)
    {
        $this->authService = $authService;
        $this->otpService = $otpService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->authService->login($credentials);

        if ($user) {
            if ($user->two_factor_enabled) {
                $this->otpService->sendOtp($user);
                return redirect()->route('otp.verify.form')->with('email', $user->email);
            }

            // Role-based redirect
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!'),
                'employee' => redirect()->route('employee.dashboard')->with('success', 'Welcome back!'),
                default => redirect()->route('home')
            };
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $this->authService->updateProfile($user, $validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function toggle2FA()
    {
        $user = auth()->user();
        $enabled = $this->authService->toggleTwoFactor($user);

        return back()->with('success', $enabled
            ? 'Two-Factor Authentication has been enabled.'
            : 'Two-Factor Authentication has been disabled.');
    }
}
