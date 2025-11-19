<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #000000;
            margin-bottom: 20px;
        }
        p {
            line-height: 1.6;
            margin-bottom: 15px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin-bottom: 20px;
        }
        ul li {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        ul li:last-child {
            border-bottom: none;
        }
        .disclaimer {
            margin-top: 30px;
            font-size: 12px;
            color: #555555;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
        }
        .highlight {
            font-weight: bold;
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

    <p>Thank you for using <span class="highlight">WorkSpace Hub</span>!</p>

    <div class="disclaimer">
        <p><strong>Disclaimer:</strong> Please ensure you keep the workspace clean and tidy after use. Dispose of trash properly, return any moved items to their original positions, and respect shared spaces. Your cooperation helps maintain a productive and comfortable environment for everyone.</p>
    </div>
</div>
</body>
</html>
