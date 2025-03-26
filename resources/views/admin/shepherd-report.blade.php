@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4 mb-4 text-center">👨 Shepherd Report</h2>

    @if($records->isEmpty())
        <div class="alert alert-info text-center">No attendance records found.</div>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
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
                                <span class="badge {{ $record->status === 'Present' ? 'bg-success' : 'bg-danger' }}">
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
        <h4 class="text-center mb-4">📊 Attendance Summary</h4>

        <div class="row g-4">
            <!-- Line Chart -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white fw-semibold">
                        📈 Attendance Trend
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white fw-semibold">
                        📊 Attendance Ratio
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
    canvas {
        width: 100% !important;
        height: auto !important;
    }

    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }
</style>

@endsection
