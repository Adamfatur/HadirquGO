@extends('layout.lecturer')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 {{ __('Leaderboard') }} - {{ ucfirst($period) }} 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">Check how you rank among your peers in your business!</p>

            <!-- Alert Box -->
            <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm p-3 mb-4" role="alert" style="background-color: #e6f5ff; border-color: #80c3f7;">
                <strong>Info:</strong> The leaderboard updates every 8 hours.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <!-- Back and View {{ __('Team Ranking') }}s Buttons -->
        <div class="d-flex flex-column flex-sm-row justify-content-between mb-4">
            <a href="{{ route('lecturer.dashboard') }}" class="btn btn-outline-light mb-2 mb-sm-0 shadow-sm">{{ __('Back') }}</a>
            <a href="{{ route('lecturer.leaderboard.team') }}" class="btn btn-primary shadow-sm">View {{ __('Team Ranking') }}s</a>
        </div>

        <!-- Filter Form -->
        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('lecturer.leaderboard.index') }}" class="d-inline-block">
                <select name="period" class="form-select w-auto d-inline-block me-2 shadow-sm" onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                </select>
            </form>
        </div>

        <!-- {{ __('Leaderboard') }} Data -->
        @if($users->isEmpty())
            <div class="text-center mt-5">
                <div class="card shadow-sm p-4" style="background-color: #f9f9fb; border-radius: 20px;">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-trophy text-primary" style="font-size: 4rem;"></i>
                        <h3 class="text-dark fw-bold mt-3">{{ __('No Data Available') }}</h3>
                        <p class="text-muted mt-2">
                            It looks like there is no ranking data for the selected period.<br>
                            Try checking back later or selecting a different time period.
                        </p>
                        <a href="{{ route('lecturer.dashboard') }}" class="btn btn-primary mt-3">Go {{ __('Back to Dashboard') }}</a>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm p-4 mb-5" style="border-radius: 20px; background-color: #f9f9fb;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: #1e3a8a;">
                        <tr>
                            <th class="text-center" style="width: 10%;">#</th>
                            <th>{{ __('Name') }}</th>
                            <th>Team</th>
                            <th>{{ __('Total Points') }}</th>
                            <th>Attendance Count</th>
                            <th>{{ __('Total Duration') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users->take(100) as $index => $entry)
                            <tr class="border-bottom">
                                <td class="text-center fw-bold" style="font-size: 1.25rem;">
                                    @if($index == 0) 🥇
                                    @elseif($index == 1) 🥈
                                    @elseif($index == 2) 🥉
                                    @else {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">
                                    @php
                                        $entryRankData = $entry['user']->leaderboards->first();
                                        $entryRank = $entryRankData?->current_rank ?? ($index + 1);
                                        $entryStyle = \App\Helpers\RankHelper::getRankStyle($entryRank, $entryRankData?->frame_color);
                                        $entryEvalUrl = $entry['user']->member_id ? route('lecturer.evaluation.show', ['member_id' => $entry['user']->member_id]) : null;
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-2">
                                            @if($entryEvalUrl)<a href="{{ $entryEvalUrl }}" class="text-decoration-none">@endif
                                            <img src="{{ $entry['user']->avatar ? (str_starts_with($entry['user']->avatar, 'http') ? $entry['user']->avatar : asset($entry['user']->avatar)) : asset('images/default-avatar.png') }}" 
                                                 alt="Avatar" class="rounded-circle {{ $entryStyle['class'] }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover; {{ $entryStyle['glow'] }}">
                                            @if($entryEvalUrl)</a>@endif
                                        </div>
                                        <div>
                                            <div class="mb-0">
                                                @if($entryEvalUrl)<a href="{{ $entryEvalUrl }}" class="text-decoration-none text-dark">@endif
                                                {{ $entry['user']->name }}
                                                @if($entryEvalUrl)</a>@endif
                                            </div>
                                            @if($entryRankData && $entryRankData->title)
                                                <div class="small px-2 py-0 rounded-pill d-inline-flex align-items-center" style="font-size: 0.65rem; font-weight: 600; {{ $entryStyle['badge'] }}">
                                                    <i class="fas fa-award me-1"></i> {{ $entryRankData->title }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-primary">{{ $entry['team_name'] }}</td>
                                <td class="text-warning">{{ $entry['total_points'] }}</td>
                                <td>{{ $entry['attendance_count'] }}</td>
                                <td>
                                    @php
                                        $totalMinutes = $entry['total_duration']; // Total durasi dalam menit
                                        if ($totalMinutes >= 60) {
                                            $hours = intdiv($totalMinutes, 60);
                                            $minutes = $totalMinutes % 60;
                                            echo "{$hours} hours";
                                            if ($minutes > 0) {
                                                echo " {$minutes} minutes";
                                            }
                                        } else {
                                            echo "{$totalMinutes} minutes";
                                        }
                                    @endphp
                                </td>

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
    .card {
        border: none;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }
    .btn-outline-light {
        color: #ffffff;
        border-color: #ffffff;
    }
    .btn-outline-light:hover {
        background-color: #ffffff;
        color: #1e3a8a;
    }
    .table-hover tbody tr:hover {
        background-color: #e6f0ff;
    }
    .alert-info {
        background-color: #e6f5ff;
        border-color: #80c3f7;
        color: #1e3a8a;
    }
</style>
