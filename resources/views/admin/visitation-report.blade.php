@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold text-center">
            <h4 class="mb-0">📋 All Visitation Reports</h4>
        </div>

        <div class="card-body bg-light">

            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.visitations.report') }}" class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" name="shepherd" class="form-control" placeholder="Search by Shepherd name" value="{{ request('shepherd') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">-- Filter by Status --</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark fw-semibold">🔍 Filter</button>
                </div>
            </form>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            {{-- Visitation Table --}}
            @if($visitations->isEmpty())
                <div class="alert alert-info text-center">No visitation reports available.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center table-hover">
                        <thead class="table-warning text-dark">
                            <tr>
                                <th>Attendee</th>
                                <th>Category</th>
                                <th>Shepherd</th>
                                <th>Address</th>
                                <th>Admin Comment</th>
                                <th>Shepherd Comment</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitations as $visitation)
                                <tr>
                                    <td>{{ $visitation->attendee->full_name }}</td>
                                    <td>{{ $visitation->attendee->category }}</td>
                                    <td>{{ $visitation->shepherd->first_name }} {{ $visitation->shepherd->last_name }}</td>
                                    <td>{{ $visitation->attendee->address }}</td>
                                    <td>{{ $visitation->admin_comment ?? '—' }}</td>
                                    <td>{{ $visitation->shepherd_comment ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $visitation->status === 'Completed' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $visitation->status }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($visitation->updated_at)->format('d M, Y h:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $visitations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
</style>
@endsection
