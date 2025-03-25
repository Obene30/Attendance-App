@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">📅 My Attendance Records</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($attendances->isEmpty())
        <div class="alert alert-info">No attendance records found.</div>
    @else
        <table class="table table-bordered">
            <thead class="table-dark">
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
                            <span class="badge {{ $attendance->status === 'Present' ? 'bg-success' : 'bg-danger' }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                        <td>{{ $attendance->comment ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
