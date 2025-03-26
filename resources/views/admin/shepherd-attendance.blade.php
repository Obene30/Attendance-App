@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold">
            <h4 class="mb-0">📉 Shepherd Attendance Log</h4>
        </div>

        <div class="card-body bg-light">
            <!-- Date Filter -->
            <form method="GET" action="{{ route('admin.shepherd-attendance') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="date" class="form-label fw-semibold">Filter by Attendance Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ request('date') ?? now()->toDateString() }}">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-warning fw-semibold text-dark">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($shepherds->isEmpty())
                <div class="alert alert-info text-center">No shepherds have marked attendance yet.</div>
            @else
                @foreach($shepherds as $shepherd)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header text-white fw-semibold" style="background-color: #6f4e37;">
                        {{ $shepherd->first_name }} {{ $shepherd->last_name }} ({{ $shepherd->email }}) -
                        <span class="badge bg-light text-dark">{{ $shepherd->getRoleNames()->first() }}</span>
                    </div>
                
                        <div class="card-body p-0">
                            @if($shepherd->attendances->isEmpty())
                                <div class="p-3 text-muted">No attendance records from this user.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-warning text-dark">
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
                                                        <span class="badge {{ $record->status === 'Present' ? 'bg-success' : 'bg-danger' }}"
                                                              data-bs-toggle="tooltip"
                                                              title="{{ $record->status === 'Present' ? 'Marked as Present' : 'Marked as Absent' }}">
                                                            {{ $record->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $record->comment ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-center p-3">
                                    {{ $shepherd->attendances->withQueryString()->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Optional Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
    // Enable tooltips
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (el) {
            new bootstrap.Tooltip(el)
        });
    });
</script>



@endsection
