<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flexi Space User Report</title>
    <style>
        /* Import Poppins font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: #111111;
            margin: 0;
            padding: 20px;
        }

        h2, h4 {
            margin-bottom: 10px;
            font-weight: 600;
        }

        h2 {
            font-size: 28px;
            color: #000000;
            text-align: center;
            margin-bottom: 20px;
        }

        h4 {
            color: #000000;
            margin-top: 20px;
        }

        p {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .filters {
            background-color: #111111;
            color: #ffffff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .filters span {
            display: inline-block;
            margin-right: 20px;
            font-weight: 500;
        }

        .statistics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: #111111;
            color: #ffffff;
            flex: 1;
            margin: 5px;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .stat-card h6 {
            margin: 0;
            font-weight: 500;
            font-size: 14px;
        }

        .stat-card h3 {
            margin: 5px 0 0 0;
            font-size: 22px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #111111;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
        }

        table th {
            background-color: #111111;
            color: #ffffff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #555555;
        }
    </style>
</head>
<body>

<h2>Flexi Space - User Report</h2>
<p>Filtered Report</p>

{{-- Filters display --}}
<div class="filters">
    <span><strong>Role:</strong> {{ request('role') ? ucfirst(request('role')) : 'All' }}</span>
    <span><strong>From:</strong> {{ request('from') ?? 'N/A' }}</span>
    <span><strong>To:</strong> {{ request('to') ?? 'N/A' }}</span>
</div>

{{-- Statistics --}}
<div class="statistics">
    <div class="stat-card">
        <h6>Total Users</h6>
        <h3>{{ $totalUsers }}</h3>
    </div>
    <div class="stat-card">
        <h6>Admins</h6>
        <h3>{{ $admins }}</h3>
    </div>
    <div class="stat-card">
        <h6>Employees</h6>
        <h3>{{ $employees }}</h3>
    </div>
    <div class="stat-card">
        <h6>Active Today</h6>
        <h3>{{ $activeToday }}</h3>
    </div>
</div>

{{-- Users Table --}}
<div>
    <h4>Users</h4>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date Joined</th>
        </tr>
        </thead>
        <tbody>
        @foreach($recentUsers as $user)
            <tr>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<footer>
    Flexi Space &copy; {{ date('Y') }} | All Rights Reserved
</footer>

</body>
</html>
