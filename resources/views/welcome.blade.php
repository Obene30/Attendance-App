<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Church Attendance</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffdb58; /* Golden Yellow Background */
            margin: 0;
            padding: 0;
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 100vh;
            background: url('{{ asset('images/doug-vos-N3kdvLgARuw-unsplash.jpg') }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .hero::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay for better text visibility */
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            font-size: 50px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-custom {
            background: #ffdb58;
            color: black;
            padding: 12px 24px;
            font-size: 18px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #f0f8ff;
        }

        /* Carousel */
        .carousel img {
            height: 70vh;
            object-fit: cover;
        }

        /* Logo */
        .logo-container {
            text-align: center;
            margin-top: 20px;
        }
        .logo-container img {
            width: 100px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Church Logo">
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to MSCI Armley Church Attendance System</h1>
            <p>Track and manage church attendance efficiently.</p>
            <div class="divider"></div>
            <a href="{{ route('login') }}" class="btn-custom">Get Started</a>
            <div class="divider"></div>
            <div class="divider"></div>
            <div class="divider"></div>
            <p class="footer">
                Powered by <a href="https://www.tech-premier.com" target="_blank">Tech Premier LTD</a>
            </p>
        </div>
    </div>



</body>
</html>
