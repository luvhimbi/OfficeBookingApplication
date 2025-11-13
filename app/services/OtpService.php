<?php

namespace App\Services;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpService
{
    protected $emailService;
    protected $otpExpiryMinutes = 10; // default expiry time

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Generate an OTP code for a user.
     */
    public function generateOtp(User $user, $length = 6)
    {
        $code = rand(pow(10, $length - 1), pow(10, $length) - 1);

        // Store OTP in cache with expiry
        Cache::put($this->getCacheKey($user), $code, now()->addMinutes($this->otpExpiryMinutes));

        return $code;
    }

    /**
     * Send OTP via email
     */
    public function sendOtp(User $user)
    {
        $code = $this->generateOtp($user);
        $this->emailService->sendTwoFactorCode($user, $code);
        return $code;
    }

    /**
     * Validate an OTP code
     */
    public function validateOtp(User $user, $code)
    {
        $cachedCode = Cache::get($this->getCacheKey($user));

        if ($cachedCode && $cachedCode == $code) {
            // OTP is valid, remove it from cache
            Cache::forget($this->getCacheKey($user));
            return true;
        }

        return false;
    }

    /**
     * Helper to generate a unique cache key per user
     */
    protected function getCacheKey(User $user)
    {
        return "otp_user_{$user->id}";
    }

    public function updateTwoFactorStatus(?\Illuminate\Contracts\Auth\Authenticatable $user, bool $enabled)
    {
    }
}
