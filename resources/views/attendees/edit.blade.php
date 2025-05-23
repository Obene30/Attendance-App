@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h3 class="mb-0">✏️ Edit Attendee</h3>
            <a href="{{ route('attendees.index') }}" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body bg-light">
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('attendees.update', $attendee->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="full_name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $attendee->full_name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $attendee->address) }}" required>
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label fw-semibold">Birthday</label>
                    <input type="text" class="form-control" id="dob" name="dob" value="{{ old('dob', $attendee->dob) }}" placeholder="MM-DD" required>
<small class="text-muted">Format: MM-DD (e.g. 04-21)</small>

                </div>

                <div class="mb-3">
                    <label for="sex" class="form-label fw-semibold">Sex</label>
                    <select class="form-select" id="sex" name="sex" required>
                        <option value="Male" {{ $attendee->sex == 'Male' ? 'selected' : '' }}>👦 Male</option>
                        <option value="Female" {{ $attendee->sex == 'Female' ? 'selected' : '' }}>👧 Female</option>
                    </select>
                
                <div class="mb-4">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="Adults" {{ $attendee->category == 'Adults' ? 'selected' : '' }}>🧑 Adults</option>
                        <option value="Children <13" {{ $attendee->category == 'Children <13' ? 'selected' : '' }}>🧒 Children &lt;13</option>
                    </select>
                </div>
                

                <div class="text-end">
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-save"></i> Update Attendee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .card {
        border-radius: 15px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    button.btn-warning:hover {
        background-color: #e0a800;
    }
</style>
@endsection
