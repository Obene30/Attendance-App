<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | MSCI Church Attendance</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf6e3;
            color: #333;
        }

        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0,0,0,0.4)), 
                        url('{{ asset('images/doug-vos-N3kdvLgARuw-unsplash.jpg') }}') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .btn-custom {
            background-color: #ffdb58;
            color: #000;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            border: none;
        }

        .btn-custom:hover {
            background-color: #fff8dc;
            transform: translateY(-2px);
        }

        .logo-container {
            position: absolute;
            top: 30px;
            left: 30px;
        }

        .logo-container img {
            width: 90px;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            text-align: center;
            color: #fff;
            font-size: 0.9rem;
            width: 100%;
        }

        .footer a {
            color: #ffdb58;
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .btn-custom {
                padding: 10px 20px;
                font-size: 1rem;
            }

            .logo-container img {
                width: 70px;
            }
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
        <h1>Welcome to MSCI Armley</h1>
        <p>Smart and simple church attendance system.</p>
        <a href="{{ route('login') }}" class="btn-custom">🚀 Get Started</a>

        <div class="footer">
            Powered by <a href="https://www.tech-premier.com" target="_blank">Tech Premier LTD</a>
        </div>
    </div>

</body>
</html>
