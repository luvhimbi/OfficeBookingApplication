@extends('Layout.app')

@section('content')
    <div class="container">
        <h3>Availability Details</h3>

        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Resource:</strong>
                    @if($availability->available)
                        {{ class_basename($availability->available_type) }} — {{ $availability->available->name ?? 'ID: '.$availability->available_id }}
                    @else
                        {{ class_basename($availability->available_type) }} — ID: {{ $availability->available_id }}
                    @endif
                </p>

                <p><strong>Date:</strong> {{ $availability->date->format('Y-m-d') }}</p>
                <p><strong>Time:</strong> {{ \Illuminate\Support\Str::substr($availability->start_time,0,5) }} - {{ \Illuminate\Support\Str::substr($availability->end_time,0,5) }}</p>
                <p><strong>Active:</strong> {{ $availability->is_active ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <a href="{{ route('availabilities.edit', $availability) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('availabilities.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection
