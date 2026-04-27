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
    <title>Leaderboard | HadirkuGO</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            padding: 20px;
        }

        /* Header */
        .leaderboard-title {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            font-size: 2.5rem;
            font-weight: 600;
        }

        .leaderboard-title i {
            color: #fbbf24;
            margin-right: 10px;
        }

        /* Table Styling */
        .table-responsive {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .table {
            margin-bottom: 0;
            color: white;
        }

        .table thead th {
            background-color: rgba(30, 64, 175, 0.8);
            color: white;
            font-weight: 500;
            border: none;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .table td, .table th {
            padding: 12px 15px;
            vertical-align: middle;
            border: none;
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fbbf24;
        }

        /* Medal Icons */
        .medal-icon {
            font-size: 1.2rem;
        }

        .medal-gold {
            color: #ffd700;
        }

        .medal-silver {
            color: #c0c0c0;
        }

        .medal-bronze {
            color: #cd7f32;
        }

        /* MVP Badge */
        .mvp-badge {
            display: inline-block;
            background: linear-gradient(45deg, #ffd700, #fbbf24);
            color: white;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        /* Footer */
        footer {
            background-color: rgba(30, 64, 175, 0.8);
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }

        footer .footer-icons {
            margin-bottom: 10px;
        }

        footer .footer-icons i {
            font-size: 1.5rem;
            margin: 0 10px;
            color: #fbbf24;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        footer .footer-icons i:hover {
            transform: scale(1.2);
            color: white;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .leaderboard-title {
                font-size: 2rem;
            }

            .table td, .table th {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="leaderboard-title">
        <i class="fas fa-trophy"></i> Leaderboard | HadirkuGO
    </h1>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Avatar</th>
                <th>Name</th>
                <th>Duration</th>
                <th>Sessions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data['rankings'] as $index => $user)
                @php
                    $hours = floor($user->total_duration / 60);
                    $minutes = $user->total_duration % 60;
                @endphp
                <tr>
                    <td>
                        @if ($index == 0)
                            <i class="fas fa-crown medal-icon medal-gold"></i>
                        @elseif ($index == 1)
                            <i class="fas fa-medal medal-icon medal-silver"></i>
                        @elseif ($index == 2)
                            <i class="fas fa-medal medal-icon medal-bronze"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td><img src="{{ $user->user->avatar }}" alt="{{ $user->user->name }}'s avatar" class="avatar"></td>
                    <td>
                        <div class="name-badge">
                            <span class="name">{{ $user->user->name }}</span>
                            @if ($index == 0)
                                <span class="mvp-badge">MVP</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $hours }} hrs {{ $minutes }} mins</td>
                    <td>{{ $user->session_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<footer>
    
    <p>Leaderboard updated in real-time by <strong>HadirkuGO</strong>.</p>
    <p>&copy; {{ date('Y') }} HadirkuGO Team. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- FontAwesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>