<!DOCTYPE html>
<html lang="en">
    <!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Attendance System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #b8860b;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            transform: translateX(-250px);
        }

        #content {
            transition: margin-left 0.3s ease-in-out;
            margin-left: 250px;
        }

        #content.expanded {
            margin-left: 0;
        }

        .toggle-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background-color: #8b4513;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
        }

        .nav-link {
            color: white !important;
            padding: 10px;
        }

        .nav-link:hover {
            background-color: #8b4513 !important;
            color: white !important;
        }

        .nav-link.active {
            background-color: #654321 !important;
            font-weight: bold;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #FFDB58 !important;
            color: black !important;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            #content {
                margin-left: 0;
            }
        }
    </style>
</head>
<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<body>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar p-3 text-white">
    <div class="text-center mb-4">
        <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Church Logo" class="img-fluid">
    </div>

    <ul class="nav flex-column">
        @if(auth()->user()->hasRole('Admin'))
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('attendees.index') }}" class="nav-link">👥 Attendees Manager</a></li>
            <li class="nav-item"><a href="{{ route('attendance.mark') }}" class="nav-link">🖊️ Mark Attendance</a></li>
            <li class="nav-item"><a href="{{ route('attendance.view') }}" class="nav-link">📅 View Attendance</a></li>
            <li class="nav-item"><a href="{{ route('admin.visitations.report') }}" class="nav-link">📋 Visitation Report</a></li>
            <li class="nav-item"><a href="{{ route('admin.shepherd-attendance') }}" class="nav-link">📘 All Shepherd Log</a></li>
            <li class="nav-item"><a href="{{ route('groups.index') }}" class="nav-link">👥 Group Manager</a></li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">📊 Reports</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('attendance.report', ['period' => 'weekly']) }}">📊 Weekly Report</a></li>
                    <li><a class="dropdown-item" href="{{ route('attendance.report.monthly') }}">📆 Monthly Report</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.shepherd.report') }}">📉 Shepherd Report</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="{{ route('users.create') }}" class="nav-link">👤 Create User</a></li>


            <li class="nav-item"><a href="{{ route('attendance.logs') }}" class="nav-link">📄 Activity Logs</a></li>
            <li class="nav-item"><a href="{{ route('attendees.import') }}" class="nav-link">📤 Import File</a></li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">⬇️ Download Report</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('attendance.exportExcel') }}">📂 Export Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('attendance.exportPDF') }}">📄 Export PDF</a></li>
                </ul>
            </li>

            <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">🔓 Logout</a></li>
        @endif

        @if(auth()->user()->hasRole('Shepherd'))
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('shepherd.attendees') }}" class="nav-link">👥 Assigned Sheep</a></li>
            <li class="nav-item"><a href="{{ route('attendance.mark') }}" class="nav-link">🖊️ Mark Attendance</a></li>
            <li class="nav-item"><a href="{{ route('attendance.view') }}" class="nav-link">📅 View Attendance</a></li>
            <li class="nav-item"><a href="{{ route('shepherd.visitations') }}" class="nav-link">👣 Shepherd Visitation</a></li>
            <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">🔓 Logout</a></li>
        @endif

        @if(auth()->user()->hasRole('Member'))
            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('attendance.mark') }}" class="nav-link">✅ Mark Attendance</a></li>
            <li class="nav-item"><a href="{{ route('attendance.view') }}" class="nav-link">📅 View Attendance</a></li>
            <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">🚪 Logout</a></li>
        @endif
    </ul>
</nav>

<!-- Toggle Button -->
<button id="toggle-btn" class="toggle-btn">&#9776;</button>

<!-- Main Content -->
<main id="content" class="flex-grow-1 p-4">
    @yield('content')
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.getElementById('toggle-btn');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('expanded');
    });

    // Highlight current route
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
</script>

</body>
</html>
