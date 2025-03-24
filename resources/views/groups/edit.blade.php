@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4 mb-4 text-center">✏️ Edit Group: <strong>{{ $group->name }}</strong></h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('groups.update', $group) }}">
                @csrf
                @method('PUT')

                {{-- Group Name --}}
                <div class="form-group mb-4">
                    <label for="name" class="form-label fw-semibold">📝 Group Name</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name', $group->name) }}"
                        placeholder="Enter group name"
                        required
                    >
                </div>

                {{-- Add Members --}}
                <div class="form-group mb-4">
                    <label for="user_names" class="form-label fw-semibold">
                        👥 Add Members by Full Name <small class="text-muted">(comma separated)</small>
                    </label>
                    <input
                        type="text"
                        name="user_names"
                        id="user_names"
                        class="form-control"
                        placeholder="e.g. John Doe, Jane Smith"
                        value="{{ implode(', ', $group->users->map(fn($u) => $u->first_name . ' ' . $u->last_name)->toArray()) }}"
                    >
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary">✅ Update Group</button>
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">← Back to Groups</a>
                </div>
            </form>

            {{-- Current Members --}}
            @if ($group->users->count())
                <hr>
                <h6 class="text-muted mt-4">🧑‍🤝‍🧑 Current Members:</h6>
                <ul class="list-group mb-3">
                    @foreach ($group->users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $user->first_name }} {{ $user->last_name }}
                                <span class="text-muted d-block small">{{ $user->email }}</span>
                            </div>
                            <span class="badge bg-secondary">{{ $user->id }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
