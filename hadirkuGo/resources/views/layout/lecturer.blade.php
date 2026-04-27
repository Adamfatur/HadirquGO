<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Meta Description -->
    <meta name="description" content="HadirquGO is a digital attendance platform using QR code technology for seamless attendance management.">
    <!-- Meta Keywords -->
    <meta name="keywords" content="HadirquGO, Digital Attendance, QR Code, Attendance Management, SSO, Platform Kehadiran">
    <!-- Meta Author -->
    <meta name="author" content="HadirquGO Team">
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="HadirquGO - Digital Attendance Platform with QR Code">
    <meta property="og:description" content="HadirquGO is a platform for managing attendance using QR code technology, making attendance processes simpler and more efficient.">
    <meta property="og:image" content="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <!-- Title -->
    <title>@yield('title', 'HadirquGO - Digital Attendance Platform')</title>
    <!-- Favicon -->
    <link rel="icon" href="https://drive.pastibisa.app/1731549860_67355aa46d47e.jpg" type="image/x-icon">
    <!-- Web App Manifest -->
    <link rel="manifest" href="/manifest.json">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="https://drive.pastibisa.app/1737344039_678dc427e611b.png">
    <!-- Theme Color -->
    <meta name="theme-color" content="#1e3a8a">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
            font-family: 'Poppins', sans-serif;
            background-color: #1e3a8a; /* Solid blue color */
            margin: 0;
            padding-bottom: 60px; /* Space for bottom navigation */
            position: relative;
        }

        /* Background Pattern dengan Ikon FontAwesome */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ffffff' opacity='0.1' d='M0 224h192V32H0v192zM64 96h64v64H64V96zm384-64v192H256V32h192zM448 96h-64v64h64V96zM0 480h192V288H0v192zm64-128h64v64H64v-64zm256 128h192V288H320v192zm128-128h-64v64h64v-64zM256 224h192V32H256v192zm128-128h64v64h-64V96z'/%3E%3C/svg%3E");
            opacity: 0.1; /* Opacity rendah */
            z-index: -1;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% {
                transform: translateY(0) translateX(0);
            }
            50% {
                transform: translateY(-50%) translateX(-50%);
            }
            100% {
                transform: translateY(0) translateX(0);
            }
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
        .nav-tabs {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            padding: 0.6rem 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .nav-tabs a {
            text-align: center;
            color: #b0bec5;
            font-size: 1.3rem;
        }
        .nav-tabs a.active,
        .nav-tabs a:hover {
            color: #1e3a8a; /* Navy color on active */
        }
        .avatar-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
        }

        .bronze-label {
            background: linear-gradient(to bottom, #cd7f32, #b97f3e); /* Bronze gradient */
            color: white;
            padding: 4px 10px;
            font-size: 0.9rem;
            border-radius: 15px;
            font-weight: 600;
            margin-right: 10px;
        }

        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #1e3a8a; /* Warna latar belakang sesuai dengan tema */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .preloader-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .preloader-icon {
            font-size: 80px; /* Ukuran ikon QR Code */
            color: white;
            animation: pulse 1.5s infinite;
        }

        .preloader-text {
            font-size: 24px; /* Ukuran teks */
            color: white;
            font-weight: 600;
            margin-top: 20px;
            animation: fadeInOut 2s infinite;
        }

        /* Ikon Background dengan Animasi */
        .icon-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .icon-background i {
            position: absolute;
            font-size: 24px; /* Ukuran ikon */
            color: rgba(255, 255, 255, 0.7); /* Warna putih dengan opacity 70% */
            animation: float 5s linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(100%) translateX(-50%);
            }
            100% {
                transform: translateY(-100%) translateX(50%);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes fadeInOut {
            0%, 100% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
        }
        /* Rank Shining Animations */
        @keyframes shining-effect {
            0% { left: -100%; }
            20% { left: 100%; }
            100% { left: 100%; }
        }

        .rank-shining {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .rank-shining::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.5) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: skewX(-25deg);
            animation: shining-effect 3s infinite;
            pointer-events: none;
        }

        @keyframes supreme-pulse-effect {
            0% { box-shadow: 0 0 5px rgba(251, 191, 36, 0.6); transform: scale(1); }
            50% { box-shadow: 0 0 15px rgba(251, 191, 36, 0.9); transform: scale(1.02); }
            100% { box-shadow: 0 0 5px rgba(251, 191, 36, 0.6); transform: scale(1); }
        }

        .title-supreme {
            animation: supreme-pulse-effect 2s infinite !important;
            border: 1px solid #fbbf24 !important;
            background: linear-gradient(135deg, #fef3c7, #fbbf24) !important;
            color: #92400e !important;
            font-weight: 800 !important;
        }

        .title-elite-grandmaster {
            background: linear-gradient(135deg, #f3f4f6, #9ca3af) !important;
            border: 1px solid #9ca3af !important;
        }

        .title-grandmaster {
            background: linear-gradient(135deg, #ffedd5, #fcd34d) !important;
            border: 1px solid #cd7f32 !important;
        }

        .title-master-elite {
            background: linear-gradient(135deg, #fef2f2, #ef4444) !important;
            border: 1px solid #ef4444 !important;
        }
    </style>
</head>
<body style="background-color: #1e3a8a; overflow: hidden;">

<!-- Preloader -->
<div id="preloader">
    <!-- Ikon Background dengan Animasi -->
    <div class="icon-background">
        <!-- Ikon-ikon akan di-generate oleh JavaScript -->
    </div>
    <div class="preloader-content">
        <i class="fas fa-qrcode preloader-icon"></i>
        <div class="preloader-text">HadirquGO</div>
    </div>
</div>

<!-- Top Navbar -->
<div class="navbar">
    <!-- Logo Slim on the left side -->
    <img src="https://drive.pastibisa.app/1731550618_67355d9aecbf5.png" alt="Logo Slim">
    <i class="fas fa-bell icon-bell"></i>
</div>

<!-- Main Content -->
<div class="container mt-3">
    @include('partials.lang_switcher')
    @yield('content')
</div>

<!-- Bottom Navigation -->
<div class="nav-tabs">
    <a href="{{ route('lecturer.dashboard') }}" class="{{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
    </a>
    <a href="{{ route('lecturer.teams.index') }}" class="{{ request()->routeIs('lecturer.teams.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
    </a>
    <a href="{{ route('lecturer.calendar') }}" class="{{ request()->routeIs('lecturer.calendar') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i>
    </a>
    <a href="{{ route('lecturer.achievements.index') }}" class="{{ request()->routeIs('lecturer.achievements.*') ? 'active' : '' }}">
        <i class="fas fa-medal"></i>
    </a>
    <a href="{{ route('lecturer.profile.show') }}" class="{{ request()->routeIs('lecturer.profile.*') ? 'active' : '' }}">
        <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Profile" class="avatar-icon">
    </a>
</div>

<!-- Bootstrap JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Menambahkan ikon-ikon random di background preloader
    (function() {
        const iconBackground = document.querySelector('.icon-background');
        if (iconBackground) {
            const icons = ['fa-qrcode', 'fa-calendar-alt', 'fa-bell', 'fa-chart-line', 'fa-medal', 'fa-home'];
            const fragment = document.createDocumentFragment();
            for (let i = 0; i < 15; i++) {
                const icon = document.createElement('i');
                icon.className = `fas ${icons[Math.floor(Math.random() * icons.length)]}`;
                icon.style.left = `${Math.random() * 100}%`;
                icon.style.top = `${Math.random() * 100}%`;
                icon.style.animationDuration = `${Math.random() * 3 + 2}s`;
                fragment.appendChild(icon);
            }
            iconBackground.appendChild(fragment);
        }

        // Sembunyikan preloader setelah halaman benar-benar dimuat
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                // Beri sedikit delay agar transisi terasa smooth
                setTimeout(() => {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }, 500);
                }, 800); // Dikurangi dari 2000ms ke 800ms untuk kecepatan
            }
        });

        // Fallback jika 'load' event terlalu lama
        setTimeout(() => {
            const preloader = document.getElementById('preloader');
            if (preloader && preloader.style.display !== 'none') {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }, 500);
            }
        }, 3000);
    })();
</script>

@auth
    @include('partials.saiqu-chat')
@endauth

@stack('scripts')
</body>
</html>