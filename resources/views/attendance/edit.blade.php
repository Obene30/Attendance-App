@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Attendance Record</h2>

    <form action="{{ route('attendance.update', $attendance) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="attendee_id">Attendee</label>
            <select name="attendee_id" id="attendee_id" class="form-control" required>
                @foreach($attendees as $attendee)
                    <option value="{{ $attendee->id }}" {{ $attendee->id == $attendance->attendee_id ? 'selected' : '' }}>
                        {{ $attendee->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $attendance->date }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Attendance</button>
    </form>
</div>
@endsection
