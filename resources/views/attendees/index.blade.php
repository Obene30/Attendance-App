<!-- resources/views/attendees/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Church Attendees</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('attendees.create') }}" class="btn btn-success mb-3">➕ Add New Attendee</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>DOB</th>
                <th>Sex</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendees as $attendee)
            <tr>
                <td>{{ $attendee->full_name }}</td>
                <td>{{ $attendee->address }}</td>
                <td>{{ $attendee->DOB }}</td>
                <td>{{ $attendee->sex }}</td>
                <td>{{ $attendee->category }}</td>
                <td>
                    <a href="{{ route('attendees.edit', $attendee) }}" class="btn btn-primary">✏️ Edit</a>
                    <!-- Delete Button with Confirmation -->
                    <form action="{{ route('attendees.destroy', $attendee) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">❌ Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
    {{ $attendees->links() }}
</div>

</div>

<!-- JavaScript for confirmation dialog -->
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this attendee?");
    }
</script>
@endsection
