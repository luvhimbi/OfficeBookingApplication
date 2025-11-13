<?php

namespace App\Services;

use App\Mail\GenericMail;
use App\Models\Booking;
use App\Mail\BookingNotification;
use Illuminate\Support\Facades\Mail;

class EmailService
{

    public function sendEmail(string $to, string $subject, string $body)
    {
        Mail::send([], [], function ($message) use ($to, $subject, $body) {
            $message->to($to)
                ->subject($subject)
                ->setBody($body, 'text/html');
        });
    }
    /**
     * Send booking confirmation email
     */
    public function sendBookingConfirmation(Booking $booking)
    {
        Mail::to($booking->user->email)
            ->send(new BookingNotification($booking, 'confirmation'));
    }

    /**
     * Send booking cancellation email
     */
    public function sendBookingCancellation(Booking $booking)
    {
        Mail::to($booking->user->email)
            ->send(new BookingNotification($booking, 'cancellation'));
    }
    public function sendPasswordReset($user, $resetLink)
    {
        $subject = 'Password Reset Request';
        $message = view('emails.password_reset', compact('user', 'resetLink'))->render();
        Mail::to($user->email)->send(new GenericMail($subject, $message));
    }
    public function sendTwoFactorCode($user, $code)
    {
        $subject = "Your Two-Factor Authentication Code";
        $body = view('emails.two_factor_code', compact('user', 'code'))->render();
        $this->sendEmail($user->email, $subject, $body);
    }
}
