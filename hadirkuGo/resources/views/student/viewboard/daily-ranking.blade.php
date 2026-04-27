@extends('layout.student')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white section-title" style="font-size: 1.8rem;">🏆 Top 50 Rankings by Level 🏆</h2>
            <p class="text-light section-subtitle" style="font-size: 1rem; margin-top: -5px;">
                Check your performance and rankings based on <strong>user level</strong>!
            </p>
        </div>

        <div class="d-flex justify-content-center mb-4 flex-wrap gap-2">
            <a href="{{ route('student.viewboard.top-levels') }}"
               class="btn btn-primary btn-custom btn-hover-animate {{ request()->routeIs('student.viewboard.top-levels') ? 'active' : '' }}">
                Top Levels
            </a>
            <a href="{{ route('student.viewboard.top-sessions') }}"
               class="btn btn-primary btn-custom btn-hover-animate {{ request()->routeIs('student.viewboard.top-sessions') ? 'active' : '' }}">
                Top Sessions
            </a>
            <a href="{{ route('student.viewboard.top-duration') }}"
               class="btn btn-custom btn-primary btn-hover-animate {{ request()->routeIs('student.viewboard.top-duration') ? 'active' : '' }}">
                Top Duration
            </a>
            <a href="{{ route('student.viewboard.top-locations') }}"
               class="btn btn-primary btn-custom btn-hover-animate {{ request()->routeIs('student.viewboard.top-locations') ? 'active' : '' }}">
                Top Locations
            </a>
            <a href="{{ route('student.viewboard.top-points') }}"
               class="btn btn-primary btn-custom btn-hover-animate {{ request()->routeIs('student.viewboard.top-points') ? 'active' : '' }}">
                Top Points
            </a>
        </div>

        @if(session('message'))
            <div class="alert alert-info text-center">
                {{ session('message') }}
            </div>
        @endif

        @if(isset($rankings) && count($rankings) > 0)
            <div class="card ranking-card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #ffffff;">
                <div class="card-header ranking-card-header bg-transparent border-0">
                    <h2 class="mb-0 text-secondary font-weight-bold card-title" style="color: #1e3a8a;">Top 50 Rankings by Level</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table ranking-table table-hover align-middle mb-0">
                            <thead class="text-white table-header" style="background-color: #1e3a8a;">
                            <tr>
                                <th class="text-center" style="width: 8%;">#</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Total Points') }}</th>
                                <th>{{ __('Level') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rankings as $index => $item)
                                <tr class="ranking-row animate__animated animate__fadeIn">
                                    <td class="text-center fw-bold ranking-number" style="font-size: 1.25rem;">
                                        @php
                                            if($index == 0) {
                                                $rankDisplay = '🥇';
                                            } elseif($index == 1) {
                                                $rankDisplay = '🥈';
                                            } elseif($index == 2) {
                                                $rankDisplay = '🥉';
                                            } else {
                                                $rankDisplay = $index + 1;
                                            }
                                        @endphp
                                        <div>
                                            {{ $rankDisplay }}
                                            @if($item['rank_change'] > 0)
                                                <small class="d-block text-success" style="font-size: 0.75rem;">
                                                    ▲ {{ $item['rank_change'] }}
                                                </small>
                                            @elseif($item['rank_change'] < 0)
                                                <small class="d-block text-danger" style="font-size: 0.75rem;">
                                                    ▼ {{ abs($item['rank_change']) }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="d-flex align-items-center user-info">
                                        <img src="{{ $item['avatar'] }}"
                                             alt="Avatar"
                                             class="rounded-circle me-2 avatar-image"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <span class="fw-bold text-dark username">{{ $item['name'] }}</span>
                                    </td>

                                    <td class="points">{{ $item['total_points'] }}</td>

                                    <td class="level-column">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $item['level_image'] }}"
                                                 alt="Level Image"
                                                 class="rounded-circle level-image"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <span class="level-badge">{{ $item['level'] }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        /* Header Styling */
        .section-title {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6); /* Efek bayangan teks */
            position: relative;
            display: inline-block;
            padding-bottom: 0.2em; /* Ruang untuk garis bawah */
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 0.15em; /* Ketebalan garis bawah */
            background: linear-gradient(to right, #ffc107, #ff9800); /* Gradasi warna garis bawah */
            border-radius: 0.5em;
        }

        .section-subtitle {
            color: #d3d3d3; /* Warna subtitle lebih lembut */
        }

        /* Custom Button Styling */
        .btn-custom {
            background: linear-gradient(135deg, #007bff, #6610f2); /* Contoh gradasi biru-ungu */
            border: none;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #6610f2, #007bff); /* Gradasi terbalik saat hover */
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.3);
        }

        .btn-custom.active {
            background: #ffc107; /* Warna kuning untuk tombol aktif */
            color: #1e3a8a;
            font-weight: bold;
        }

        /* Ranking Card Styling */
        .ranking-card {
            background: #f8f9fa; /* Latar belakang card lebih lembut */
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .ranking-card-header {
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef; /* Garis pemisah header */
        }

        .card-title {
            color: #343a40; /* Warna judul card lebih gelap */
        }

        /* Table Styling */
        .ranking-table {
            border-collapse: separate;
            border-spacing: 0 8px; /* Spasi antar baris */
        }

        .table-header th {
            padding: 0.75rem 1rem; /* Padding header tabel */
            text-align: center;
            border-bottom: none;
            font-weight: bold;
        }

        .ranking-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            background: white;
            border-top: none;
            border-bottom: none;
        }

        .ranking-table tbody tr:first-child td {
            border-top: none; /* Hilangkan border atas baris pertama */
        }

        .ranking-table tbody tr:last-child td {
            border-bottom: none; /* Hilangkan border bawah baris terakhir */
        }

        .ranking-table tbody tr:hover td {
            background-color: #f0f0f0; /* Efek hover baris */
            transition: background-color 0.2s ease-in-out;
        }

        .ranking-row {
            border-radius: 15px;
            overflow: hidden; /* Untuk memastikan border-radius bekerja pada hover */
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); /* Shadow ringan untuk baris */
        }

        .ranking-number {
            font-weight: bolder;
            color: #495057; /* Warna nomor ranking */
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .avatar-image {
            width: 45px;
            height: 45px;
            margin-right: 10px;
            border: 2px solid #ddd; /* Border avatar */
        }

        .username {
            color: #212529; /* Warna username */
            font-weight: 500;
        }

        .points {
            font-weight: bold;
            color: #007bff; /* Warna poin */
        }

        .level-column {
            text-align: center;
        }

        /* Level Badge Styling */
        .level-badge {
            font-size: 1rem; /* Ukuran font badge */
            background: linear-gradient(to bottom, #ffdb58, #f4c430); /* Gradasi emas */
            color: #343a40; /* Warna teks badge lebih gelap */
            padding: 0.55em 0.85em; /* Padding badge */
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); /* Shadow badge */
            letter-spacing: 0.5px; /* Spasi antar huruf */
            transition: transform 0.2s ease-in-out;
        }

        .level-badge:hover {
            transform: scale(1.05); /* Efek scale saat hover */
        }

        .level-image {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #f8f9fa; /* Border gambar level */
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); /* Shadow gambar level */
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Animasi tambahan saat halaman dimuat (optional)
        document.addEventListener('DOMContentLoaded', function () {
            const animatedElements = document.querySelectorAll('.animate__animated');
            animatedElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = 1;
                }, index * 100);
            });

            // Efek hover dinamis untuk baris tabel
            const tableRows = document.querySelectorAll('.ranking-row');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.style.boxShadow = '0 5px 15px rgba(0,0,0,0.15)';
                    row.style.transform = 'translateY(-2px)';
                });
                row.addEventListener('mouseleave', () => {
                    row.style.boxShadow = '0 1px 3px rgba(0,0,0,0.05)';
                    row.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        // Animasi tambahan saat halaman dimuat (optional)
        document.addEventListener('DOMContentLoaded', function () {
            const animatedElements = document.querySelectorAll('.animate__animated');
            animatedElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = 1;
                }, index * 100);
            });

            // Efek hover dinamis untuk baris tabel
            const tableRows = document.querySelectorAll('.ranking-row');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.style.boxShadow = '0 5px 15px rgba(0,0,0,0.15)';
                    row.style.transform = 'translateY(-2px)';
                });
                row.addEventListener('mouseleave', () => {
                    row.style.boxShadow = '0 1px 3px rgba(0,0,0,0.05)';
                    row.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endpush