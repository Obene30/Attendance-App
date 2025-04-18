@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">📆 Monthly Attendance Report ({{ $currentMonth }})</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('attendance.report.monthly') }}" class="mb-4 text-center">
        <label for="month" class="form-label fw-semibold">Select Month:</label>
        <input type="month" id="month" name="month" value="{{ $currentMonth }}" class="form-control w-auto d-inline mx-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Charts Section -->
    <div class="row g-4">
        <!-- Bar Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white fw-semibold">
                    📊 Attendance Bar Chart
                </div>
                <div class="card-body">
                    <canvas id="attendanceBarChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Line Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white fw-semibold">
                    📈 Attendance Line Chart
                </div>
                <div class="card-body">
                    <canvas id="attendanceLineChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart & Table -->
    <div class="row g-4 mt-3">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-dark fw-semibold">
                    📈  Attendance Pie Chart
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="attendancePieChart" style="max-width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white fw-semibold">
                    📋 Detailed Attendance by Date
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>🧑 Adults</th>
                                <th>👶 Children</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dates as $index => $date)
                                <tr>
                                    <td>{{ $date }}</td>
                                    <td>{{ $adultData[$index] }}</td>
                                    <td>{{ $childrenData[$index] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dates = @json($dates);
            const adultData = @json($adultData);
            const childrenData = @json($childrenData);
            const totalAdult = {{ $totalAdult }};
            const totalChildren = {{ $totalChildren }};

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
            new Chart(document.getElementById("attendanceBarChart"), {
                type: "bar",
                data: {
                    labels: dates,
                    datasets: [
                        { label: "🧑 Adults", data: adultData, backgroundColor: "#f39c12" },
                        { label: "👶 Children", data: childrenData, backgroundColor: "#3498db" }
                    ]
                },
                options: chartOptions
            });

            // Line Chart
            new Chart(document.getElementById("attendanceLineChart"), {
                type: "line",
                data: {
                    labels: dates,
                    datasets: [
                        { label: "🧑 Adults", data: adultData, borderColor: "#f39c12", fill: false, tension: 0.3 },
                        { label: "👶 Children", data: childrenData, borderColor: "#3498db", fill: false, tension: 0.3 }
                    ]
                },
                options: chartOptions
            });

            // Pie Chart
            new Chart(document.getElementById("attendancePieChart"), {
                type: "pie",
                data: {
                    labels: ["🧑 Adults", "👶 Children"],
                    datasets: [{
                        data: [totalAdult, totalChildren],
                        backgroundColor: ["#f39c12", "#3498db"]
                    }]
                },
                options: { responsive: true }
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
</div>
@endsection
