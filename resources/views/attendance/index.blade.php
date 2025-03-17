@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Attendance Records</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                <td>{{ $attendance->attendee->full_name }}</td>
                <td>{{ $attendance->attendee->category }}</td>
                <td>
                    <span class="badge {{ $attendance->status == 'Present' ? 'bg-success' : 'bg-danger' }}">
                        {{ $attendance->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $attendances->links() }}
    </div>

</div>
@endsection
