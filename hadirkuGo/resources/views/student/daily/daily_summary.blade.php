@extends('layout.student')

@section('title', 'Daily Attendance Summary')

@section('content')
    <div class="container mt-4" style="max-width: 500px;">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="#" class="text-dark">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h4 class="fw-bold text-center mb-0">Daily Attendance</h4>
            <a href="{{ route('student.attendance.history') }}" class="text-dark">
                <i class="fas fa-history"></i>
            </a>
        </div>

        <!-- Weekly Overview Selector -->
        <div class="d-flex justify-content-between mb-4">
            @foreach($weekData as $day)
                <div class="text-center">
                    <div class="progress-circle" style="position: relative; width: 50px; height: 50px; margin: 0 auto;">
                        <svg class="circle" viewBox="0 0 36 36">
                            <path
                                    class="circle-bg"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            ></path>
                            <path
                                    class="circle-progress"
                                    stroke-dasharray="{{ $day['progress'] }}, 100"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            ></path>
                        </svg>
                        <div class="progress-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.75rem;">{{ $day['day'] }}</div>
                    </div>
                    <small>{{ $day['date'] }}</small>
                </div>
            @endforeach
        </div>

        <!-- Summary Section -->
        <div class="summary-card p-4 mb-4 shadow-sm rounded">
            <div class="text-center mb-3">
                <h5 class="fw-bold text-secondary">Today's Summary</h5>
            </div>
            <div class="d-flex justify-content-around">
                <div class="text-center">
                    <p class="fw-bold" style="color: #1e3a8a; font-size: 1.2rem;">{{ $totalLocations }}</p>
                    <small>Locations</small>
                </div>
                <div class="text-center">
                    <p class="fw-bold" style="color: #1e3a8a; font-size: 1.2rem;">{{ intdiv($totalDuration, 60) }} hrs {{ $totalDuration % 60 }} mins</p>
                    <small>Total Duration</small>
                </div>
                <div class="text-center">
                    <p class="fw-bold" style="color: #1e3a8a; font-size: 1.2rem;">{{ $averageDuration }} mins</p>
                    <small>Avg. Time per Location</small>
                </div>
            </div>
        </div>

        <!-- Additional Insights Section -->
        <div class="insights-section p-3 shadow-sm rounded mb-4">
            <h6 class="fw-bold text-secondary mb-2">Insights</h6>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-map-marker-alt fa-lg text-primary me-2"></i>
                    <span>Most Visited:</span>
                    <strong class="ms-1 text-dark">{{ $mostVisitedLocation }}</strong>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-stopwatch fa-lg text-primary me-2"></i>
                    <span>Longest Duration:</span>
                    <strong class="ms-1 text-dark">
                        {{ $longestDurationLocation ? intdiv($longestDurationLocation->duration_at_location, 60) . ' hrs ' . ($longestDurationLocation->duration_at_location % 60) . ' mins' : 'N/A' }}
                    </strong>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .progress-circle {
            width: 50px;
            height: 50px;
            position: relative;
        }
        .circle {
            fill: none;
            stroke-width: 2.8;
        }
        .circle-bg {
            stroke: #e6e6e6;
        }
        .circle-progress {
            stroke: #1e3a8a;
            stroke-linecap: round;
        }
        .summary-card, .insights-section {
            border-radius: 12px;
            background-color: #f8f9fa;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
