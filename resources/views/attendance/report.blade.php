@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h2 class="mt-3">📋 Weekly Attendance Records</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
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
                        <span class="badge rounded-pill px-3 py-2 
                            {{ $attendance->status === 'Present' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                            {{ $attendance->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
