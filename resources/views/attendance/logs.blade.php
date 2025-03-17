@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>📄 Activity Logs</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No logs available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $logs->links() }} <!-- Pagination -->
</div>
@endsection
