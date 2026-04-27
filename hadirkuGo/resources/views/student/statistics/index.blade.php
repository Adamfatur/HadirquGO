@extends('layout.student')

@section('content')
    <div class="container mt-5">
        <!-- Title -->
        <h2 class="text-center mb-4 fw-bold" style="font-size: 1.8rem; color: #ffffff;">
            <i class="fas fa-chart-bar me-2"></i>Your Attendance Insights
        </h2>

        @if(isset($message))
            <!-- Alert Jika Ada Pesan -->
            <div class="alert alert-info text-center rounded-pill shadow-sm">
                <i class="fas fa-info-circle me-2"></i>{{ $message }}
            </div>
        @else
            <!-- Statistik Utama -->
            <div class="row g-4">
                <!-- 1) Average Times -->
                <div class="col-6 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill">
                        <div class="card-body text-center">
                            <div class="icon-container gradient-primary mx-auto">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">Avg. Check-in</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $statistics->average_checkin_time ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-6 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill">
                        <div class="card-body text-center">
                            <div class="icon-container gradient-warning mx-auto">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">Avg. Check-out</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $statistics->average_checkout_time ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 2) Most & Least Visited Locations -->
                <div class="col-6 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill">
                        <div class="card-body text-center">
                            <div class="icon-container gradient-success mx-auto">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">Most Visited</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $mostFrequentLocation->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-6 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill">
                        <div class="card-body text-center">
                            <div class="icon-container gradient-danger mx-auto">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">Least Visited</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $leastFrequentLocation->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3) Sessions, Streak, Max Check-ins (3-column layout) -->
                <div class="col-4 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill text-center">
                        <div class="card-body">
                            <div class="icon-container gradient-dark mx-auto">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">Sessions</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $statistics->total_attendance_sessions }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill text-center">
                        <div class="card-body">
                            <div class="icon-container gradient-info mx-auto">
                                <i class="fas fa-bolt text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">{{ __('Longest Streak') }}</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $statistics->longest_consecutive_attendance_streak }} days
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex">
                    <div class="card stat-card shadow border-0 flex-fill text-center">
                        <div class="card-body">
                            <div class="icon-container gradient-secondary mx-auto">
                                <i class="fas fa-calendar-plus text-white"></i>
                            </div>
                            <h6 class="card-title mt-3 text-muted">Max Check-ins</h6>
                            <p class="card-text fs-5 text-dark">
                                {{ $statistics->max_checkins_in_one_day }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4) All Visited Locations -->
            <div class="card shadow border-0 mt-4 mb-4">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Visited Locations</h6>
                    @if($allVisitedLocations->isEmpty())
                        <p class="mt-3">No locations visited yet.</p>
                    @else
                        <div class="d-flex flex-wrap justify-content-center mt-3">
                            @foreach($allVisitedLocations as $location)
                                <span class="badge gradient-primary text-white m-1 p-2">
                                    {{ $location->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <style>
        /* General Styles */
        .stat-card {
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .icon-container {
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            color: #fff;
            font-size: 1.5rem;
        }

        /* Gradients */
        .gradient-primary {
            background: linear-gradient(135deg, #6c63ff, #3b82f6);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }
        .gradient-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }
        .gradient-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        .gradient-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }
        .gradient-secondary {
            background: linear-gradient(135deg, #64748b, #334155);
        }
        .gradient-dark {
            background: linear-gradient(135deg, #1e293b, #0f172a);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .stat-card {
                min-height: 140px;
            }
        }
    </style>
@endsection