@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Mark Attendance</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.querySelector('.alert-success');
                if (alert) alert.remove();
            }, 4000); // 4 seconds
        </script>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Select Date</label>
            <input type="date" class="form-control" name="date" required>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendees as $attendee)
                <tr>
                    <td>
                        <input type="checkbox" name="attendees[]" value="{{ $attendee->id }}" class="attendee-checkbox">
                    </td>
                    <td>{{ $attendee->full_name }}</td>
                    <td>{{ $attendee->category }}</td>
                    <td>
                        <select name="status[{{ $attendee->id }}]" class="form-select">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="comment[{{ $attendee->id }}]" class="form-control comment-input" placeholder="Enter comment" disabled>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $attendees->links() }}
        </div>

        <button type="submit" class="btn btn-primary">Submit Attendance</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll(".attendee-checkbox");
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                const commentInput = this.closest("tr").querySelector(".comment-input");
                commentInput.disabled = !this.checked;
            });
        });
    });
</script>
@endsection
