@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-dark">📅 Shepherd View: Upcoming Events</h2>

    {{-- Filter by Date --}}
    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-4">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning w-100 fw-semibold">🔍 Filter</button>
        </div>
    </form>

    {{-- Events Table --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white fw-semibold">📖 Events</div>
        <div class="card-body p-0">
            <table class="table table-striped text-center mb-0">
                <thead class="table-warning text-dark">
                    <tr>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y h:i A') }}</td>
                            <td>{{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('M d, Y h:i A') : '—' }}</td>
                            <td>{{ $event->description ?? '—' }}</td>
                            <td>
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $start = \Carbon\Carbon::parse($event->start_time);
                                @endphp
                                @if($start->isToday())
                                    <span class="badge bg-info text-dark">Today</span>
                                @elseif($start->isFuture())
                                    <span class="badge bg-success">Upcoming</span>
                                @else
                                    <span class="badge bg-secondary">Past</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">No events found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $events->appends(request()->query())->links() }}
    </div>

    {{-- Calendar View --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-warning fw-semibold text-dark">🗓️ Calendar</div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

{{-- FullCalendar --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
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
</script>

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    #calendar {
        width: 100%;
        margin-top: 1rem;
    }

    .btn-warning {
        background-color: #f5c518;
        border-color: #f5c518;
    }

    .btn-warning:hover {
        background-color: #e0b114;
    }
</style>
@endsection
