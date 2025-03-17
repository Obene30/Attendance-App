<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Attendance System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #b8860b;
            position: fixed;
            top: 0;
            left: 0;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        /* Initially hide sidebar on mobile */
        .sidebar-hidden {
            transform: translateX(-250px); /* Sidebar hidden */
        }

        /* Sidebar Item Hover Effect */
        .nav-item .nav-link {
            padding: 10px;
            border-radius: 5px;
            color: white !important;
        }

        .nav-item .nav-link:hover {
            background-color: #8b4513 !important;
            color: white !important;
        }

        .nav-item .nav-link.active {
            background-color: #654321 !important;
            font-weight: bold;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background-color: #8b4513;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
        }

        /* Mobile View Adjustments */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-250px); /* Sidebar hidden by default */
            }
            #content {
                margin-left: 0; /* No space on the left for mobile */
            }

            /* Ensure the sidebar takes up full width when visible on mobile */
            .sidebar-visible #sidebar {
                transform: translateX(0); /* Show sidebar */
            }
        }

        /* Desktop View Adjustments */
        @media (min-width: 769px) {
            #sidebar {
                transform: translateX(0); /* Sidebar is visible by default */
            }
            #content {
                margin-left: 250px; /* Push content to the right */
            }
        }

        /* Table and Chart Responsiveness */
        .table-responsive, .chart-container {
            overflow-x: auto;
            width: 100%;
        }
    </style>
</head>
<body class="d-flex bg-light">

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar d-flex flex-column p-3 text-white">
        <div class="text-center mb-4">
            <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Church Logo" class="img-fluid">
        </div>

        <ul class="nav flex-column">
            @if(auth()->user()->hasRole('Admin'))
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link" id="dashboard-link">🏠 Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('attendees.index') }}" class="nav-link">👥 Manage Attendees</a></li>
                <li class="nav-item"><a href="{{ route('attendance.mark') }}" class="nav-link">✅ Mark Attendance</a></li>
                <li class="nav-item"><a href="{{ route('attendance.view') }}" class="nav-link">📅 View Attendance</a></li>
                <li class="nav-item"><a href="{{ route('attendance.report', ['period' => 'weekly']) }}" class="nav-link">📊 Weekly Report</a></li>
                <li class="nav-item"><a href="{{ route('attendance.report.monthly') }}" class="nav-link">📆 Monthly Report</a></li>
                <li class="nav-item"><a href="{{ route('attendance.logs') }}" class="nav-link">📄 Activity Logs</a></li>

                <!-- Export Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="exportDropdown" role="button" data-bs-toggle="dropdown">
                        📤 Export
                    </a>
                    <ul class="dropdown-menu custom-dropdown">
                        <li><a class="dropdown-item" href="{{ route('attendance.exportExcel') }}">📂 Export Excel</a></li>
                        <li><a class="dropdown-item" href="{{ route('attendance.exportPDF') }}">📄 Export PDF</a></li>
                    </ul>
                </li>

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
        const toggleBtn = document.getElementById('toggle-btn');
        const content = document.getElementById('content');

        // Handle Toggle Button Click
        toggleBtn.addEventListener('click', () => {
            // Toggle sidebar visibility on mobile
            sidebar.classList.toggle('sidebar-hidden');
            
            // On mobile, adjust the content margin to compensate for sidebar visibility
            if (sidebar.classList.contains('sidebar-hidden')) {
                content.style.marginLeft = '0';
            } else {
                content.style.marginLeft = '250px';
            }
        });

        // Highlight active menu item based on the current route
        document.querySelectorAll('.nav-link').forEach(link => {
            // Check if the current link's URL matches the current page's URL
            if (link.href === window.location.href) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    </script>

</body>
</html>
