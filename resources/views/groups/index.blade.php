@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📘 Group Management</h4>

            <form action="{{ route('groups.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by group or member name..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-dark">Search</button>
            </form>
        </div>

        <div class="card-body bg-light">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <a href="{{ route('groups.create') }}" class="btn btn-warning text-dark fw-semibold mb-4">
                ➕ Create New Group
            </a>

            @foreach ($groups as $group)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header text-white fw-semibold d-flex justify-content-between align-items-start"
                         style="background-color: #6f4e37;">
                        <div>
                            <h5 class="mb-1">{{ $group->name }}</h5>
                            @if($group->category)
                                <span class="badge bg-warning text-dark">Category: {{ $group->category }}</span>
                            @else
                                <span class="badge bg-secondary">No Category</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-light text-dark fw-bold">
                                ⚙️ Group Settings
                            </a>
                            <form action="{{ route('groups.destroy', $group) }}" method="POST"
                                  onsubmit="return confirmGroupDelete('{{ $group->name }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger fw-bold">❌ Delete</button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <h6 class="mb-2 text-muted">👥 Members:</h6>

                        @if ($group->users->isEmpty() && $group->externalMembers->isEmpty())
                            <div class="alert alert-warning mb-0">No members yet.</div>
                        @else
                            <ul class="list-group mb-3">
                                @foreach ($group->users as $user)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                                            <span class="text-muted d-block small">{{ $user->email }}</span>
                                        </div>
                                        <span class="badge bg-secondary">User</span>
                                    </li>
                                @endforeach

                                @foreach ($group->externalMembers as $ext)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $ext->name }}</strong>
                                            <span class="text-muted d-block small">External Member</span>
                                        </div>
                                        <span class="badge bg-info">External</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($group->subcategories && $group->subcategories->count())
                            <h6 class="text-muted">Subcategories:</h6>
                            <ul class="list-group">
                                @foreach ($group->subcategories as $sub)
                                    <li class="list-group-item">
                                        <i class="bi bi-tags"></i> {{ $sub->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($groups->isEmpty())
                <div class="alert alert-info text-center">No groups available yet. Create one to get started!</div>
            @endif

            <div class="d-flex justify-content-center mt-4">
                {{ $groups->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function confirmGroupDelete(groupName) {
        return confirm(`⚠️ Are you sure you want to delete the group "${groupName}"?\nThis action cannot be undone.`);
    }
</script>
@endsection
