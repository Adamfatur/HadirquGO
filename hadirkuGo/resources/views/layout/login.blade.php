<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login | HadirkuGo')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SKEWGER5TD"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-SKEWGER5TD');
    </script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #1e3a8a, #2563eb);
            color: #fff;
            overflow: hidden;
        }

        .login-card {
            padding: 3rem 2rem;
            margin: 0 1rem;
            max-width: 450px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card img {
            width: 150px;
            margin-bottom: 2rem;
            animation: logoFadeIn 0.5s ease 0.5s forwards;
        }

        @keyframes logoFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-subheading {
            font-size: 1.2rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 2rem;
            opacity: 0;
            transform: translateY(20px);
            animation: subheadingFadeIn 0.5s ease 0.75s forwards;
        }

        @keyframes subheadingFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-google {
            display: inline-block;
            padding: 0.8rem 1.6rem;
            background-color: #4285F4;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            animation: pulse 2s infinite;
        }

        .btn-google i {
            margin-right: 0.8rem;
        }

        .btn-google:hover {
            background-color: #357ae8;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
            animation: none;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }

            .login-card img {
                width: 120px;
            }

            .login-subheading {
                font-size: 1rem;
            }

            .btn-google {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="login-card">
    @yield('content')
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>