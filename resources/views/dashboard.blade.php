@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-4"></div>
    {{-- Welcome Message --}}
    <h3 class="mb-4 text-center">👋 Welcome back, {{ Auth::user()->first_name }}!</h3>

    <h2 class="text-center mb-4">MSCI Armley Attendance Software</h2>

    <!-- Stat Cards -->
    <div class="row g-4">
        <h4 class="text-left mb-4">🏠 Dashboard</h5>
        <div class="col-12 col-md-4">
            <div class="card text-white bg-primary shadow p-4 h-100">
                <h4>👥 Membership</h4>
                <p class="fs-3">{{ $totalAttendees }}</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-white bg-success shadow p-4 h-100">
                <h4>📅 Weekly Attendance</h4>
                <p class="fs-3">{{ $weeklyAttendance }}</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-white bg-warning shadow p-4 h-100">
                <h4>📆 Monthly Attendance</h4>
                <p class="fs-3">{{ $monthlyAttendance }}</p>
            </div>
        </div>
    </div>


{{-- Shepherd & Admin Overview --}}
<div class="mt-5">
    <h5 class="text-center mb-4">👥 Shepherd Insight</h5>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark fw-semibold">
            <i class="bi bi-person-check-fill"></i> Assigned Attendees Summary
        </div>
        <div class="card-body bg-light">
            @if($assignedUsers->isEmpty())
                <div class="alert alert-warning text-center mb-0">
                    No Shepherds or Admins with assigned attendees found.
                </div>
            @else
            <div class="table-responsive">
                <table id="overviewTable" class="table table-bordered align-middle table-striped">
                    <thead class="table-warning text-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Assigned Sheep</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedUsers as $index => $user)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $user->assigned_attendees_count }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        $('#overviewTable').DataTable({
                            paging: true,
                            ordering: true,
                            info: false,
                            language: {
                                search: "🔍 Filter:"
                            }
                        });
                    });
                </script>
                
            </div>
            
            @endif
        </div>
    </div>
</div>




    <div class="py-4"></div>

    <!-- Charts -->
    <div class="row g-4">
        <h5 class="text-center mb-4">📊 Attendance Summary</h5>
        <!-- Bar Chart -->
       
        <div class="col-12 col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-semibold">
                    📊 Attendance Bar Chart
                </div>
                <div class="card-body chart-wrapper">
                    <canvas id="attendanceBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Line Chart -->
        <div class="col-12 col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white fw-semibold">
                    📈 Attendance Trend Line Chart
                </div>
                <div class="card-body chart-wrapper">
                    <canvas id="attendanceLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 📊 Charts Section --}}
    <div class="mt-5">
        <h5 class="text-center mb-4">📊 Attendees Overview (Present/Absent)</h5>

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
                        📉 Attendance Ratio
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

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = ["Total Attendees", "Weekly Attendance", "Monthly Attendance"];
        const data = [{{ $totalAttendees }}, {{ $weeklyAttendance }}, {{ $monthlyAttendance }}];

        const summaryLabels = @json($dates);
        const presentData = @json($statusGroups['Present']);
        const absentData = @json($statusGroups['Absent']);
        const totalPresent = {{ $totalPresent }};
        const totalAbsent = {{ $totalAbsent }};

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        };

        // Bar Chart
        new Chart(document.getElementById("attendanceBarChart").getContext("2d"), {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Attendance Data",
                    data: data,
                    backgroundColor: ["#007bff", "#28a745", "#ffc107"]
                }]
            },
            options: chartOptions
        });

        // Line Chart
        new Chart(document.getElementById("attendanceLineChart").getContext("2d"), {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Attendance Trend",
                    data: data,
                    borderColor: "#007bff",
                    backgroundColor: "rgba(0, 123, 255, 0.1)",
                    fill: true,
                    tension: 0.3
                }]
            },
            options: chartOptions
        });

        // Summary Line Chart
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: summaryLabels,
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
            options: chartOptions
        });

        // Pie Chart
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
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>

{{-- Responsive Chart Styling --}}
<style>
    .chart-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
    }
    .chart-container {
        position: relative;
        width: 100%;
        height: 300px;
    }
    canvas {
        width: 100% !important;
        height: auto !important;
    }
    @media (max-width: 576px) {
        .chart-wrapper, .chart-container {
            height: 250px;
        }
        .card-header {
            font-size: 1rem;
        }
    }
</style>
@endsection
