@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-3"></div>

    <h2 class="text-center mb-4">👥 My Assigned Sheep</h2>

    @if($attendees->isEmpty())
        <div class="alert alert-info text-center">You have no sheep assigned yet.</div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                📝 Attendee Details
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Address</th>
                                <th>Birthday</th>
                                <th>Sex</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendees as $attendee)
                                <tr>
                                    <td>{{ $attendee->full_name }}</td>
                                    <td>{{ $attendee->address }}</td>
                                    <td> {{ \Carbon\Carbon::createFromFormat('d-m', $attendee->dob)->format('d M') }}</td>
                                    <td>{{ $attendee->sex }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $attendee->category }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-center">
                {{ $attendees->links() }}
            </div>
        </div>
    @endif
</div>

{{-- Optional Custom Styling --}}
<style>
    .table th, .table td {
        vertical-align: middle;
    }

    .card-header {
        font-size: 1.1rem;
        padding: 1rem;
    }

    @media (max-width: 576px) {
        .card-header, .card-footer {
            font-size: 0.95rem;
            padding: 0.75rem;
        }

        .table th, .table td {
            font-size: 0.85rem;
        }
    }
</style>
@endsection
