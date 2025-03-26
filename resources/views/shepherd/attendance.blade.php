@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-3"></div>

    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold d-flex justify-content-between align-items-center">
            <h4 class="mb-0">✅ Mark Attendance</h4>
        </div>

        <div class="card-body bg-light">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(() => {
                        const alert = document.querySelector('.alert-success');
                        if (alert) alert.remove();
                    }, 4000);
                </script>
            @endif

            <form method="POST" action="{{ route('attendance.store') }}">
                @csrf

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-warning text-dark">
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendees as $attendee)
                            <tr>
                                <td>{{ $attendee->full_name }}</td>
                                <td>
                                    <select name="attendance[{{ $attendee->id }}][status]" class="form-select">
                                        <option value="">-- Select --</option>
                                        <option value="Present">✅ Present</option>
                                        <option value="Absent">❌ Absent</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="attendance[{{ $attendee->id }}][comment]" class="form-control" placeholder="Optional comment">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label fw-semibold">Attendance Date</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-check-circle"></i> Submit Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($existingRecords->count())
    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-secondary text-white fw-semibold">
            🗓️ Already Marked for {{ \Carbon\Carbon::parse($selectedDate)->format('d M, Y') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Attendee</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($existingRecords as $record)
                    <tr>
                        <td>{{ $record->attendee->full_name }}</td>
                        <td>
                            <span class="badge {{ $record->status == 'Present' ? 'bg-success' : 'bg-danger' }}" data-bs-toggle="tooltip" title="{{ $record->status == 'Present' ? 'Marked as Present' : 'Marked as Absent' }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td>{{ $record->comment ?? '—' }}</td>
                        <td>
                            <form action="{{ route('attendance.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Delete this record?')" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Remove attendance record">
                                    <i class="bi bi-trash3-fill"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Bootstrap Icons CDN (if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Optional: Enable tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
