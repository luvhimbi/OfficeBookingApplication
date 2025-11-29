<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\DB;

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
        // Look for the token in the password_resets table
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

        // Case 1: Token does not exist (invalid or already used)
        if (!$reset) {
            return view('auth.token_invalid_or_expired'); // create this view
        }

        // Case 2: Token exists but has expired (default 60 minutes)
        $tokenCreated = Carbon::parse($reset->created_at);
        if (Carbon::now()->diffInMinutes($tokenCreated) > 60) {
            return view('auth.token_invalid_or_expired');
        }

        // Token is valid, show reset form
        return view('auth.reset', compact('token', 'reset'));
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
