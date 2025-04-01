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

            {{-- Internal Members --}}
            @if ($group->users->count())
                <hr>
                <h6 class="text-muted mt-4">🧑‍🤝‍🧑 Current Internal Members:</h6>
                <ul class="list-group mb-3">
                    @foreach ($group->users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                                <span class="text-muted d-block small">{{ $user->email }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary">User #{{ $user->id }}</span>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->first_name }} {{ $user->last_name }}"
                                    data-type="internal">❌ Remove</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- External Members --}}
            @if ($group->externalMembers->count())
                <h6 class="text-muted mt-4">🌐 External Members:</h6>
                <ul class="list-group mb-3">
                    @foreach ($group->externalMembers as $ext)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>{{ $ext->name }}</strong>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal"
                                data-user-id="{{ $ext->id }}"
                                data-user-name="{{ $ext->name }}"
                                data-type="external">❌ Remove</button>
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteMemberForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Removal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove <strong id="modalMemberName"></strong> from the group?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Remove</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteModal = document.getElementById('confirmDeleteModal');
        const form = document.getElementById('deleteMemberForm');
        const modalName = document.getElementById('modalMemberName');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const type = button.getAttribute('data-type');

            modalName.textContent = userName;

            const url = type === 'internal'
                ? `{{ url('groups/' . $group->id . '/remove-user') }}/${userId}`
                : `{{ url('groups/' . $group->id . '/remove-external') }}/${userId}`;

            form.action = url;
        });
    });
</script>

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
