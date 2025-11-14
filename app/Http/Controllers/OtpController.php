<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show the OTP verification form
     */
    public function showVerifyForm()
    {
        return view('auth.verify')
            ;
    }

    /**
     * Verify the submitted OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($this->otpService->validateOtp($user, $request->input('two_factor_code'))) {
            // Mark 2FA as verified in session (optional)
            session(['two_factor_verified' => true]);

            // Role-based redirection
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            } elseif ($user->role === 'employee') {
                return redirect()->route('employee.dashboard')->with('success', 'Welcome back!');
            }
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
    }

    /**
     * Resend a new OTP
     */
    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        $code = $this->otpService->sendOtp($user);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}
