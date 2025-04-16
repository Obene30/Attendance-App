@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold text-center">
            <h4 class="mb-0">📋 Visitation Requests</h4>
        </div>

        <div class="card-body bg-light">
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if($visitations->isEmpty())
                <div class="alert alert-info text-center">No visitation requests assigned to you.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center table-hover">
                        <thead class="table-warning text-dark">
                            <tr>
                                <th>Attendee</th>
                                <th>Category</th>
                                <th>Address</th>
                                <th>Admin Comment</th>
                                <th>Status</th>
                                <th>Shepherd Comment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitations as $visitation)
                                <tr>
                                    <td>{{ $visitation->attendee->full_name }}</td>
                                    <td>{{ $visitation->attendee->category }}</td>
                                    <td>{{ $visitation->attendee->address }}</td>
                                    <td>{{ $visitation->admin_comment ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $visitation->status === 'Completed' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $visitation->status }}
                                        </span>
                                    </td>
                                    <td>{{ $visitation->shepherd_comment ?? '—' }}</td>
                                    <td>
                                        @if($visitation->status !== 'Completed')
                                            <form method="POST" action="{{ route('attendees.visitation.complete', $visitation->attendee) }}">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="shepherd_comment" rows="2" class="form-control form-control-sm mb-2" placeholder="Add your comment..." required>{{ old('shepherd_comment') }}</textarea>
                                                <button type="submit" class="btn btn-success btn-sm w-100">✅ Mark Completed</button>
                                            </form>
                                        @else
                                            <small class="text-muted">Already completed</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-success {
        background-color: #28a745;
        border: none;
    }
    .btn-success:hover {
        background-color: #218838;
    }
</style>
@endsection
