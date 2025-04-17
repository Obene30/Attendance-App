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

    <form action="{{ route('attendees.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-8 col-sm-12">
                <input type="text" name="search" class="form-control" placeholder="Search by name, shepherd, or status"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4 col-sm-12">
                <button type="submit" class="btn btn-warning w-100 fw-semibold text-dark">
                    🔍 Search
                </button>
            </div>
        </div>
    </form>

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
                    <th>Assigned visitation</th>
                    @hasrole('Admin') <th>Assign Shepherd</th> @endhasrole
                    <th>Visitations</th>
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
                    <td>
                        @if($attendee->visitation && $attendee->visitation->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($attendee->visitation as $visit)
                                    <li>
                                        {{ $visit->shepherd->first_name ?? '—' }} {{ $visit->shepherd->last_name ?? '' }}
                                        <span class="badge {{ $visit->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $visit->status }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

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

                    <td>
                        @include('partials._visitation-form', ['attendee' => $attendee])

                        @hasrole('Admin')
                            @if($attendee->visitation && $attendee->visitation->count())
                                <form action="{{ route('attendees.visitation.cancel', $attendee) }}" method="POST" onsubmit="return confirmCancel();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-2 w-100">❌ Cancel Request</button>
                                </form>
                            @endif
                        @endhasrole
                    </td>

                    <td>
                        @php
                            $hasVisit = $attendee->visitation && $attendee->visitation->count();
                            $isCompleted = $hasVisit ? $attendee->visitation->contains(fn($v) => $v->status === 'Completed') : false;
                        @endphp

                        @if(!$hasVisit)
                            <span class="text-muted">—</span>
                        @elseif($isCompleted)
                            <span class="badge bg-success">✅ Completed</span>
                        @else
                            <span class="badge bg-warning text-dark">⏳ Pending</span>
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

  {{-- This part stays unchanged until the mobile view --}}
{{-- ... --}}

{{-- Mobile View --}}
<div class="d-lg-none">
    @foreach($attendees as $attendee)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">{{ $attendee->full_name }}</h5>
                <p class="mb-1"><strong>Address:</strong> {{ $attendee->address }}</p>
                <p class="mb-1"><strong>DOB:</strong> {{ $attendee->dob }}</p>
                <p class="mb-1"><strong>Sex:</strong> {{ $attendee->sex }}</p>
                <p class="mb-1"><strong>Category:</strong> {{ $attendee->category }}</p>

                {{-- Visitation Shepherd List --}}
                <div class="mb-2">
                    <strong>Shepherd(s) & Status:</strong>
                    @if($attendee->visitation && $attendee->visitation->count())
                        <ul class="list-group list-group-flush small">
                            @foreach($attendee->visitation as $visit)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $visit->shepherd->first_name ?? '—' }} {{ $visit->shepherd->last_name ?? '' }}</span>
                                    <span class="badge rounded-pill {{ $visit->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $visit->status }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-1">No visitation assigned.</p>
                    @endif
                </div>

                {{-- Assign Shepherd for Admin --}}
                @hasrole('Admin')
                <form method="POST" action="{{ route('attendees.assign', $attendee) }}" class="mb-3">
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

                {{-- Visitation Form --}}
                <h6 class="fw-bold mt-2">Visitation</h6>
                @include('partials._visitation-form', ['attendee' => $attendee])

                {{-- Cancel Button for Admin --}}
                @hasrole('Admin')
                    @if($attendee->visitation && $attendee->visitation->count())
                        <form action="{{ route('attendees.visitation.cancel', $attendee) }}" method="POST" onsubmit="return confirmCancel();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger mt-2 w-100">❌ Cancel All Requests</button>
                        </form>
                    @endif
                @endhasrole

                {{-- Status Overview --}}
                <p class="mb-2 mt-3">
                    <strong>Overall Status:</strong>
                    @php
                        $hasVisit = $attendee->visitation && $attendee->visitation->count();
                        $isCompleted = $hasVisit ? $attendee->visitation->contains(fn($v) => $v->status === 'Completed') : false;
                    @endphp

                    @if(!$hasVisit)
                        <span class="text-muted">—</span>
                    @elseif($isCompleted)
                        <span class="badge bg-success">✅ Completed</span>
                    @else
                        <span class="badge bg-warning text-dark">⏳ Pending</span>
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

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-3">
    {{ $attendees->links() }}
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this attendee?");
    }
    function confirmCancel() {
        return confirm("⚠️ Are you sure you want to cancel this visitation request?");
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
