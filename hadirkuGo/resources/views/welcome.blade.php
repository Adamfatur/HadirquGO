<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- SEO Meta Tags -->
    <meta name="description" content="HadirquGO is a state-of-the-art digital attendance platform based on QR code technology, transforming daily attendance into a seamless, gamified, and rewarding experience." />
    <meta name="keywords" content="HadirquGO, Digital Attendance, QR Code, Attendance Management, Gamification, Employee Tracking, Student Attendance" />
    <meta name="author" content="Alphabet Incubator" />
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="HadirquGO - Next-Gen Digital Attendance Platform" />
    <meta property="og:description" content="Revolutionize your attendance process with lightning-fast QR scanning, real-time analytics, and gamified rewards." />
    <meta property="og:image" content="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png" />
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="HadirquGO - Next-Gen Digital Attendance Platform" />
    <meta property="twitter:description" content="Revolutionize your attendance process with lightning-fast QR scanning, real-time analytics, and gamified rewards." />
    <meta property="twitter:image" content="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png" />

    <title>HadirquGO - Modern Digital Attendance Platform</title>

    <link rel="icon" href="https://drive.pastibisa.app/1731549860_67355aa46d47e.jpg" type="image/x-icon" />
    <link rel="apple-touch-icon" href="https://drive.pastibisa.app/1737344039_678dc427e611b.png">
    <meta name="theme-color" content="#1e40af">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #facc15;
            --accent: #3b82f6;
            --dark: #1e293b;
            --dark-lighter: #334155;
            --light: #f8fafc;
            --text-main: #334155;
            --text-muted: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.2);
            --gradient-primary: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            --gradient-dark: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        body { font-family: 'Inter', sans-serif; color: var(--text-main); background-color: var(--light); overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; font-weight: 700; }
        .text-gradient { background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* Navbar */
        .navbar { background: var(--glass-bg); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.3); padding: 15px 0; transition: all 0.3s ease; }
        .navbar.scrolled { box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 10px 0; background: rgba(255,255,255,0.95); }
        .navbar-brand img { height: 40px; transition: transform 0.3s ease; }
        .navbar-brand:hover img { transform: scale(1.05); }
        .nav-link { font-weight: 500; color: var(--text-main) !important; margin: 0 10px; position: relative; transition: color 0.3s; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 50%; background: var(--primary); transition: all 0.3s ease; transform: translateX(-50%); }
        .nav-link:hover { color: var(--primary) !important; }
        .nav-link:hover::after { width: 100%; }
        .btn-nav-login { background: var(--primary); color: white !important; border-radius: 50px; padding: 8px 25px !important; font-weight: 600; box-shadow: 0 4px 15px rgba(30,64,175,0.3); transition: all 0.3s ease; }
        .btn-nav-login:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30,64,175,0.4); }

        /* Hero */
        .hero { position: relative; padding: 160px 0 100px; background: radial-gradient(circle at top right, rgba(30,64,175,0.1), transparent 50%), radial-gradient(circle at bottom left, rgba(59,130,246,0.05), transparent 50%); overflow: hidden; }
        .hero::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%231e40af" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); animation: moveBg 60s linear infinite; z-index: -1; }
        @keyframes moveBg { 0% { transform: translate(0, 0); } 100% { transform: translate(50px, 50px); } }
        .hero h1 { font-size: 4rem; line-height: 1.1; letter-spacing: -1.5px; margin-bottom: 1.5rem; color: var(--dark); }
        .hero p { font-size: 1.25rem; color: var(--text-muted); margin-bottom: 2.5rem; max-width: 90%; }
        .btn-hero { background: var(--gradient-primary); color: white; border: none; padding: 16px 40px; border-radius: 50px; font-size: 1.15rem; font-weight: 600; box-shadow: 0 10px 25px rgba(59,130,246,0.3); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: inline-flex; align-items: center; gap: 10px; text-decoration: none; }
        .btn-hero:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 15px 35px rgba(30,64,175,0.4); color: white; }
        .btn-hero i { transition: transform 0.3s ease; }
        .btn-hero:hover i { transform: translateX(5px); }

        .btn-hero-secondary { background: white !important; color: var(--dark) !important; box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important; }
        .btn-hero-secondary:hover { background: #f1f5f9 !important; color: var(--primary) !important; box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        .btn-hero-secondary i { color: var(--primary) !important; }
        .btn-hero-secondary:hover i { color: var(--primary-dark) !important; }

        /* Floating Cards */
        .floating-card { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.5); padding: 15px 20px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); position: absolute; animation: float 6s ease-in-out infinite; display: flex; align-items: center; gap: 15px; z-index: 2; }
        .card-1 { top: 10%; right: 5%; animation-delay: 0s; }
        .card-2 { bottom: 15%; left: -5%; animation-delay: 2s; }
        .card-3 { top: 45%; right: -10%; animation-delay: 4s; }
        .floating-card .icon-box { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white; }
        .floating-card .bg-blue { background: var(--primary); }
        .floating-card .bg-pink { background: #ec4899; }
        .floating-card .bg-yellow { background: var(--secondary); }
        .floating-card h4 { margin: 0; font-size: 1rem; color: var(--dark); font-weight: 700; }
        .floating-card p { margin: 0; font-size: 0.8rem; color: var(--text-muted); }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
        .main-image { width: 100%; max-width: 450px; filter: drop-shadow(0 30px 50px rgba(30,64,175,0.2)); animation: floatSlow 8s ease-in-out infinite; display: block; margin: 0 auto; }
        @keyframes floatSlow { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(2deg); } }

        /* Sections */
        .stats-section { padding: 80px 0; background: white; position: relative; z-index: 10; box-shadow: 0 -20px 40px rgba(0,0,0,0.02), 0 20px 40px rgba(0,0,0,0.02); }
        .stat-item { padding: 20px; transition: transform 0.3s ease; }
        .stat-item:hover { transform: translateY(-5px); }
        .stat-number { font-size: 3.5rem; font-weight: 800; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 15px; display: block; }
        .stat-label { font-size: 0.9rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; display: block; }

        .features { padding: 100px 0; background: var(--light); }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header h2 { font-size: 2.8rem; margin-bottom: 20px; color: var(--dark); }
        .section-header p { font-size: 1.1rem; color: var(--text-muted); max-width: 700px; margin: 0 auto; }
        .feature-box { background: white; border-radius: 24px; padding: 40px; height: 100%; transition: all 0.4s ease; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 10px 30px rgba(0,0,0,0.03); position: relative; z-index: 1; display: flex; flex-direction: column; align-items: flex-start; }
        .feature-box:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(30,64,175,0.1); border-color: rgba(30,64,175,0.2); }
        .feature-icon-wrapper { width: 60px; height: 60px; border-radius: 18px; background: rgba(30,64,175,0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.4s ease; }
        .feature-box:hover .feature-icon-wrapper { background: var(--primary); color: white; transform: scale(1.1) rotate(5deg); }
        .feature-box h3 { font-size: 1.35rem; margin-bottom: 15px; color: var(--dark); }

        .how-it-works { padding: 100px 0; background: white; }
        .step-number { width: 65px; height: 65px; background: var(--gradient-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; margin: 0 auto 20px; box-shadow: 0 10px 20px rgba(59,130,246,0.3); }

        .comparison { padding: 100px 0; background: var(--dark-lighter); color: white; position: relative; overflow: hidden; }
        .comparison-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px); border-radius: 24px; padding: 40px; height: 100%; transition: all 0.4s ease; }
        .comparison-box.active { background: var(--gradient-primary); border: none; transform: scale(1.05); box-shadow: 0 20px 50px rgba(0,0,0,0.4); z-index: 2; }
        .comparison-list { list-style: none; padding: 0; }
        .comparison-list li { margin-bottom: 15px; font-size: 1.05rem; display: flex; gap: 12px; align-items: flex-start; }

        .cta-area { padding: 80px 0; background: var(--light); }
        .cta-card { background: var(--gradient-dark); border-radius: 40px; padding: 70px 30px; text-align: center; color: white; position: relative; overflow: hidden; }
        .cta-card h2 { font-size: 3rem; margin-bottom: 20px; }
        .btn-cta-large { background: white; color: var(--dark); font-size: 1.15rem; font-weight: 700; padding: 18px 45px; border-radius: 50px; text-decoration: none; transition: all 0.3s ease; display: inline-block; position: relative; z-index: 2; }

        footer { background: var(--dark); color: #94a3b8; padding: 80px 0 30px; }
        .footer-logo { height: 40px; margin-bottom: 25px; filter: brightness(0) invert(1); }
        .footer-links h5 { color: white; margin-bottom: 25px; }
        .footer-links a { color: #94a3b8; text-decoration: none; transition: all 0.3s; }
        .footer-links a:hover { color: var(--secondary); }
        .social-icons a { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); color: white; margin-right: 10px; transition: all 0.3s; }

        @media (max-width: 1200px) {
            .hero h1 { font-size: 3.5rem; }
            .stat-number { font-size: 3rem; }
        }

        @media (max-width: 991px) {
            .hero { padding: 140px 0 80px; text-align: center; }
            .hero h1 { font-size: 3rem; }
            .hero p { margin: 0 auto 2.5rem; }
            .main-image { max-width: 380px; margin-top: 40px; }
            .floating-card { display: none; }
            .hero .d-flex { justify-content: center; }
            .comparison-box.active { transform: scale(1); margin-top: 30px; }
            .stat-number { font-size: 2.8rem; }
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .section-header h2 { font-size: 2.2rem; }
            .stat-number { font-size: 2.4rem; }
            .stat-label { font-size: 0.8rem; }
            .cta-card h2 { font-size: 2.2rem; }
            .cta-card { padding: 50px 20px; border-radius: 25px; }
            .feature-box { padding: 30px 20px; }
        }

        @media (max-width: 576px) {
            .hero h1 { font-size: 2.2rem; }
            .stat-number { font-size: 2.2rem; }
            .stat-item { padding: 15px 5px; }
            .stat-label { font-size: 0.75rem; letter-spacing: 1px; }
            .navbar-brand img { height: 32px; }
            .btn-hero { width: 100%; justify-content: center; padding: 14px 20px; font-size: 1rem; }
        }
    </style>
</head>
<body>

    @include('landing-page.partials.navbar')
    @include('landing-page.partials.hero')
    @include('landing-page.partials.stats')
    @include('landing-page.partials.features')
    @include('landing-page.partials.how-it-works')
    @include('landing-page.partials.comparison')
    @include('landing-page.partials.cta')
    @include('landing-page.partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({ duration: 800, once: true, offset: 100 });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) { navbar.classList.add('scrolled'); } 
            else { navbar.classList.remove('scrolled'); }
        });
    </script>
</body>
</html>