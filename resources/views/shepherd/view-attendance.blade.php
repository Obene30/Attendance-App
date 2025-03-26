@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-3"></div>

    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold">
            <h4 class="mb-0">📅 My Attendance Records</h4>
        </div>

        <div class="card-body bg-light">
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if($attendances->isEmpty())
                <div class="alert alert-info text-center">No attendance records found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
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
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                                    <td>{{ $attendance->attendee->full_name }}</td>
                                    <td>{{ $attendance->attendee->category }}</td>
                                    <td>
                                        <span class="badge {{ $attendance->status === 'Present' ? 'bg-success' : 'bg-danger' }}"
                                              data-bs-toggle="tooltip"
                                              title="{{ $attendance->status === 'Present' ? 'Attended' : 'Absent' }}">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->comment ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bootstrap Icons (Optional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Enable Bootstrap Tooltips -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (el) {
            new bootstrap.Tooltip(el)
        })
    });
</script>
@endsection
