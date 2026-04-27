<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="HadirkuGO is a digital attendance platform using QR code technology for seamless attendance management.">
    <meta name="keywords" content="HadirkuGO, Digital Attendance, QR Code, Attendance Management, SSO, Platform Kehadiran">
    <meta name="author" content="HadirkuGO Team">
    <meta property="og:title" content="HadirkuGO - Digital Attendance Platform with QR Code">
    <meta property="og:description" content="HadirkuGO is a platform for managing attendance using QR code technology, making attendance processes simpler and more efficient.">
    <meta property="og:image" content="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <title>@yield('title', '')</title>

    <!-- Favicon -->
    <link rel="icon" href="https://drive.pastibisa.app/1731549860_67355aa46d47e.jpg" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS for layout and responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for icons -->
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
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
        }
        .navbar {
            background-color: #1e3a8a; /* Navy color */
            padding: 1rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 1.2rem;
            margin: 0;
            font-weight: 600;
        }
        .navbar .icon-bell {
            color: white;
            font-size: 1.2rem;
        }
        .navbar img {
            width: 100px; /* Adjust the width for the slim logo */
            height: auto;
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<div class="navbar">
    <!-- Logo Slim on the left side -->
    <img src="https://drive.pastibisa.app/1731550618_67355d9aecbf5.png" alt="Logo Slim">
    <i class="fas fa-bell icon-bell"></i>
</div>

<!-- Main Content -->
<div class="container mt-3">
    @yield('content')
</div>

<!-- Bootstrap JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
