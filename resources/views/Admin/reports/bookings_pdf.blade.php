<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flexi Space - Booking Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            margin:0;
            padding:20px;
            color:#111;
        }
        h2 {
            text-align:center;
            margin-bottom:20px;
            font-size:28px;
        }
        .filters,
        .statistics
        {
            margin-bottom:20px;
        }
        .filters span
        {
            margin-right:20px;
            font-weight:500;
        }
        .statistics {
            display:flex;
            justify-content:space-between;
        }
        .stat-card
        {
            background:#111;
            color:#fff;
            flex:1;
            margin:5px;
            padding:15px;
            text-align:center;
            border-radius:5px;
        }
        .stat-card h6
        {  margin:0;
            font-size:14px;
        }
        .stat-card h4
        {
            margin:5px 0 0 0;
            font-size:22px;
        }
        table {
            width:100%;
            border-collapse: collapse;
            margin-top:10px;
            font-size:11px;
        }
        th, td { border:1px solid #111;
            padding:6px 8px;
            text-align:left;
        }
        th
        {
            background:#111;
            color:#fff;
        }
        tr:nth-child(even) {
            background:#f2f2f2;
        }
        footer {
            text-align:center;
            margin-top:30px;
            font-size:12px;
            color:#555;
        }
    </style>
</head>
<body>

<h2>Flexi Space - Booking Report</h2>

<div class="filters">
    <span><strong>Status:</strong> {{ $status ?? 'All' }}</span>
    <span><strong>From:</strong> {{ $from ?? 'N/A' }}</span>
    <span><strong>To:</strong> {{ $to ?? 'N/A' }}</span>
</div>

<div class="statistics">
    <div class="stat-card"><h6>Total Bookings</h6><h4>{{ $totalBookings }}</h4></div>
    <div class="stat-card"><h6>Upcoming</h6><h4>{{ $upcoming }}</h4></div>
    <div class="stat-card"><h6>Cancelled</h6><h4>{{ $cancelled }}</h4></div>
    <div class="stat-card"><h6>Today</h6><h4>{{ $todayBookings }}</h4></div>
</div>

<h4>Recent Bookings</h4>
<table>
    <thead>
    <tr>
        <th>User</th><th>Date</th><th>Start</th><th>End</th><th>Status</th>
        <th>Campus</th><th>Building</th><th>Floor</th><th>Space Type</th>
    </tr>
    </thead>
    <tbody>
    @foreach($recentBookings as $b)
        <tr>
            <td>{{ $b->user->firstname ?? 'N/A' }}</td>
            <td>{{ $b->date->format('Y-m-d') }}</td>
            <td>{{ $b->start_time }}</td>
            <td>{{ $b->end_time }}</td>
            <td>{{ ucfirst($b->status) }}</td>
            <td>{{ $b->campus->name ?? 'N/A' }}</td>
            <td>{{ $b->building->name ?? 'N/A' }}</td>
            <td>{{ $b->floor->name ?? 'N/A' }}</td>
            <td>{{ ucfirst($b->space_type) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4>Monthly Trends</h4>
<table>
    <thead><tr><th>Month</th><th>Bookings</th></tr></thead>
    <tbody>
    @foreach($monthlyLabels as $i => $month)
        <tr><td>{{ $month }}</td><td>{{ $monthlyData[$i] }}</td></tr>
    @endforeach
    </tbody>
</table>

<h4>Top 10 Users</h4>
<table>
    <thead><tr><th>User</th><th>Total Bookings</th></tr></thead>
    <tbody>
    @foreach($userRanking as $rank)
        <tr><td>{{ $rank['user']->firstname ?? 'User Deleted' }}</td><td>{{ $rank['total'] }}</td></tr>
    @endforeach
    </tbody>
</table>

<footer>Flexi Space &copy; {{ date('Y') }}</footer>

</body>
</html>
