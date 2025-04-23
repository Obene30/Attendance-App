@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success text-center fw-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-dark mb-2">📅 Event Manager</h2>

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('events.index') }}" class="d-flex gap-2">
            <input type="date" name="search_date" class="form-control" value="{{ request('search_date') }}" />
            <button type="submit" class="btn btn-warning text-dark fw-semibold">
                🔍 Filter by Date
            </button>
        </form>
    </div>

    {{-- Create Event Form --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-warning fw-semibold text-dark">
            ➕ Create New Event
        </div>
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('events.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        📌 Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Upcoming Events Table --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white fw-semibold">
            📖 Upcoming Events
        </div>
        <div class="card-body bg-white p-0">
            <table class="table table-striped mb-0 text-center">
                <thead class="table-warning text-dark">
                    <tr>
                        <th>Title</th>
                        <th>Date & Time</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }}
                                @if($event->end_time)
                                    — {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                @endif
                            </td>
                            <td>{{ $event->description ?? '—' }}</td>
                            <td>
                                @if(\Carbon\Carbon::parse($event->start_time)->isPast())
                                    <span class="badge bg-secondary">Past</span>
                                @elseif(\Carbon\Carbon::parse($event->start_time)->isToday())
                                    <span class="badge bg-info text-dark">Today</span>
                                @else
                                    <span class="badge bg-success">Upcoming</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-primary">✏️ Edit</a>
                                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No events found for the selected date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer d-flex justify-content-center">
            {{ $events->appends(['search_date' => request('search_date')])->links() }}
        </div>
    </div>

    {{-- FullCalendar View --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark fw-semibold">
            🗓️ Calendar View
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

{{-- FullCalendar CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            themeSystem: 'standard',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: @json($calendarEvents),
            eventDidMount: function(info) {
                new bootstrap.Tooltip(info.el, {
                    title: info.event.extendedProps.description,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });
        calendar.render();
    });

    function confirmDelete() {
        return confirm("⚠️ Are you sure you want to delete this event?");
    }
</script>

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .form-control:focus, .form-select:focus {
        border-color: #f5c518;
        box-shadow: 0 0 0 0.2rem rgba(245, 197, 24, 0.25);
    }

    .btn-warning {
        background-color: #f5c518;
        border-color: #f5c518;
    }

    .btn-warning:hover {
        background-color: #e0b114;
    }

    #calendar {
        width: 100%;
        margin-top: 1rem;
    }

    .fc .fc-toolbar-title {
        font-weight: bold;
        color: #333;
    }
</style>
@endsection
