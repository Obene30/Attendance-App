@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Attendance Report</h2>

    <form action="{{ route('attendance.report') }}" method="GET">
        <label>Category:</label>
        <select name="category" class="form-control">
            <option value="">All</option>
            <option value="men">Men</option>
            <option value="women">Women</option>
            <option value="children">Children</option>
        </select>

        <label>Date:</label>
        <input type="date" name="date" class="form-control">

        <button type="submit" class="btn btn-primary mt-2">Filter</button>
    </form>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->attendee->full_name }}</td>
                <td>{{ $attendance->attendee->category }}</td>
                <td>{{ $attendance->created_at->format('Y-m-d') }}</td>
                <td>{{ $attendance->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
    {{ $attendances->links() }}
</div>

</div>
@endsection
