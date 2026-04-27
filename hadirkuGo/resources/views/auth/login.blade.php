<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HadirkuGo - Modern Attendance Management System</title>
    <!-- Meta Description -->
    <meta name="description" content="HadirkuGo is a modern attendance management system designed to streamline your attendance tracking and boost productivity. Login now to experience the future of attendance management.">
    <!-- Meta Keywords -->
    <meta name="keywords" content="HadirkuGo, attendance management, login, productivity, modern system, Raharja University">
    <!-- Canonical URL -->
    <link rel="canonical" href="https://hadirkugo.raharja.ac.id/">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://drive.pastibisa.app/1737344039_678dc427e611b.png">
    <!-- Web App Manifest -->
    <link rel="manifest" href="/manifest.json">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="https://drive.pastibisa.app/1737344039_678dc427e611b.png">
    <!-- Theme Color -->
    <meta name="theme-color" content="#1e3a8a">
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
            position: relative;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95); /* Card lebih terang */
            border-radius: 16px;
            padding: 2.5rem;
            max-width: 400px;
            width: 90%; /* Lebih responsif */
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease;
            margin: 1rem; /* Margin untuk mobile */
            position: relative;
            z-index: 2;
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
            width: 180px; /* Ukuran logo diperbesar */
            margin-bottom: 1.5rem;
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
            font-size: 1rem;
            font-weight: 500;
            color: #666; /* Warna teks lebih gelap untuk kontras */
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

        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.6rem;
            background: linear-gradient(45deg, #ff6f61, #ffcc00); /* Warna gradient menarik */
            color: #fff; /* Teks putih */
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: pulse 2s infinite, shine 3s infinite;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            transform: rotate(45deg);
            animation: shine-effect 3s infinite;
        }

        @keyframes shine-effect {
            0% {
                transform: rotate(45deg) translateX(-200%);
            }
            100% {
                transform: rotate(45deg) translateX(200%);
            }
        }

        .btn-login:active {
            animation: blast 0.5s ease;
        }

        @keyframes blast {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Efek Shock Wave */
        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
        }

        .btn-login:active::after {
            animation: shockwave 0.5s ease-out;
        }

        @keyframes shockwave {
            0% {
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                width: 200px;
                height: 200px;
                opacity: 0;
            }
        }

        .btn-login i {
            margin-right: 0.8rem;
            color: #fff; /* Ikon putih */
        }

        .btn-login:hover {
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

        .login-footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #666; /* Warna teks lebih gelap untuk kontras */
            opacity: 0;
            transform: translateY(20px);
            animation: subheadingFadeIn 0.5s ease 1s forwards;
        }

        /* Background QRCode Icons */
        .background-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .background-icons i {
            position: absolute;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.1); /* Opacity rendah */
            animation: float 10s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100vh) translateX(100vw) rotate(360deg);
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
                width: 85%; /* Lebih responsif di mobile */
            }

            .login-card img {
                width: 140px; /* Ukuran logo lebih kecil di mobile */
            }

            .login-subheading {
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<!-- Background QRCode Icons -->
<div class="background-icons">
    <!-- Icons will be dynamically added here by JavaScript -->
</div>

<!-- Login Card -->
<div class="login-card">
    <!-- Logo -->
    <img src="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png" alt="HadirkuGo Logo">

    <!-- Subheading -->
    <p class="login-subheading">Step into the future of attendance management and unleash your productivity!</p>

    <!-- Login Button -->
    <a href="{{ route('auth.google') }}" class="btn-login">
        <i class="fas fa-rocket"></i> Launch Now
    </a>

    <!-- Footer -->
    <div class="login-footer">
        <p>&copy; {{ date('Y') }} HadirkuGo. All rights reserved.</p>
    </div>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript untuk efek blast dan background icons -->
<script>
    // Efek blast saat tombol diklik
    const btnLogin = document.querySelector('.btn-login');
    btnLogin.addEventListener('click', () => {
        btnLogin.style.animation = 'blast 0.5s ease';
        setTimeout(() => {
            btnLogin.style.animation = '';
        }, 500);
    });

    // Menambahkan icon QRCode secara acak di background
    const backgroundIcons = document.querySelector('.background-icons');
    const iconCount = 20; // Jumlah icon QRCode

    for (let i = 0; i < iconCount; i++) {
        const icon = document.createElement('i');
        icon.classList.add('fas', 'fa-qrcode');
        icon.style.top = `${Math.random() * 100}vh`;
        icon.style.left = `${Math.random() * 100}vw`;
        icon.style.animationDuration = `${10 + Math.random() * 10}s`; // Durasi acak
        backgroundIcons.appendChild(icon);
    }
</script>
</body>
</html>