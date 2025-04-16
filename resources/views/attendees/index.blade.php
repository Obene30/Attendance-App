@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h2 class="text-dark fw-bold mb-2">⛪ Church Attendees</h2>
        <a href="{{ route('attendees.create') }}" class="btn btn-warning fw-semibold text-dark shadow-sm">
            ➕ Add New Attendee
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- Desktop Table View --}}
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-bordered table-hover align-middle shadow-sm text-center">
            <thead class="table-warning text-dark">
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Birthdays</th>
                    <th>Sex</th>
                    <th>Category</th>
                    <th>Shepherd</th>
                    @hasrole('Admin') <th>Assign Shepherd</th> @endhasrole
                    <th>Visitation</th>
                    <th>Status</th>
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
                                @foreach(App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['Shepherd', 'Admin']))->get() as $user)
                                    <option value="{{ $user->id }}" {{ $attendee->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }} ({{ $user->getRoleNames()->first() }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    @endhasrole

                    {{-- Visitation Form --}}
                    <td>
                        @include('partials._visitation-form', ['attendee' => $attendee])
                    </td>

                    <td>
                        @if($attendee->visitation)
                            @if($attendee->visitation->shepherd_comment)
                                <span class="badge bg-success">✅ Completed</span>
                            @else
                                <span class="badge bg-warning text-dark">⏳ Pending</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('attendees.edit', $attendee) }}" class="btn btn-sm btn-primary">✏️</a>
                            <form action="{{ route('attendees.destroy', $attendee) }}" method="POST" onsubmit="return confirmDelete();">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">❌</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile / iPad Card View --}}
    <div class="d-lg-none">
        @foreach($attendees as $attendee)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $attendee->full_name }}</h5>
                    <p class="mb-1"><strong>Address:</strong> {{ $attendee->address }}</p>
                    <p class="mb-1"><strong>DOB:</strong> {{ $attendee->dob }}</p>
                    <p class="mb-1"><strong>Sex:</strong> {{ $attendee->sex }}</p>
                    <p class="mb-1"><strong>Category:</strong> {{ $attendee->category }}</p>
                    <p class="mb-1"><strong>Shepherd:</strong> {{ $attendee->shepherd?->first_name ?? '—' }}</p>

                    @hasrole('Admin')
                    <form method="POST" action="{{ route('attendees.assign', $attendee) }}" class="mb-2">
                        @csrf
                        <label class="form-label small mb-1">Assign Shepherd</label>
                        <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Assign --</option>
                            @foreach(App\Models\User::whereHas('roles', fn($q) => $q->whereIn('name', ['Shepherd', 'Admin']))->get() as $user)
                                <option value="{{ $user->id }}" {{ $attendee->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->getRoleNames()->first() }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @endhasrole

                    {{-- Visitation --}}
                    @include('partials._visitation-form', ['attendee' => $attendee])

                    <p class="mb-2 mt-2">
                        <strong>Status:</strong>
                        @if($attendee->visitation)
                            <span class="badge {{ $attendee->visitation->shepherd_comment ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $attendee->visitation->shepherd_comment ? '✅ Completed' : '⏳ Pending' }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </p>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('attendees.edit', $attendee) }}" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <form action="{{ route('attendees.destroy', $attendee) }}" method="POST" onsubmit="return confirmDelete();">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">❌ Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $attendees->links() }}
    </div>
</div>

{{-- Confirmation --}}
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this attendee?");
    }
</script>

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .btn-warning {
        background-color: #f5c518;
        border-color: #f5c518;
    }
    .btn-warning:hover {
        background-color: #e0b114;
    }
    .form-select:focus {
        border-color: #f5c518;
        box-shadow: 0 0 0 0.2rem rgba(245, 197, 24, 0.25);
    }
</style>
@endsection
