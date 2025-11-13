<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;

class PasswordResetController extends Controller
{
    // Show the form where user inputs email
    public function showRequestForm()
    {
        return view('auth.email');
    }

    // Handle form submission
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with this email.']);
        }

        // Generate a token
        $token = Str::random(60);

        // Save token in DB
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Send email
        $resetLink = url("/password/reset/{$token}?email={$user->email}");

        Mail::send('emails.password_reset', ['resetLink' => $resetLink, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Password Reset Request');
        });

        return back()->with('success', 'We have emailed your password reset link!');
    }
}
