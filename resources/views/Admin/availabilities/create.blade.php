@extends('layout.app')

@section('content')
    <div class="container mt-4">

        <div class="card">
            <div class="card-body">

                <h4>Add Availability</h4>

                <form action="{{ route('availabilities.store') }}" method="POST">
                    @csrf

                    {{-- Choose Desk or Boardroom --}}
                    <div class="mb-3">
                        <label class="form-label">Resource Type</label>
                        <select name="resource_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="desk">Desk</option>
                            <option value="boardroom">Boardroom</option>
                        </select>
                    </div>

                    {{-- Desk Dropdown --}}
                    <div class="mb-3">
                        <label class="form-label">Desk</label>
                        <select name="resource_id" class="form-select">
                            <option value="">-- Select Desk --</option>
                            @foreach($desks as $desk)
                                <option value="{{ $desk->id }}">
                                    {{ $desk->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Boardroom Dropdown --}}
                    <div class="mb-3">
                        <label class="form-label">Boardroom</label>
                        <select name="resource_id" class="form-select">
                            <option value="">-- Select Boardroom --</option>
                            @foreach($boardrooms as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <button class="btn btn-primary">Save</button>
                </form>

            </div>
        </div>

    </div>
@endsection
