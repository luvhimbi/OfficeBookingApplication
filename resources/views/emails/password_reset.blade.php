<p>Hello {{ $user->firstname }},</p>

<p>You requested to reset your password. Click the link below to set a new password:</p>

<p><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>

<p>If you did not request a password reset, no action is needed.</p>

<p>Regards,<br>Office Booking App Team</p>
