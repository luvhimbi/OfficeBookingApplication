@extends('Layout.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Availabilities</h3>
            <a href="{{ route('availabilities.create') }}" class="btn btn-primary">Add Availability</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Resource</th>
                <th>Date</th>
                <th>Start</th>
                <th>End</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($availabilities as $a)
                <tr>
                    <td>
                        @if($a->available)
                            {{ class_basename($a->available_type) }} — {{ $a->available->name ?? 'ID: '.$a->available_id }}
                        @else
                            {{ class_basename($a->available_type) }} — ID: {{ $a->available_id }}
                        @endif
                    </td>
                    <td>{{ $a->date->format('Y-m-d') }}</td>
                    <td>{{ \Illuminate\Support\Str::substr($a->start_time, 0, 5) }}</td>
                    <td>{{ \Illuminate\Support\Str::substr($a->end_time, 0, 5) }}</td>
                    <td>{{ $a->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('availabilities.show', $a) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('availabilities.edit', $a) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                        <form action="{{ route('availabilities.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this availability?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No availabilities yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $availabilities->links() }}
    </div>
@endsection
