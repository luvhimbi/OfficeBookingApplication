@extends('Layout.app')

@section('title','Devices')

@section('content')
    <div class="container mt-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <h4>Active Devices / Sessions</h4>
                <table class="table table-striped mt-3">
                    <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Browser</th>
                        <th>Platform</th>
                        <th>Last Activity</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($sessions as $session)
                        <tr>
                            <td>{{ $session['ip_address'] }}</td>
                            <td>{{ $session['browser'] }}</td>
                            <td>{{ $session['platform'] }}</td>
                            <td>{{ $session['last_activity'] }}</td>
                            <td>
                                <form action="{{ route('profile.devices.logout', $session['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if(count($sessions) == 0)
                        <tr>
                            <td colspan="5" class="text-center">No active devices</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
