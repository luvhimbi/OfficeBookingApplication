@extends('Layout.app')

@section('title', 'Notifications')

@section('content')
    <div class="container my-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bell-fill text-primary"></i> Notifications</h5>
            </div>
            <div class="card-body">
                @if($notifications->isEmpty())
                    <p class="text-muted">No new notifications.</p>
                @else
                    <ul class="list-group">
                        @foreach($notifications as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->is_read ? '' : 'list-group-item-info' }}">
                                <div>
                                    <strong>{{ $notification->title }}</strong> <br>
                                    <small>{{ $notification->message }}</small>
                                </div>
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success">Mark as Read</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <style>
        .list-group-item-info {
            background-color: rgba(102, 126, 234, 0.2);
        }
    </style>
@endsection
