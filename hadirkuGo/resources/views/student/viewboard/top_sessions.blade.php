@extends('layout.student')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 Top 50 Rankings by Sessions 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">
                Check your performance and rankings based on <strong>session count</strong>!
            </p>
        </div>

        <div class="d-flex justify-content-center mb-4 flex-wrap gap-2">
            <a href="{{ route('student.viewboard.top-levels') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('student.viewboard.top-levels') ? 'active' : '' }}">
                Top Levels
            </a>
            <a href="{{ route('student.viewboard.top-sessions') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('student.viewboard.top-sessions') ? 'active' : '' }}">
                Top Sessions
            </a>
            <a href="{{ route('student.viewboard.top-duration') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('student.viewboard.top-duration') ? 'active' : '' }}">
                Top Duration
            </a>
            <a href="{{ route('student.viewboard.top-locations') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('student.viewboard.top-locations') ? 'active' : '' }}">
                Top Locations
            </a>
            <a href="{{ route('student.viewboard.top-points') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('student.viewboard.top-points') ? 'active' : '' }}">
                Top Points
            </a>
        </div>

        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('student.viewboard.top-sessions') }}" class="d-inline-block">
                <select name="period" class="form-select w-auto d-inline-block me-2 shadow-sm select-animate"
                        onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="daily"   {{ $period == 'daily'   ? 'selected' : '' }}>{{ __('Daily') }}</option>
                    <option value="weekly"  {{ $period == 'weekly'  ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                    <option value="yearly"  {{ $period == 'yearly'  ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                </select>
            </form>
        </div>

        <div class="card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #f9f9fb;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-secondary font-weight-bold" style="color: #1e3a8a;">
                    Top 50 Rankings by Sessions - {{ ucfirst($period) }}
                </h2>
                <small class="text-muted">{{ __('Updated hourly') }}</small>
            </div>
            <div class="card-body">
                @include('partials.leaderboard_search', ['searchCategory' => 'top_sessions_' . $period])
                @if($rankings->isEmpty())
                    <div class="text-center">
                        <i class="fas fa-trophy text-primary" style="font-size: 4rem;"></i>
                        <h3 class="text-dark fw-bold mt-3">{{ __('No Data Available') }}</h3>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="text-white" style="background-color: #1e3a8a;">
                            <tr>
                                <th class="text-center" style="width: 15%;">{{ __('Rank') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Session Count') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rankings as $data)
                                <tr class="border-bottom animate__animated animate__fadeIn">
                                    <td class="text-center fw-bold">
                                        <div class="d-flex flex-column align-items-center">
                                            <span style="font-size: 1.25rem;">
                                                @if($data->current_rank == 1) 🥇
                                                @elseif($data->current_rank == 2) 🥈
                                                @elseif($data->current_rank == 3) 🥉
                                                @else {{ $data->current_rank }}
                                                @endif
                                            </span>
                                            <small style="font-size: 0.7rem;">
                                                @if(is_null($data->previous_rank))
                                                    <span class="text-primary"><i class="fas fa-plus"></i> New</span>
                                                @elseif($data->current_rank < $data->previous_rank)
                                                    <span class="text-success"><i class="fas fa-arrow-up"></i> {{ $data->previous_rank - $data->current_rank }}</span>
                                                @elseif($data->current_rank > $data->previous_rank)
                                                    <span class="text-danger"><i class="fas fa-arrow-down"></i> {{ $data->current_rank - $data->previous_rank }}</span>
                                                @else
                                                    <span class="text-muted"><i class="fas fa-minus"></i></span>
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        <img src="{{ $data->user?->avatar ?? asset('images/default-avatar.png') }}"
                                             alt="Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        {{ $data->user?->name ?? __('Unknown User') }}
                                    </td>
                                    <td class="fw-bold text-primary">
                                        {{ number_format($data->score, 0) }} sessions
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
