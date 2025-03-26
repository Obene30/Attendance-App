@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold">
            <h4 class="mb-0">➕ Create New Group</h4>
        </div>

        <div class="card-body bg-light">
            {{-- Success message --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>⚠️ Please fix the following errors:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('groups.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Group Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter unique group name" required>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        ✅ Create Group
                    </button>
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">
                        ← Back to Groups
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Optional Styling --}}
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
