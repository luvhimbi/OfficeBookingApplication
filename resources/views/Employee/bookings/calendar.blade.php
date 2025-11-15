@extends('Layout.app')

@section('title', 'Booking Calendar')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h3 class="mb-4"><i class="bi bi-calendar3 text-primary"></i> Booking Calendar</h3>

                <!-- Debug Info (remove in production) -->
                <div class="alert alert-info d-none">
                    <strong>Debug Info:</strong>
                    <div>Total Events: <span id="event-count">{{ count($events) }}</span></div>
                    <pre id="events-data">{{ json_encode($events, JSON_PRETTY_PRINT) }}</pre>
                </div>

                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Log events to console for debugging
            var events = @json($events);
            console.log('Calendar Events:', events);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                editable: false,
                selectable: false,
                height: 'auto',
                // Add more options to ensure events display
                displayEventTime: true,
                eventDisplay: 'block',
                eventDidMount: function(info) {
                    console.log('Event rendered:', info.event.title);
                }
            });

            calendar.render();

            // Check if events are loaded
            setTimeout(function() {
                var renderedEvents = calendar.getEvents();
                console.log('Rendered events:', renderedEvents.length);
                if (renderedEvents.length === 0) {
                    console.warn('No events rendered on calendar');
                }
            }, 1000);
        });
    </script>
@endpush

<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }

    /* Ensure events are visible */
    .fc-event {
        border: 1px solid #fff;
        padding: 2px 4px;
    }
</style>
