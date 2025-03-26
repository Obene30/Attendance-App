@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-4"></div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-dark fw-bold">⛪ Church Attendees</h2>
        <a href="{{ route('attendees.create') }}" class="btn btn-warning fw-semibold text-dark shadow-sm">
            ➕ Add New Attendee
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm">
            <thead class="table-warning text-dark">
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Birthdays</th>
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
                    <td>{{ $attendee->dob }}</td>
                    <td>{{ $attendee->sex }}</td>
                    <td>{{ $attendee->category }}</td>
                    <td>{{ $attendee->shepherd?->first_name ?? '—' }}</td>

                    @hasrole('Admin')
                    <td>
                        <form method="POST" action="{{ route('attendees.assign', $attendee) }}">
                            @csrf
                            <select name="user_id" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                                <option value="">-- Assign --</option>
                                @foreach(App\Models\User::whereHas('roles', function($q) {
                                    $q->whereIn('name', ['Shepherd', 'Admin']);
                                })->get() as $user)
                                <option value="{{ $user->id }}" {{ $attendee->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->getRoleNames()->first() }})
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    @endhasrole

                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('attendees.edit', $attendee) }}" class="btn btn-sm btn-primary" title="Edit Attendee">✏️</a>
                            <form action="{{ route('attendees.destroy', $attendee) }}" method="POST" onsubmit="return confirmDelete();">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Attendee">❌</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $attendees->links() }}
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this attendee?");
    }
</script>

<style>
    .table thead th {
        vertical-align: middle;
        text-align: center;
    }

    .btn-warning {
        background-color: #f5c518;
        border-color: #f5c518;
    }

    .btn-warning:hover {
        background-color: #e0b114;
        border-color: #e0b114;
    }

    .form-select:focus {
        border-color: #f5c518;
        box-shadow: 0 0 0 0.2rem rgba(245, 197, 24, 0.25);
    }
</style>
@endsection
