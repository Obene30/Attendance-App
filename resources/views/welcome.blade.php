<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | MSCI Church Attendance</title>
    v<link rel="icon" type="image/png" href="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}">

    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            background-color: #ffdb58;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.8s ease-out;
        }

        #splash-screen.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .hero {
            flex: 1 0 auto;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0,0,0,0.4)),
                        url('{{ asset('/images/natalia-y--hrKlTEauoI-unsplash copy.jpg') }}') center center / cover no-repeat;
            animation: backgroundPan 20s linear infinite;
            background-size: 110%;
            color: #fff;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        @keyframes backgroundPan {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #ffdb58;
            text-shadow: 1px 1px 2px #000;
            animation: bounce 1.5s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .hero p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #fff9c4;
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
            background-color: #fce275;
            transform: translateY(-2px);
        }

        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .logo-container img {
            width: 70px;
        }

        .footer {
            background-color: #ffdb58;
            padding: 12px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #333;
            flex-shrink: 0;
        }

        .footer a {
            color: #0b6ef0;
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .hero {
                padding: 1.5rem;
            }
            .hero h1 {
                font-size: 1.8rem;
            }
            .btn-custom {
                padding: 10px 20px;
                font-size: 1rem;
            }
            .logo-container img {
                width: 50px;
            }
        }
    </style>
</head>
<body>

    <!-- Splash Screen -->
    <div id="splash-screen">
        <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Loading Logo" style="width: 90px; margin-bottom: 20px;">
        <div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="display: none;">
        <!-- Hero Section -->
        <div class="hero">
            <div class="logo-container">
                <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Church Logo">
            </div>

            <h1>Welcome to MSCI Armley</h1>
            <p>Church management system made simple and impactful.</p>
            <a href="{{ route('login') }}" class="btn-custom">🚀 Get Started</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            Powered by <a href="https://www.tech-premier.com" target="_blank">Tech Premier</a>
        </div>
    </div>

    <!-- Splash Script -->
    <script>
        window.addEventListener('load', () => {
            const splash = document.getElementById('splash-screen');
            const main = document.querySelector('.main-content');
            setTimeout(() => {
                splash.classList.add('fade-out');
                setTimeout(() => {
                    splash.style.display = 'none';
                    main.style.display = 'flex';
                }, 800);
            }, 1500);
        });
    </script>

</body>
</html>
