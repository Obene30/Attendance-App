@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-4"></div>

    <h2 class="text-center mb-4">📊 Welcome To MSCI Armley Attendance System</h2>

    <!-- Stat Cards -->
    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card text-white bg-primary shadow p-4 h-100">
                <h4>👥 Total Attendees</h4>
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

    <div class="py-4"></div>

    <!-- Charts -->
    <div class="row g-4">
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
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = ["Total Attendees", "Weekly Attendance", "Monthly Attendance"];
        const data = [{{ $totalAttendees }}, {{ $weeklyAttendance }}, {{ $monthlyAttendance }}];

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
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
    });
</script>

{{-- Responsive Chart Styling --}}
<style>
    .chart-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
    }

    @media (max-width: 576px) {
        .chart-wrapper {
            height: 250px;
        }

        .card-header {
            font-size: 1rem;
        }
    }
</style>
@endsection
