<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Notification</title>
</head>
<body>
<h2>{{ $type === 'confirmation' ? 'Booking Confirmed' : 'Booking Cancelled' }}</h2>

<p>Dear {{ $booking->user->firstname }},</p>

@if($type === 'confirmation')
    <p>Your booking has been successfully confirmed with the following details:</p>
@else
    <p>Your booking has been cancelled. Here are the details:</p>
@endif

<ul>
    <li><strong>Space Type:</strong> {{ ucfirst($booking->space_type) }}</li>
    <li><strong>Space Name/Number:</strong>
        @if($booking->space_type === 'desk')
            {{ $booking->desk->desk_number ?? 'N/A' }}
        @elseif($booking->space_type === 'boardroom')
            {{ $booking->boardroom->name ?? 'N/A' }}
        @endif
    </li>
    <li><strong>Campus:</strong> {{ $booking->campus->name ?? 'N/A' }}</li>
    <li><strong>Building:</strong> {{ $booking->building->name ?? 'N/A' }}</li>
    <li><strong>Floor:</strong> {{ $booking->floor->name ?? 'N/A' }}</li>
    <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</li>
    <li><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</li>
    <li><strong>End Time:</strong> {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</li>
</ul>

<p>Thank you for using WorkSpace Hub!</p>
</body>
</html>
