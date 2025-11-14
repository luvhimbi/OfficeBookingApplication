<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If user is logged in and has 2FA enabled
        if ($user && $user->two_factor_enabled) {
            // Check if session has 2FA verified flag
            if (!$request->session()->get('two_factor_verified', false)) {
                // Redirect to 2FA verification page
                return redirect()->route('otp.verify.form')
                    ->with('warning', 'Please verify your two-factor authentication before continuing.');
            }
        }

        return $next($request);
    }
}
