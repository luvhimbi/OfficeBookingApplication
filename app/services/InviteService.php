<?php

namespace App\Services;

use App\Mail\UserInviteMail;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteService
{
    /**
     * Create a new invite and send the email.
     */
    public function createInvite(array $data): Invite
    {
        $token = Str::random(40);

        $invite = Invite::create([
            'email' => $data['email'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'role' => $data['role'],
            'token' => $token
        ]);

        // Send email
        Mail::to($data['email'])->send(new UserInviteMail($token));

        return $invite;
    }

    /**
     * Accept an invite by token.
     */
    public function getInviteByToken(string $token): ?Invite
    {
        return Invite::where('token', $token)->first();
    }

    /**
     * Complete invite and create user.
     */
    public function completeInvite(Invite $invite, string $password): User
    {
        $user = User::create([
            'firstname' => $invite->firstname,
            'lastname' => $invite->lastname,
            'email' => $invite->email,
            'role' => $invite->role,
            'password' => Hash::make($password),
        ]);

        $invite->update(['used' => true]);

        Auth::login($user);

        return $user;
    }

    /**
     * List recent invites.
     */
    public function recentInvites(int $limit = 8)
    {
        return Invite::latest()->take($limit)->get();
    }

    /**
     * Paginate all invites.
     */
    public function paginateInvites(int $perPage = 10)
    {
        return Invite::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Delete an invite.
     */
    public function deleteInvite(Invite $invite): void
    {
        $invite->delete();
    }
}
