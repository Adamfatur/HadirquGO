@extends('layout.lecturer')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 List of Teams 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">
                Check the list of teams with their total members, total points, and total duration!
            </p>
        </div>

        <!-- Daftar Tim Section -->
        <div class="card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #f9f9fb;">
            <div class="card-header bg-transparent border-0">
                <h2 class="mb-0 text-secondary font-weight-bold" style="color: #1e3a8a;">
                    Teams - All-Time
                </h2>
            </div>
            <div class="card-body">
                @if($teamData->isEmpty())
                    <div class="text-center">
                        <i class="fas fa-trophy text-primary" style="font-size: 4rem;"></i>
                        <h3 class="text-dark fw-bold mt-3">{{ __('No Data Available') }}</h3>
                        <p class="text-muted mt-2">
                            It looks like there is no team data available.<br>
                            Try checking back later.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="text-white" style="background-color: #1e3a8a;">
                            <tr>
                                <th>{{ __('Rank') }}</th>
                                <th>Team Name</th>
                                <th>Total Members</th>
                                <th>{{ __('Total Points') }}</th>
                                <th>{{ __('Total Duration') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teamData as $index => $team)
                                <tr class="border-bottom animate__animated animate__fadeIn clickable-row"
                                    data-href="{{ route('lecturer.teamsrank.member-rankings', $team['team']->team_unique_id) }}">
                                    <!-- Ranking Column -->
                                    <td class="text-center fw-bold" style="font-size: 1.25rem;">
                                        @if($index == 0)
                                            🥇
                                        @elseif($index == 1)
                                            🥈
                                        @elseif($index == 2)
                                            🥉
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>

                                    <!-- Team Name -->
                                    <td class="fw-bold text-dark">
                                        <div class="d-flex align-items-center">
                                            {{ $team['team']->name }}
                                            @if($index < 3)
                                                <span class="label-top label-top-{{ $index + 1 }} ms-2 animate__animated animate__flash animate__infinite">
                                                    <!-- Teks "TOP" dihapus -->
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Total Members -->
                                    <td>
                                        {{ $team['total_members'] }}
                                    </td>

                                    <!-- Total Points -->
                                    <td>
                                        {{ $team['total_points'] }}
                                    </td>

                                    <!-- Total Duration -->
                                    <td>
                                        {{ $team['total_duration_formatted'] }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Animasi hover pada tombol */
        .btn-hover-animate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-hover-animate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Animasi pada select dropdown */
        .select-animate {
            transition: all 0.3s ease;
        }
        .select-animate:hover {
            transform: scale(1.02);
        }

        /* Animasi fadeIn untuk card dan tabel */
        .animate__animated.animate__fadeIn {
            animation-duration: 0.5s;
        }

        /* Responsif untuk tombol navigasi */
        @media (max-width: 768px) {
            .d-flex.flex-wrap {
                gap: 8px;
            }
            .btn {
                padding: 8px 12px;
                font-size: 14px;
            }
        }

        /* Efek hover pada baris tabel */
        .table-hover tbody tr:hover {
            background-color: #e6f0ff;
            transition: background-color 0.3s ease;
        }

        /* Gaya untuk label TOP */
        .label-top {
            display: inline-block;
            padding: 0.25em 0.75em;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            color: white;
            text-align: center;
        }

        /* Warna dan animasi untuk TOP 1 */
        .label-top-1 {
            background-color: #ffd700; /* Emas */
            animation: shine 2s infinite;
        }

        /* Warna dan animasi untuk TOP 2 */
        .label-top-2 {
            background-color: #c0c0c0; /* Perak */
            animation: shine 2s infinite;
        }

        /* Warna dan animasi untuk TOP 3 */
        .label-top-3 {
            background-color: #cd7f32; /* Perunggu */
            animation: shine 2s infinite;
        }

        /* Animasi berkilau */
        @keyframes shine {
            0%, 100% {
                box-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
            }
            50% {
                box-shadow: 0 0 20px rgba(255, 215, 0, 0.9);
            }
        }

        /* Gaya untuk baris yang dapat diklik */
        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .clickable-row:hover {
            background-color: #e6f0ff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangani klik pada baris
            const rows = document.querySelectorAll('.clickable-row');
            rows.forEach(row => {
                row.addEventListener('click', function() {
                    window.location.href = this.dataset.href;
                });
            });
        });
    </script>
@endpush
