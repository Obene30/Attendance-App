@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">✏️ Edit Event</h2>
        <a href="{{ route('events.index') }}" class="btn btn-sm btn-secondary">
            ⬅️ Back to Events
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark fw-semibold">
            📝 Update Event Details
        </div>
        <div class="card-body bg-light">
            {{-- Update Form --}}
            <form method="POST" action="{{ route('events.update', $event) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control"
                               value="{{ \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control"
                               value="{{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        💾 Update Event
                    </button>
                </div>
            </form>

            {{-- Delete Form --}}
            <form method="POST" action="{{ route('events.destroy', $event) }}"
                  onsubmit="return confirm('⚠️ Are you sure you want to delete this event?');"
                  class="mt-3 text-end">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger fw-semibold">
                    🗑️ Delete Event
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #f5c518;
        box-shadow: 0 0 0 0.2rem rgba(245, 197, 24, 0.25);
    }

    .btn-warning {
        background-color: #f5c518;
        border-color: #f5c518;
    }

    .btn-warning:hover {
        background-color: #e0b114;
    }
</style>
@endsection
