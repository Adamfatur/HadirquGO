@extends('layout.lecturer') <!-- Layout for lecturer -->

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 Team {{ __('Leaderboard') }} 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">Check how your team ranks within your business!</p>

            <!-- Enhanced Alert Box -->
            <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm p-3 mb-4" role="alert" style="background-color: #e6f5ff; border-color: #80c3f7;">
                <strong>Info:</strong> The leaderboard updates periodically.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <!-- Back and View User Rankings Buttons (Responsive) -->
        <div class="d-grid gap-2 mb-4">
            <a href="{{ route('lecturer.leaderboard.index') }}" class="btn btn-outline-light rounded-pill">Back to User Rankings</a>
            <a href="{{ route('lecturer.dashboard') }}" class="btn btn-primary rounded-pill">{{ __('Back to Dashboard') }}</a>
        </div>

        <!-- Team {{ __('Leaderboard') }} Table -->
        @if($teams->isEmpty())
            <!-- Display message if no data available -->
            <div class="text-center mt-5">
                <div class="card shadow-sm p-4" style="background-color: #f9f9fb; border-radius: 20px;">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-users-slash text-secondary" style="font-size: 4rem;"></i>
                        <h3 class="text-dark fw-bold mt-3">No Team Data Available</h3>
                        <p class="text-muted mt-2">
                            It looks like there is no ranking data for teams yet.<br>
                            Encourage your teammates to stay active and check back later!
                        </p>
                        <a href="{{ route('lecturer.dashboard') }}" class="btn btn-primary mt-3">Go {{ __('Back to Dashboard') }}</a>
                    </div>
                </div>
            </div>
        @else
            <!-- Table with Team {{ __('Leaderboard') }} -->
            <div class="card shadow-sm p-4 mb-5" style="border-radius: 20px; background-color: #f9f9fb;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: #1e3a8a;">
                        <tr>
                            <th class="text-center" style="width: 10%;">#</th>
                            <th>Team Name</th>
                            <th>{{ __('Total Points') }}</th>
                            <th>Attendance Count</th>
                            <th>{{ __('Total Duration') }}</th> <!-- Total Duration -->
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teams as $index => $team)
                            <tr class="border-bottom">
                                <td class="text-center fw-bold" style="font-size: 1.25rem;">
                                    @if($index == 0) 🥇
                                    @elseif($index == 1) 🥈
                                    @elseif($index == 2) 🥉
                                    @else {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">{{ $team['team_name'] }}</td>
                                <td class="text-warning">{{ $team['total_points'] }}</td>
                                <td>{{ $team['attendance_count'] }}</td>
                                <td>{{ $team['formatted_total_duration'] }}</td> <!-- Display the formatted total duration -->
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

<style>
    /* Improved Button Styles */
    .btn-outline-light {
        color: #ffffff;
        border-color: #ffffff;
    }
    .btn-outline-light:hover {
        background-color: #ffffff;
        color: #1e3a8a;
    }

    /* Table Header and Row Styles */
    .bg-primary {
        background-color: #1e3a8a !important;
    }
    .text-light, .text-white {
        color: #ffffff !important;
    }

    /* Table Hover Effect for Rows */
    .table-hover tbody tr:hover {
        background-color: #e6f0ff;
    }

    /* Leaderboard Highlighted Rows */
    .bg-warning {
        background-color: #fff3cd !important;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Custom Tooltip Colors */
    .tooltip-inner {
        background-color: #1e3a8a;
        color: #ffffff;
    }

    /* Alert Style */
    .alert-info {
        background-color: #e6f5ff;
        border-color: #80c3f7;
        color: #1e3a8a;
    }
</style>
