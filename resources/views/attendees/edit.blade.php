<!-- resources/views/attendees/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Attendee</h2>
    
    <!-- Display Success Message after Update -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendees.update', $attendee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $attendee->full_name) }}" required>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $attendee->address) }}" required>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $attendee->dob) }}" required>
        </div>

        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select class="form-select" id="sex" name="sex" required>
                <option value="Male" {{ $attendee->sex == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $attendee->sex == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Men" {{ $attendee->category == 'Men' ? 'selected' : '' }}>Men</option>
                <option value="Women" {{ $attendee->category == 'Women' ? 'selected' : '' }}>Women</option>
                <option value="Children" {{ $attendee->category == 'Children' ? 'selected' : '' }}>Children</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Attendee</button>
    </form>
</div>
@endsection
