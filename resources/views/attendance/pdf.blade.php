<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Attendance Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 150px; /* Adjust the logo size */
            height: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Add the logo here -->
        <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Original copy">
    </div>
    
    <h2 style="text-align: center;">MSCI Armley Church Attendance Report</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Attendee</th>
                <th>Date</th>
                <th>Status</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->id }}</td>
                    <td>{{ $attendance->attendee->full_name }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->status }}</td>
                    <td>{{ $attendance->attendee->category }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
