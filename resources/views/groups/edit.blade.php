@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold">
            <h4 class="mb-0">✏️ Edit Group: <strong>{{ $group->name }}</strong></h4>
        </div>

        <div class="card-body bg-light">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>⚠️ Please fix the following errors:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('groups.update', $group) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">📝 Group Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $group->name) }}" placeholder="Enter group name" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">📂 Category</label>
                    <input type="text" name="category" id="category" class="form-control"
                        value="{{ old('category', $group->category) }}" placeholder="e.g. Men, Women, Youths">
                </div>

                <div class="mb-3">
                    <label for="subcategories" class="form-label fw-semibold">🏷️ Subcategories</label>
                    <input type="text" name="subcategories" id="subcategories" class="form-control"
                        value="{{ old('subcategories', $group->subcategories ? $group->subcategories->pluck('name')->implode(', ') : '') }}"
                        placeholder="Comma-separated (e.g. Ushers, Choir, Protocol)">
                </div>

                <div class="mb-3">
                    <label for="user_names" class="form-label fw-semibold">
                        👥 Add Internal Members 
                        <small class="text-muted d-block">
                            Use full name, username or email (comma separated)
                        </small>
                    </label>
                    <input type="text" name="user_names" id="user_names" class="form-control"
                        value="{{ implode(', ', $group->users->map(fn($u) => $u->first_name . ' ' . $u->last_name)->toArray()) }}"
                        placeholder="e.g. John Doe, Jane Smith">
                </div>

                <div class="mb-4">
                    <label for="external_members" class="form-label fw-semibold">
                        🌍 Add External Members <small class="text-muted">(comma separated names)</small>
                    </label>
                    <input type="text" name="external_members" id="external_members" class="form-control"
                        placeholder="e.g. Guest One, Visitor Two">
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-warning text-dark fw-semibold">✅ Update Group</button>
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">← Back to Groups</a>
                </div>
            </form>

            {{-- Current Members --}}
            @if ($group->users->count() > 0 || $group->externalMembers->count() > 0)
                <hr>
                <h6 class="text-muted mt-4">🧑‍🤝‍🧑 Current Members:</h6>
                <ul class="list-group mb-3">
                    @foreach ($group->users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                                <span class="text-muted d-block small">{{ $user->email }}</span>
                            </div>
                            <form method="POST" action="{{ route('groups.remove-user', [$group->id, $user->id]) }}" onsubmit="return confirm('Are you sure you want to remove this internal member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">❌ Remove</button>
                            </form>
                        </li>
                    @endforeach
                    @foreach ($group->externalMembers as $ext)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>{{ $ext->name }}</strong>
                            <form method="POST" action="{{ route('groups.remove-external', [$group->id, $ext->id]) }}" onsubmit="return confirm('Are you sure you want to remove this external member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">❌ Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Subcategories --}}
            @if ($group->subcategories && $group->subcategories->count())
                <div class="mb-3">
                    <label class="form-label fw-semibold">📂 Subcategories</label>
                    <ul class="list-group">
                        @foreach ($group->subcategories as $sub)
                            <li class="list-group-item">{{ $sub->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    .card {
        border-radius: 12px;
    }
    .btn-warning:hover {
        background-color: #e0a800;
    }
</style>
@endsection
