@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark text-center fw-bold">
            <h4 class="mb-0">📋 Weekly Attendance Records</h4>
        </div>

        <div class="card-body bg-light">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('attendance.report') }}" class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <label for="date" class="form-label fw-semibold">Filter by Date</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>

            {{-- Attendance Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-warning text-dark">
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                                <td>{{ $attendance->attendee->full_name }}</td>
                                <td>{{ $attendance->attendee->category }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 
                                        {{ $attendance->status === 'Present' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons (if not already loaded) --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- Optional Styles --}}
<style>
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    .card {
        border-radius: 12px;
    }
    .btn-warning:hover {
        background-color: #e0a800;
    }
</style>
@endsection
