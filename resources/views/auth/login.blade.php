<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Church Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body { 
            background-color: #ffdb58; 
            font-family: Arial, sans-serif; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0; 
            overflow: hidden;
        }

        .login-container { 
            width: 400px; 
            padding: 30px; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); 
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .logo img {
            width: 100px;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        .footer a {
            color: #d4a017;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            color: #b8860b;
            text-decoration: underline;
        }

        /* Splash Screen Loader */
        .splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(3px);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .splash-screen.show {
            display: flex;
        }

        .splash-screen .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #ffdb58;
        }

        .splash-screen p {
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- Splash Loader -->
    <div class="splash-screen" id="splashLoader">
        <div class="spinner-border" role="status"></div>
        <p>Signing you in...</p>
    </div>

    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('images/PHOTO-2025-03-04-20-14-01-removebg-preview.png') }}" alt="Church Logo">
        </div>

        <h2 class="text-center">Login</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-3 text-start">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3 text-start">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Login</button>

            <p class="footer">
                Powered by <a href="https://www.tech-premier.com" target="_blank">Tech Premier</a>
            </p>
        </form>
    </div>

    <!-- JS: Show Splash on Login -->
    <script>
        const loginForm = document.getElementById('loginForm');
        const splash = document.getElementById('splashLoader');

        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            splash.classList.add('show');

            setTimeout(() => {
                loginForm.submit();
            }, 1500); // Optional delay to show splash
        });
    </script>
</body>
</html>
