@extends('layout.lecturer')

@php
use App\Helpers\RankHelper;
@endphp

@section('content')
    <div class="container mt-5">
        <!-- Judul dan Deskripsi -->
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 Member Rankings for Team: {{ $team->name }} 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">
                Check the performance and rankings of members in this team based on <strong>total duration</strong>!
            </p>
        </div>

        <!-- Statistik Tim -->
        <div class="row mb-4 g-3">
            <!-- Total Members -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm h-100 animate__animated animate__fadeInLeft" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-users fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title text-white mb-3">Total Members</h5>
                        <p class="card-text display-4 fw-bold text-white">{{ $totalMembers }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Points -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm h-100 animate__animated animate__fadeInUp" style="background: linear-gradient(135deg, #10b981, #059669); border: none; border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-star fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title text-white mb-3">Total Points</h5>
                        <p class="card-text display-4 fw-bold text-white">{{ number_format($totalPointsAllTime) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Duration (All-Time) -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm h-100 animate__animated animate__fadeInRight" style="background: linear-gradient(135deg, #06b6d4, #0e7490); border: none; border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-clock fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title text-white mb-3">Total Duration</h5>
                        <p class="card-text display-4 fw-bold text-white">
                            @php
                                $hours = floor($totalDurationAllTime / 60);
                                $minutes = $totalDurationAllTime % 60;
                            @endphp
                            {{ $hours }}<small class="fs-6">h</small> {{ $minutes }}<small class="fs-6">m</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Durasi Per Hari -->
        <div class="card shadow-sm mb-5" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Daily Duration Statistics</h5>
            </div>
            <div class="card-body">
                <div id="dailyDurationChart"></div>
            </div>
        </div>

        <!-- Tabel Ranking Member -->
        <div class="card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #f9f9fb;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0 text-secondary font-weight-bold" style="color: #1e3a8a;">
                    Member Rankings - All Time
                </h2>
                <span class="badge bg-primary rounded-pill px-3 py-2">Global Identity Active</span>
            </div>
            <div class="card-body">
                @if(empty($memberRankings))
                    <div class="text-center">
                        <i class="fas fa-trophy text-primary" style="font-size: 4rem;"></i>
                        <h3 class="text-dark fw-bold mt-3">{{ __('No Data Available') }}</h3>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="text-white" style="background-color: #1e3a8a;">
                            <tr>
                                <th class="text-center" style="width: 8%;">#</th>
                                <th>{{ __('Member Identity') }}</th>
                                <th class="text-center">Team Rank</th>
                                <th class="text-end">{{ __('Total Duration') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($memberRankings as $ranking)
                                @php
                                    $user = $ranking['user'];
                                    $globalEntry = $ranking['global_entry'];
                                    $globalRank = $globalEntry ? $globalEntry->current_rank : 999;
                                    $globalStyle = RankHelper::getRankStyle($globalRank, $globalEntry->frame_color ?? null);
                                @endphp
                                <tr class="border-bottom animate__animated animate__fadeIn {{ Auth::id() == $user->id ? 'table-primary' : '' }}">
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="fw-bold" style="font-size: 1.1rem;">
                                                @if($ranking['rank'] == 1) 🥇
                                                @elseif($ranking['rank'] == 2) 🥈
                                                @elseif($ranking['rank'] == 3) 🥉
                                                @else {{ $ranking['rank'] }}
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        <a href="{{ route('lecturer.evaluation.show', ['member_id' => $user->member_id]) }}" class="text-decoration-none d-flex align-items-center">
                                            <div class="position-relative me-3">
                                                <img src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset($user->avatar)) : asset('images/default-avatar.png') }}"
                                                     alt="Avatar"
                                                     class="rounded-circle {{ $globalRank <= 50 ? $globalStyle['class'] : '' }}"
                                                     style="width: 50px; height: 50px; object-fit: cover; {{ $globalRank <= 50 ? $globalStyle['glow'] : 'border: 2px solid #e2e8f0;' }}">
                                                
                                                @if($globalRank <= 3)
                                                    <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $globalStyle['iconColor'] }}" style="font-size: 1rem; transform: translate(-50%, -100%) !important; filter: drop-shadow(0 2px 3px rgba(0,0,0,0.3));"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark d-flex align-items-center" style="font-size: 1.05rem;">
                                                    {{ $user->name }}
                                                    @if($user->id === Auth::user()->id)
                                                        <span class="badge bg-primary ms-2" style="font-size: 0.65rem;">YOU</span>
                                                    @endif
                                                </div>
                                                @if($globalEntry && $globalEntry->title)
                                                    {!! RankHelper::getTitleBadge($globalEntry->title, $globalRank, $globalEntry->frame_color) !!}
                                                @else
                                                    <span class="text-muted small">Standard Member</span>
                                                @endif
                                            </div>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2 fw-bold" style="border-radius: 10px;">
                                            #{{ $ranking['rank'] }}
                                        </span>
                                    </td>

                                    <td class="text-end fw-bold text-primary">
                                        @php
                                            $totalMinutes = $ranking['total_duration'];
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                        @endphp
                                        <span style="font-size: 1.1rem;">{{ $hours }}</span><small>h</small> <span>{{ $minutes }}</span><small>m</small>
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
        .table-hover tbody tr:hover {
            background-color: rgba(30, 58, 138, 0.03);
            transition: background-color 0.2s ease;
        }
        .animate__animated {
            --animate-duration: 0.8s;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                series: [{
                    name: 'Total Duration (Minutes)',
                    data: {!! json_encode(array_values($dailyDurations->toArray())) !!}
                }],
                xaxis: {
                    categories: {!! json_encode(array_keys($dailyDurations->toArray())) !!},
                    labels: {
                        formatter: function (value) {
                            if (!value) return '';
                            const date = new Date(value);
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }
                    }
                },
                yaxis: {
                    title: { text: 'Minutes' }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100]
                    }
                },
                colors: ['#1e3a8a']
            };

            var chart = new ApexCharts(document.querySelector("#dailyDurationChart"), options);
            chart.render();
        });
    </script>
@endpush
