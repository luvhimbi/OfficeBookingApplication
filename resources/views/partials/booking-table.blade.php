@if($bookings->isEmpty())
    <p class="text-muted">No bookings found.</p>
@else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>

                <th>Campus</th>
                <th>Building</th>
                <th>Floor</th>
                <th>Space</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody>
            @foreach($bookings as $index => $booking)
                <tr>

                    <td>{{ $booking->campus->name ?? 'N/A' }}</td>
                    <td>{{ $booking->building->name ?? 'N/A' }}</td>
                    <td>{{ $booking->floor->name ?? 'N/A' }}</td>

                    <td>
                        @if($booking->space_type === 'desk')
                            {{ $booking->desk->desk_number ?? 'N/A' }}
                        @else
                            {{ $booking->boardroom->name ?? 'N/A' }}
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>

                    <td>
                        @php
                            $status = strtolower($booking->status);
                        @endphp

                        @if($status === 'booked')
                            <span class="badge badge-status badge-booked">Booked</span>

                        @elseif($status === 'completed')
                            <span class="badge badge-status badge-completed">Completed</span>

                        @elseif($status === 'cancelled')
                            <span class="badge badge-status badge-cancelled">Cancelled</span>

                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
