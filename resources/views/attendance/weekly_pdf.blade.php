<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <h2>Weekly Attendance Report</h2>

    <!-- Bar Chart: Attendance per Date -->
    <canvas id="attendanceBarChart"></canvas>
    <br>

    <!-- Pie Chart: Total Attendance by Category -->
    <canvas id="attendancePieChart"></canvas>

    <script>
        // Get the data passed from the controller
        var weeklyData = @json($chartData);

        // Bar Chart Configuration
        var ctxBar = document.getElementById('attendanceBarChart').getContext('2d');
        var attendanceBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: weeklyData.labels, // X-axis: Dates
                datasets: [{
                    label: '👨 Men',
                    data: weeklyData.male, // Y-axis: Men attendance
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Blue
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: '👩 Women',
                    data: weeklyData.female, // Y-axis: Women attendance
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Red
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: '👶 Children',
                    data: weeklyData.children, // Y-axis: Children attendance
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Green
                    borderColor: 'rgba(75, 192, 192, 1)',
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

        // Pie Chart Configuration: Total attendance by category
        var ctxPie = document.getElementById('attendancePieChart').getContext('2d');
        var attendancePieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['👨 Men', '👩 Women', '👶 Children'], // Pie chart categories
                datasets: [{
                    data: [
                        weeklyData.total_men,  // Total Men
                        weeklyData.total_women, // Total Women
                        weeklyData.total_children // Total Children
                    ],
                    backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

</body>
</html>
