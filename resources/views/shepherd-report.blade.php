@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4">📋 Shepherd Attendance Report</h2>

    @if($records->isEmpty())
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
                    <th>Marked By (Shepherd)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
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
                        <td>{{ $record->markedBy->first_name }} {{ $record->markedBy->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
