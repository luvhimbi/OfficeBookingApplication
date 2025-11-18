<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PasswordResetService;

class PasswordResetController extends Controller
{
    protected $resetService;

    public function __construct(PasswordResetService $resetService)
    {
        $this->resetService = $resetService;
    }

    // Show form to input email
    public function showRequestForm()
    {
        return view('auth.email');
    }

    // Handle request and send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);


        $user = User::where('email', $request->email)->first();
        if ($user) {
            $this->resetService->sendResetLink($user->email);
        }

        // Always return the same response to prevent account enumeration
        return back()->with('success', 'If an account exists with this email, a password reset link has been sent.');
    }


    // Show reset password form
    public function resetForm($token)
    {
        return view('auth.reset', compact('token'));
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $success = $this->resetService->resetPassword($request->token, $request->password);

        if (!$success) {
            return redirect()->route('password.request')->with('error', 'Invalid or expired token.');
        }

        return redirect()->route('login')->with('success', 'Password reset successfully.');
    }


}
