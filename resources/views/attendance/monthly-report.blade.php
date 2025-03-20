@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">📆 Monthly Attendance Report ({{ $currentMonth }})</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('attendance.report.monthly') }}" class="mb-4 text-center">
        <label for="month">Select Month:</label>
        <input type="month" id="month" name="month" value="{{ $currentMonth }}" class="form-control w-50 d-inline">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Charts Wrapper -->
    <div class="charts-wrapper">
        <div class="chart-container">
            <canvas id="attendanceBarChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="attendancePieChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="attendanceLineChart"></canvas>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>👨 Men</th>
                    <th>👩 Women</th>
                    <th>👶 Children</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dates as $index => $date)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $menData[$index] }}</td>
                        <td>{{ $womenData[$index] }}</td>
                        <td>{{ $childrenData[$index] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dates = @json($dates);
        const menData = @json($menData);
        const womenData = @json($womenData);
        const childrenData = @json($childrenData);

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        };

        function createChart(chartId, type, datasets, labels) {
            let ctx = document.getElementById(chartId).getContext('2d');
            new Chart(ctx, {
                type: type,
                data: { labels: labels, datasets: datasets },
                options: chartOptions
            });
        }

        // Bar Chart
        createChart("attendanceBarChart", "bar", [
            { label: "👨 Men", data: menData, backgroundColor: "#f39c12" },
            { label: "👩 Women", data: womenData, backgroundColor: "#9b59b6" },
            { label: "👶 Children", data: childrenData, backgroundColor: "#3498db" }
        ], dates);
   
        // Pie Chart
        createChart("attendancePieChart", "pie", [{
            data: [@json($totalMen), @json($totalWomen), @json($totalChildren)],
            backgroundColor: ["#f39c12", "#9b59b6", "#3498db"]
        }], ["👨 Men", "👩 Women", "👶 Children"]);

        // Line Chart
        createChart("attendanceLineChart", "line", [
            { label: "👨 Men", data: menData, borderColor: "#f39c12", fill: false },
            { label: "👩 Women", data: womenData, borderColor: "#9b59b6", fill: false },
            { label: "👶 Children", data: childrenData, borderColor: "#3498db", fill: false }
        ], dates);
    });
</script>

<style>
    /* Wrapper to flex charts properly */
    .charts-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        width: 100%;
    }

    /* Chart container adapts to screen size */
    .chart-container {
        width: 100%;
        max-width: 100%;
        height: auto;
        aspect-ratio: 16 / 9; /* Maintains proper ratio */
    }

    canvas {
        width: 100% !important;
        height: auto !important;
    }

    /* Make table scrollable on mobile */
    .table-responsive {
        overflow-x: auto;
    }

    /* Adjust sizes for smaller screens */
    @media (max-width: 768px) {
        .chart-container {
            aspect-ratio: 4 / 3;
        }
    }

    @media (max-width: 480px) {
        .chart-container {
            aspect-ratio: 1 / 1;
        }
    }
</style>

@endsection
