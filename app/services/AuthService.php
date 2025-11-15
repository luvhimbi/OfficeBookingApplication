<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt to log in a user.
     */
    public function login(array $credentials): ?User
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session()->regenerate();
            return $user;
        }
        return null;
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Update a user's profile.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => !empty($data['password']) ? Hash::make($data['password']) : $user->password,
        ]);

        return $user;
    }

    /**
     * Toggle 2FA for a user.
     */
    public function toggleTwoFactor(User $user): bool
    {
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();
        return $user->two_factor_enabled;
    }
}
