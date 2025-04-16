@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark fw-bold text-center">
            <h4 class="mb-0">📋 All Visitation Reports</h4>
        </div>

        <div class="card-body bg-light">

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.visitations.report') }}" class="row gy-2 gx-3 align-items-end mb-4">
                <div class="col-12 col-md-5">
                    <input type="text" name="shepherd" class="form-control" placeholder="Search by Shepherd name" value="{{ request('shepherd') }}">
                </div>
                <div class="col-12 col-md-4">
                    <select name="status" class="form-select">
                        <option value="">-- Filter by Status --</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto">
                    <button class="btn btn-dark w-100"><i class="bi bi-search"></i> Filter</button>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if($visitations->isEmpty())
                <div class="alert alert-info text-center">No visitation reports available.</div>
            @else
               {{-- Desktop Table: shown on lg+ (≥992px) --}}
<div class="d-none d-lg-block table-responsive">
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
                    <td>{{ \Carbon\Carbon::parse($visitation->updated_at)->format('d M, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Card View: visible on <992px (mobile & small tablets like iPad Mini) --}}
<div class="d-lg-none">
    @foreach($visitations as $visitation)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-2">{{ $visitation->attendee->full_name }}</h5>
                <p><strong>Category:</strong> {{ $visitation->attendee->category }}</p>
                <p><strong>Shepherd:</strong> {{ $visitation->shepherd->first_name }} {{ $visitation->shepherd->last_name }}</p>
                <p><strong>Address:</strong> {{ $visitation->attendee->address }}</p>
                <p><strong>Admin Comment:</strong> {{ $visitation->admin_comment ?? '—' }}</p>
                <p><strong>Shepherd Comment:</strong> {{ $visitation->shepherd_comment ?? '—' }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge {{ $visitation->status === 'Completed' ? 'bg-success' : 'bg-danger' }}">
                        {{ $visitation->status }}
                    </span>
                </p>
                <p class="text-muted mb-0">
                    <small><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($visitation->updated_at)->format('d M, Y h:i A') }}</small>
                </p>
            </div>
        </div>
    @endforeach
</div>


                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $visitations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Optional Styles --}}
<style>
    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.8em;
    }

    .card-title {
        font-size: 1.15rem;
    }

    @media (max-width: 576px) {
        .card-body {
            font-size: 0.95rem;
        }
    }
</style>
@endsection
