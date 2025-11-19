<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Flexi Booking Notification</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #000000;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 25px;
            background-color: #111111;
            border: 1px solid #333333;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 25px;
            font-size: 24px;
            letter-spacing: 1px;
        }
        p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #e0e0e0;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin-bottom: 20px;
        }
        ul li {
            padding: 10px 0;
            border-bottom: 1px solid #333333;
            color: #ffffff;
        }
        ul li:last-child {
            border-bottom: none;
        }
        .highlight {
            font-weight: bold;
            color: #ffffff;
        }
        .disclaimer {
            margin-top: 30px;
            font-size: 12px;
            color: #aaaaaa;
            border-top: 1px solid #333333;
            padding-top: 10px;
        }
        a {
            color: #ffffff;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>{{ $type === 'confirmation' ? 'Booking Confirmed' : 'Booking Cancelled' }}</h2>

    <p>Dear <span class="highlight">{{ $booking->user->firstname }}</span>,</p>

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

    <p>Thank you for using <span class="highlight">Flexi</span> to manage your workspace efficiently!</p>

    <div class="disclaimer">
        <p><strong>Disclaimer:</strong> Please ensure you maintain cleanliness and respect shared spaces. Dispose of trash properly, return any moved items to their original positions, and follow workspace rules. Your cooperation ensures a productive environment for all users.</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Flexi. All rights reserved.
    </div>
</div>
</body>
</html>
