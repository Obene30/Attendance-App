@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Attendee</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendees.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
    <label for="dob" class="form-label">Birthday (Month & Day Only)</label>
    <input type="text" class="form-control" id="dob" name="dob" placeholder="MM-DD" required>
</div>

        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select class="form-select" id="sex" name="sex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
                <option value="Children">Children</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Attendee</button>
    </form>
</div>
@endsection
