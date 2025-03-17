@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📆 Monthly Attendance Report ({{ $currentMonth }})</h2>

    <!-- Bar Chart for Attendance per Date -->
    <div class="mb-4">
        <canvas id="attendanceBarChart"></canvas>
    </div>

    <!-- Pie Chart for Total Attendance by Category -->
    <div class="mb-4">
        <canvas id="attendancePieChart"></canvas>
    </div>

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
            @forelse($attendanceData as $data)
                <tr>
                    <td>{{ $data->date }}</td>
                    <td>{{ $data->men }}</td>
                    <td>{{ $data->women }}</td>
                    <td>{{ $data->children }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No attendance records for this month.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare the data for the bar chart
    const dates = @json($dates); // Dates for the x-axis
    const menData = @json($menData); // Men attendance data for the y-axis
    const womenData = @json($womenData); // Women attendance data for the y-axis
    const childrenData = @json($childrenData); // Children attendance data for the y-axis

    // Bar Chart Configuration
    const ctxBar = document.getElementById('attendanceBarChart').getContext('2d');
    const attendanceBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: dates, // X-axis labels (dates)
            datasets: [{
                label: '👨 Men',
                data: menData,
                backgroundColor: '#f39c12',
                borderColor: '#e67e22',
                borderWidth: 1
            }, {
                label: '👩 Women',
                data: womenData,
                backgroundColor: '#9b59b6',
                borderColor: '#8e44ad',
                borderWidth: 1
            }, {
                label: '👶 Children',
                data: childrenData,
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pie Chart Configuration for total attendance by category
    const ctxPie = document.getElementById('attendancePieChart').getContext('2d');
    const attendancePieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['👨 Men', '👩 Women', '👶 Children'],
            datasets: [{
                data: [
                    menData.reduce((a, b) => a + b, 0),  // Total men
                    womenData.reduce((a, b) => a + b, 0), // Total women
                    childrenData.reduce((a, b) => a + b, 0) // Total children
                ],
                backgroundColor: ['#f39c12', '#9b59b6', '#3498db'],
                borderColor: ['#e67e22', '#8e44ad', '#2980b9'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endsection
