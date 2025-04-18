@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">📥 Import Attendees</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('attendees.import') }}" enctype="multipart/form-data">

        @csrf
        <div class="mb-3">
            <label class="form-label">Choose Excel File</label>
            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
        </div>
        <button type="submit" class="btn btn-warning text-dark fw-bold">📤 Import</button>
    </form>
</div>
@endsection
