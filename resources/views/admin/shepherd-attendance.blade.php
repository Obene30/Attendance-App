@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4">📉 Shepherd Attendance Overview</h2>

    <!-- Date Selection (optional for filtering future logic) -->
    <form method="GET" action="{{ route('admin.shepherd-attendance') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="date" class="form-label">Filter by Attendance Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') ?? now()->toDateString() }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @if($shepherds->isEmpty())
        <div class="alert alert-info">No shepherds have marked attendance yet.</div>
    @else
        @foreach($shepherds as $shepherd)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    {{ $shepherd->first_name }} {{ $shepherd->last_name }} ({{ $shepherd->email }})
                </div>
                <div class="card-body p-0">
                    @if($shepherd->attendances->isEmpty())
                        <div class="p-3 text-muted">No attendance records from this shepherd.</div>
                    @else
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Attendee</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shepherd->attendances as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                                        <td>{{ $record->attendee->full_name }}</td>
                                        <td>{{ $record->attendee->category }}</td>
                                        <td>
                                            <span class="badge {{ $record->status === 'Present' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $record->status }}
                                            </span>
                                        </td>
                                        <td>{{ $record->comment ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center p-2">
                            {{ $shepherd->attendances->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
