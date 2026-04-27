<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard HadirkuGO 2025</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #1e3a8a;
            --secondary: #3b82f6;
            --accent: #10b981;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --card-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.3);
            --text-main: #1e293b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        .hero-section {
            background: linear-gradient(rgba(30, 58, 138, 0.9), rgba(30, 58, 138, 0.8)),
                url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200') center/cover;
            padding: 4rem 1rem;
            text-align: center;
            color: white;
            margin-bottom: -3rem;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .hero-section img {
            width: 180px;
            margin-bottom: 1.5rem;
            filter: brightness(0) invert(1);
        }

        .hero-title {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-weight: 300;
            opacity: 0.9;
        }

        .leaderboard-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .leaderboard-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 1rem;
        }

        .card-header i {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--primary);
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--primary);
        }

        .rank-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .rank-item:nth-child(even) {
            background: rgba(255, 255, 255, 0.4);
        }

        .rank-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .rank-1 .rank-number {
            background: #ffd700;
            color: #856404;
        }

        .rank-2 .rank-number {
            background: #c0c0c0;
            color: #383d41;
        }

        .rank-3 .rank-number {
            background: #cd7f32;
            color: #721c24;
        }

        .rank-other .rank-number {
            background: #f1f5f9;
            color: #64748b;
        }

        .user-name {
            font-weight: 500;
            flex-grow: 1;
            font-size: 1rem;
        }

        .stat-value {
            font-weight: 700;
            color: var(--primary);
            text-align: right;
            font-size: 1.1rem;
        }

        .stat-unit {
            font-size: 0.75rem;
            font-weight: 400;
            color: #64748b;
            margin-left: 4px;
        }

        .footer {
            margin-top: 4rem;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }

        .shine {
            position: relative;
            overflow: hidden;
        }

        .shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to bottom right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0) 40%,
                    rgba(255, 255, 255, 0.4) 50%,
                    rgba(255, 255, 255, 0) 60%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .leaderboard-card {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <section class="hero-section">
        <img src="https://drive.pastibisa.app/1731550618_67355d9aecbf5.png" alt="HadirkuGO Logo">
        <h1 class="hero-title">Hall of Fame 2025</h1>
        <p class="hero-subtitle">Apresiasi bagi pejuang kehadiran dengan dedikasi tertinggi.</p>
    </section>

    <div class="container" style="position: relative; z-index: 10;">
        <div class="row g-4">
            <!-- Attendance Leaderboard -->
            <div class="col-lg-6">
                <div class="leaderboard-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-check"></i>
                        <h2>Top 10 Kehadiran (Hari)</h2>
                    </div>

                    <div class="rank-list">
                        @foreach($topAttendance as $index => $user)
                            <div
                                class="rank-item {{ $index < 3 ? 'rank-' . ($index + 1) : 'rank-other' }} {{ $index == 0 ? 'shine' : '' }}">
                                <div class="rank-number">{{ $index + 1 }}</div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="stat-value">{{ number_format($user->total_days) }} <span
                                        class="stat-unit">Hari</span></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Duration Leaderboard -->
            <div class="col-lg-6">
                <div class="leaderboard-card">
                    <div class="card-header">
                        <i class="fas fa-clock"></i>
                        <h2>Top 10 Jam Kerja</h2>
                    </div>

                    <div class="rank-list">
                        @foreach($topDuration as $index => $user)
                            <div
                                class="rank-item {{ $index < 3 ? 'rank-' . ($index + 1) : 'rank-other' }} {{ $index == 0 ? 'shine' : '' }}">
                                <div class="rank-number">{{ $index + 1 }}</div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="stat-value">{{ number_format($user->total_hours, 2) }} <span
                                        class="stat-unit">Jam</span></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HadirkuGO. Data per {{ \Carbon\Carbon::now()->format('d F Y') }}.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>