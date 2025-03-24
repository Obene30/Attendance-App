@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4 mb-4">➕ Create New Group</h2>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>There were some issues with your input:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('groups.store') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="name" class="form-label fw-semibold">Group Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter unique group name" required>
                </div>

                <button type="submit" class="btn btn-primary">✅ Create Group</button>
                <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">← Back to Groups</a>
            </form>
        </div>
    </div>
</div>
@endsection
