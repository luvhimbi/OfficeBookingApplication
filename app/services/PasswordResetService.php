<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\EmailService;

class PasswordResetService
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Generate a password reset token and send email.
     */
    public function sendResetLink(string $email): void
    {
        $user = User::where('email', $email)->firstOrFail();
        $token = Str::random(64);

        // Store token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetLink = url("/password/reset/{$token}");
        $this->emailService->sendPasswordReset($user, $resetLink);
    }

    /**
     * Validate the token and return the user.
     */
    public function validateToken(string $token): ?User
    {
        $record = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$record) {
            return null;
        }

        return User::where('email', $record->email)->first();
    }

    /**
     * Reset the user's password and delete the token.
     */
    public function resetPassword(string $token, string $password): bool
    {
        $user = $this->validateToken($token);

        if (!$user) {
            return false;
        }

        $user->password = Hash::make($password);
        $user->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return true;
    }
}
