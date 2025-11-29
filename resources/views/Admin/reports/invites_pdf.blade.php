<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flexi Space - Invite Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; color: #111; margin: 0; padding: 20px; }
        h2 { text-align: center; color: #000; margin-bottom: 20px; }
        .filters { background-color: #111; color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .filters span { margin-right: 20px; font-weight: 500; }
        .statistics { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .stat-card { background-color: #111; color: #fff; flex: 1; margin: 5px; padding: 15px; border-radius: 5px; text-align: center; }
        .stat-card h6 { margin: 0; font-size: 14px; font-weight: 500; }
        .stat-card h4 { margin: 5px 0 0 0; font-size: 22px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #111; padding: 8px 12px; font-size: 12px; text-align: left; }
        th { background-color: #111; color: #fff; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        footer { text-align: center; margin-top: 30px; font-size: 12px; color: #555; }
    </style>
</head>
<body>

<h2>Flexi Space - Invite Report</h2>

<div class="filters">
    <span><strong>Status:</strong> {{ $status ? ucfirst($status) : 'All' }}</span>
    <span><strong>From:</strong> {{ $from ?? 'N/A' }}</span>
    <span><strong>To:</strong> {{ $to ?? 'N/A' }}</span>
</div>

<div class="statistics">
    <div class="stat-card"><h6>Total Invites</h6><h4>{{ $totalInvites }}</h4></div>
    <div class="stat-card"><h6>Used Invites</h6><h4>{{ $usedInvites }}</h4></div>
    <div class="stat-card"><h6>Unused Invites</h6><h4>{{ $unusedInvites }}</h4></div>
    <div class="stat-card"><h6>Sent Today</h6><h4>{{ $todayInvites }}</h4></div>
</div>

<div>
    <h4>Recent Invites</h4>
    <table>
        <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Sent On</th>
        </tr>
        </thead>
        <tbody>
        @foreach($recentInvites as $invite)
            <tr>
                <td>{{ $invite->email }}</td>
                <td>{{ $invite->firstname }} {{ $invite->lastname }}</td>
                <td>{{ ucfirst($invite->role) }}</td>
                <td>{{ $invite->used ? 'Used' : 'Unused' }}</td>
                <td>{{ $invite->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<footer>Flexi Space &copy; {{ date('Y') }}</footer>

</body>
</html>
