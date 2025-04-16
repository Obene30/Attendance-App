@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark text-center fw-bold">
            <h4 class="mb-0">Shepherd Report</h4>
        </div>

        <div class="card-body bg-light">
            @if(!empty($fallbackMessage))
    <div class="alert alert-warning text-center">{{ $fallbackMessage }}</div>
@endif

            {{-- Filter Form --}}
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<form method="GET" action="{{ route('admin.shepherd.report') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label for="marked_by" class="form-label fw-semibold">Marked By</label>
        <input type="text" name="marked_by" id="marked_by" class="form-control"
            placeholder="Enter shepherd name..." value="{{ request('marked_by') }}">
    </div>
    <div class="col-md-4">
        <label for="date" class="form-label fw-semibold">Date</label>
        <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-warning text-dark fw-semibold"><i class="bi bi-search"></i> Filter</button>
    </div>
</form>

            @if($records->isEmpty())
                <div class="alert alert-info text-center">No attendance records found.</div>
            @else
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-warning text-dark">
                            <tr>
                                <th>Date</th>
                                <th>Attendee</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Marked By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                                    <td>{{ $record->attendee->full_name }}</td>
                                    <td>{{ $record->attendee->category }}</td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 {{ $record->status === 'Present' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                    <td>{{ $record->comment ?? '—' }}</td>
                                    <td>{{ $record->markedBy->first_name }} {{ $record->markedBy->last_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $records->links() }}
                </div>
            @endif

            {{-- 📊 Charts Section --}}
            <div class="mt-5">
                <h5 class="text-center mb-4">📊 Attendance Summary</h5>

                <div class="row g-4">
                    <!-- Line Chart -->
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-warning text-dark fw-semibold">
                                📈 Attendance Trend
                            </div>
                            <div class="card-body chart-container">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-warning text-dark fw-semibold">
                                📈 Attendance Ratio
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="chart-container" style="max-width: 300px;">
                                    <canvas id="pieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- End card --}}
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = @json($dates);
        const presentData = @json($statusGroups['Present']);
        const absentData = @json($statusGroups['Absent']);
        const totalPresent = {{ $totalPresent }};
        const totalAbsent = {{ $totalAbsent }};

        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Present',
                        data: presentData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Absent',
                        data: absentData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [totalPresent, totalAbsent],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>

<style>
    .chart-container {
        position: relative;
        width: 100%;
        height: 300px;
    }

    canvas {
        width: 100% !important;
        height: auto !important;
    }

    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }

    .card {
        border-radius: 12px;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .btn-warning:hover {
        background-color: #e0a800 !important;
    }
</style>
@endsection
