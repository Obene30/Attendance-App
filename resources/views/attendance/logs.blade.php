@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark text-center fw-bold">
            <h4 class="mb-0">📄 Activity Logs</h4>
        </div>

        <div class="card-body bg-light">
            @if($logs->isEmpty())
                <div class="alert alert-info text-center">No logs available.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-warning text-dark">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration + ($logs->firstItem() - 1) }}</td>
                                    <td>{{ $log->user ? $log->user->first_name : 'Unknown User' }}</td>
                                    <td>
                                        <span class="badge bg-dark text-light">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .table thead th {
        font-weight: bold;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.75em;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-light {
        background-color: #fdfcf5 !important;
    }

    .card-header h4 {
        margin: 0;
    }
</style>
@endsection
