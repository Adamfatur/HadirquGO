<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Rekapan absensi tim secara publik untuk HadirquGO.">
    <meta name="keywords" content="HadirquGO, Absensi, Rekap, Tim, QR Code">
    <meta name="author" content="HadirquGO">
    <meta property="og:title" content="Rekapan Absensi {{ $team->name }} | HadirquGO">
    <meta property="og:description" content="Pantau kehadiran tim {{ $team->name }} secara real-time.">
    <meta property="og:image" content="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <title>Absensi {{ $team->name }} | HadirquGO</title>
    
    <!-- Favicon -->
    <link rel="icon" href="https://drive.pastibisa.app/1731549860_67355aa46d47e.jpg" type="image/x-icon">

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-500: #64748b;
            --gray-800: #1e293b;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
        }

        /* Modern Navbar/Hero */
        .header-bg {
            background: #ffffff;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .brand-logo img {
            height: 40px;
            width: auto;
        }

        /* Cards */
        .card-modern {
            background: #ffffff;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .card-modern:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .bg-icon-primary { background: var(--primary-light); color: var(--primary); }
        .bg-icon-success { background: #dcfce7; color: var(--success); }
        .bg-icon-warning { background: #fef3c7; color: var(--warning); }

        /* Filter Tabs */
        .nav-pills-custom {
            background: var(--gray-100);
            padding: 0.25rem;
            border-radius: 12px;
            display: inline-flex;
        }

        .nav-pills-custom .nav-link {
            border-radius: 10px;
            color: var(--gray-500);
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
        }

        .nav-pills-custom .nav-link.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Table Styles */
        .table-custom {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background: var(--gray-100);
            color: var(--gray-500);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-custom tbody tr td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.95rem;
            background: #ffffff;
            transition: background 0.15s;
        }

        .table-custom tbody tr:hover td {
            background: #f8fafc;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        /* Avatar */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Status Badges */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 99px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: currentColor;
        }

        .status-success { background: #dcfce7; color: #166534; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-danger { background: #fee2e2; color: #991b1b; }
        .status-secondary { background: #f1f5f9; color: #64748b; }

        /* Typography */
        .text-label {
            color: var(--gray-500);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .text-value {
            color: var(--dark);
            font-weight: 700;
        }

        /* Timeline for Mobile */
        .timeline-item {
            position: relative;
            padding-left: 1.5rem;
            border-left: 2px solid var(--gray-200);
            padding-bottom: 1.5rem;
        }
        
        .timeline-item:last-child {
            border-left-color: transparent;
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 2px var(--primary-light);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }

        @media (max-width: 768px) {
            .table-responsive-stack tr {
                display: flex;
                flex-direction: column;
                background: #ffffff;
                margin-bottom: 1rem;
                border: 1px solid var(--gray-200);
                border-radius: 12px;
                padding: 1rem;
            }
            .table-responsive-stack td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: none !important;
                padding: 0.5rem 0 !important;
            }
            .table-responsive-stack thead {
                display: none;
            }
            .table-responsive-stack td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--gray-500);
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="header-bg">
    <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
        <a href="{{ url('/') }}" class="brand-logo">
            <img src="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png" alt="HadirquGO Logo">
        </a>
        <div class="nav nav-pills-custom">
            <a class="nav-link {{ $range === 'today' ? 'active' : '' }}" href="{{ route('public.team.attendance.recap', ['team_unique_id' => $team->team_unique_id, 'range' => 'today']) }}">
                Hari Ini
            </a>
            <a class="nav-link {{ $range === 'week' ? 'active' : '' }}" href="{{ route('public.team.attendance.recap', ['team_unique_id' => $team->team_unique_id, 'range' => 'week']) }}">
                Minggu Ini
            </a>
        </div>
    </div>
</header>

<main class="container py-5">
    <!-- Title Section -->
    <div class="row align-items-end mb-5">
        <div class="col-lg-8">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill fw-semibold">
                <i class="fa-solid fa-users me-1"></i> {{ $team->name }}
            </span>
            <h1 class="fw-bold display-6 mb-2 text-dark">Rekapan Absensi</h1>
            <p class="text-muted mb-0 fs-5">
                Data kehadiran tim untuk periode <span class="fw-semibold text-dark">{{ $startDate->translatedFormat('d M Y') }}</span> s/d <span class="fw-semibold text-dark">{{ $endDate->translatedFormat('d M Y') }}</span>
            </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
             <div class="position-relative">
                <input type="text" id="searchInput" class="form-control form-control-lg ps-5 rounded-pill border-0 shadow-sm" placeholder="Cari nama anggota...">
                <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card-modern p-4 h-100">
                <div class="stat-icon bg-icon-primary">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="text-label mb-1">Total Anggota</div>
                <div class="text-value fs-2">{{ $summaries[$dates[0]->toDateString()]['total'] ?? 0 }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
            </div>
        </div>
        
        @php
            $todayKey = \Carbon\Carbon::today()->toDateString();
            if (!isset($summaries[$todayKey])) {
                $todayKey = $dates[0]->toDateString();
            }
            $todaySummary = $summaries[$todayKey] ?? ['present' => 0, 'pending_checkout' => 0];
        @endphp

        <div class="col-md-4">
            <div class="card-modern p-4 h-100">
                <div class="stat-icon bg-icon-success">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="text-label mb-1">Hadir Hari Ini</div>
                <div class="text-value fs-2">{{ $todaySummary['present'] }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern p-4 h-100">
                <div class="stat-icon bg-icon-warning">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="text-label mb-1">Belum Checkout</div>
                <div class="text-value fs-2">{{ $todaySummary['pending_checkout'] }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
            </div>
        </div>
    </div>

    <!-- Attendance Lists -->
    @foreach ($dates as $date)
        @php
            $dateKey = $date->toDateString();
            $rows = $recordsByDate[$dateKey] ?? [];
            $summary = $summaries[$dateKey] ?? ['present' => 0, 'total' => 0, 'pending_checkout' => 0];
            $isToday = $date->isToday();
        @endphp

        <div class="card-modern mb-5">
            <div class="p-4 border-bottom bg-white d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-3 d-flex flex-column align-items-center justify-content-center px-3 py-2" style="min-width: 60px;">
                        <span class="fw-bold fs-4 lh-1">{{ $date->format('d') }}</span>
                        <span class="small text-uppercase fw-semibold" style="font-size: 0.65rem;">{{ $date->format('M') }}</span>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $date->translatedFormat('l') }}</h5>
                        <small class="text-muted">Kehadiran: {{ $summary['present'] }} dari {{ $summary['total'] }}</small>
                    </div>
                </div>
                @if($isToday)
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-circle fa-xs me-1"></i> Hari Ini
                    </span>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-custom w-100 table-responsive-stack">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 35%;">Anggota Tim</th>
                            <th style="width: 15%;">Jam Masuk</th>
                            <th style="width: 15%;">Jam Pulang</th>
                            <th style="width: 15%;">Durasi</th>
                            <th style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            @php
                                $checkin = $row['checkin'];
                                $checkout = $row['checkout'];
                                $duration = $row['duration_minutes'];
                                $status = $row['status'];
                                $tags = $row['tags'] ?? [];
                                $durationText = $duration ? floor($duration / 60) . 'j ' . ($duration % 60) . 'm' : '-';
                                
                                $statusClass = 'status-secondary';
                                
                                if ($status === 'Lengkap') {
                                    $statusClass = 'status-success';
                                } elseif ($status === 'Belum checkout') {
                                    $statusClass = 'status-warning';
                                } elseif ($status === 'Tidak hadir') {
                                    $statusClass = 'status-danger';
                                }
                            @endphp
                            <tr class="search-item">
                                <td data-label="#">{{ $index + 1 }}</td>
                                <td data-label="Anggota Tim">
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $avatar = $row['user']->avatar;
                                            $isExternal = Str::startsWith($avatar, ['http://', 'https://']);
                                            $avatarUrl = $isExternal ? $avatar : asset($avatar);
                                            $showAvatar = $avatar && ($isExternal || file_exists(public_path($avatar)));
                                        @endphp

                                        @if($showAvatar)
                                            <img src="{{ $avatarUrl }}" alt="{{ $row['user']->name }}" class="avatar-circle" referrerpolicy="no-referrer">
                                        @else
                                            <div class="avatar-initial">
                                                {{ substr($row['user']->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark user-name">{{ $row['user']->name }}</div>
                                            <div class="d-flex gap-1 mt-1">
                                                @if(in_array('morning_person', $tags))
                                                    <span class="badge bg-warning text-warning-emphasis bg-opacity-25 rounded-pill" style="font-size: 0.65rem;">
                                                        <i class="fa-solid fa-sun me-1"></i> Morning Person
                                                    </span>
                                                @endif
                                                @if(in_array('late_person', $tags))
                                                    <span class="badge bg-secondary text-secondary-emphasis bg-opacity-25 rounded-pill" style="font-size: 0.65rem;">
                                                        <i class="fa-solid fa-person-walking me-1"></i> Paling Akhir
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Jam Masuk" class="fw-medium font-monospace">
                                    {{ $checkin ? $checkin->format('H:i') : '-' }}
                                </td>
                                <td data-label="Jam Pulang" class="fw-medium font-monospace">
                                    {{ $checkout ? $checkout->format('H:i') : '-' }}
                                </td>
                                <td data-label="Durasi" class="text-muted fw-medium">
                                    {{ $durationText }}
                                </td>
                                <td data-label="Status">
                                    <div class="badge-status {{ $statusClass }}">
                                        {{ $status }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa-regular fa-calendar-xmark fs-1 mb-3 text-gray-300"></i>
                                        <p class="mb-0">Tidak ada data absensi untuk tanggal ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</main>

<footer class="bg-white border-top py-4 mt-auto">
    <div class="container text-center">
        <p class="mb-0 text-muted small">
            &copy; {{ date('Y') }} <strong>HadirquGO</strong>. All rights reserved. <br>
            <span class="text-gray-400">Timezone: Asia/Jakarta (WIB)</span>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchItems = document.querySelectorAll('.search-item');

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            searchItems.forEach(item => {
                const name = item.querySelector('.user-name').textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>
