@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-4"></div> <!-- Added padding above the title -->
    
    <h2 class="text-center mb-4">📊 Welcome To MSCI Armley Attendance System</h2> <!-- Added bottom margin -->

    <div class="py-2"></div> <!-- Additional spacing for balance -->

    <div class="row g-4"> <!-- Added Bootstrap gutter spacing -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 p-4"> <!-- Increased padding -->
                <h4>👥 Total Attendees</h4>
                <p class="fs-3">{{ $totalAttendees }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 p-4">
                <h4>📅 Weekly Attendance</h4>
                <p class="fs-3">{{ $weeklyAttendance }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3 p-4">
                <h4>📆 Monthly Attendance</h4>
                <p class="fs-3">{{ $monthlyAttendance }}</p>
            </div>
        </div>
    </div>

    <div class="py-3"></div> <!-- Added spacing before charts -->

    <!-- Charts Section -->
    <div class="charts-wrapper">
        <div class="chart-container">
            <canvas id="attendanceBarChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="attendanceLineChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = ["Total Attendees", "Weekly Attendance", "Monthly Attendance"];
        const data = [{{ $totalAttendees }}, {{ $weeklyAttendance }}, {{ $monthlyAttendance }}];

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        };

        function createChart(chartId, type, datasets) {
            let ctx = document.getElementById(chartId).getContext('2d');
            new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: chartOptions
            });
        }

        // Bar Chart
        createChart("attendanceBarChart", "bar", [{
            label: "Attendance Data",
            data: data,
            backgroundColor: ["#007bff", "#28a745", "#ffc107"]
        }]);

        // Line Chart
        createChart("attendanceLineChart", "line", [{
            label: "Attendance Trend",
            data: data,
            borderColor: "#007bff",
            fill: false
        }]);
    });
</script>

<style>
    .charts-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px; /* Increased gap for better spacing */
        width: 100%;
    }

    .chart-container {
        width: 100%;
        max-width: 600px;
        height: 400px;
    }

    canvas {
        width: 100% !important;
        height: auto !important;
    }
</style>
@endsection
