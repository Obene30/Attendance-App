@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Church Attendees</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                <th>Shepherd</th>
                @hasrole('Admin')
                    <th>Assign Shepherd</th>
                @endhasrole
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
                <td>{{ $attendee->shepherd?->first_name ?? '—' }}</td>

                @hasrole('Admin')
                <td>
                    <form method="POST" action="{{ route('attendees.assign', $attendee) }}">
                        @csrf
                        <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Assign --</option>
                            @foreach(App\Models\User::role('Shepherd')->get() as $user)
                                <option value="{{ $user->id }}" {{ $attendee->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </td>
                @endhasrole

                <td>
                    <a href="{{ route('attendees.edit', $attendee) }}" class="btn btn-primary btn-sm">✏️ Edit</a>
                    <form action="{{ route('attendees.destroy', $attendee) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">❌ Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $attendees->links() }}
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this attendee?");
    }
</script>
@endsection