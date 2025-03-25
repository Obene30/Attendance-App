@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4">✅ Mark Attendance</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.querySelector('.alert-success');
                if (alert) alert.remove();
            }, 4000); // Auto-dismiss after 4 seconds
        </script>
    @endif

    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf

        <table class="table table-bordered">
            <thead>
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
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="attendance[{{ $attendee->id }}][comment]" class="form-control" placeholder="Optional comment">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mb-3">
            <label for="date" class="form-label">Attendance Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Attendance</button>
    </form>
    @if($existingRecords->count())
    <div class="mt-4">
        <h5>🗑️ Already Marked for {{ \Carbon\Carbon::parse($selectedDate)->format('d M, Y') }}</h5>
        <table class="table table-bordered">
            <thead>
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
                            <span class="badge {{ $record->status == 'Present' ? 'bg-success' : 'bg-danger' }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td>{{ $record->comment ?? '—' }}</td>
                        <td>
                            <form action="{{ route('attendance.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">❌ Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

</div>
@endsection
