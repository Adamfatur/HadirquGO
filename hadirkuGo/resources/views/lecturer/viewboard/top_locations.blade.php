@extends('layout.lecturer')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 Top 50 Rankings by Locations 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">
                Check the most popular spots based on <strong>visit count</strong> and <strong>total duration</strong>!
            </p>
        </div>

        <!-- Navigasi ke Halaman Lain -->
        <div class="d-flex justify-content-center mb-4 flex-wrap gap-2">
            <a href="{{ route('lecturer.viewboard.top-levels') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('lecturer.viewboard.top-levels') ? 'active' : '' }}">
                Top All
            </a>
            <a href="{{ route('lecturer.viewboard.top-sessions') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('lecturer.viewboard.top-sessions') ? 'active' : '' }}">
                Top Sessions
            </a>
            <a href="{{ route('lecturer.viewboard.top-duration') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('lecturer.viewboard.top-duration') ? 'active' : '' }}">
                Top Duration
            </a>
            <a href="{{ route('lecturer.viewboard.top-locations') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('lecturer.viewboard.top-locations') ? 'active' : '' }}">
                Top Locations
            </a>
            <a href="{{ route('lecturer.viewboard.top-points') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('lecturer.viewboard.top-points') ? 'active' : '' }}">
                Top Points
            </a>
            <a href="{{ route('lecturer.viewboard.top-teams') }}"
               class="btn btn-primary btn-hover-animate {{ request()->routeIs('lecturer.viewboard.top-teams') ? 'active' : '' }}">
                Top Teams
            </a>
        </div>

        <!-- Filter Form -->
        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('lecturer.viewboard.top-locations') }}" class="d-inline-block">
                <select name="period" class="form-select w-auto d-inline-block me-2 shadow-sm select-animate"
                        onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="daily"   {{ $period == 'daily'   ? 'selected' : '' }}>{{ __('Daily') }}</option>
                    <option value="weekly"  {{ $period == 'weekly'  ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                    <option value="yearly"  {{ $period == 'yearly'  ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                </select>
            </form>
        </div>

        <!-- Rankings Section -->
        <div class="card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #f9f9fb;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-secondary font-weight-bold" style="color: #1e3a8a;">
                    Top 50 Most Visited Locations - {{ ucfirst($period) }}
                </h2>
                <small class="text-muted">{{ __('Updated hourly') }}</small>
            </div>
            <div class="card-body">
                @if($topLocations->isEmpty())
                    <div class="text-center">
                        <i class="fas fa-map-marker-alt text-primary" style="font-size: 4rem;"></i>
                        <h3 class="text-dark fw-bold mt-3">{{ __('No Data Available') }}</h3>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="text-white" style="background-color: #1e3a8a;">
                            <tr>
                                <th class="text-center" style="width: 15%;">{{ __('Rank') }}</th>
                                <th>{{ __('Location Name') }}</th>
                                <th>{{ __('Visits') }}</th>
                                <th>{{ __('Total Duration') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topLocations as $data)
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

                                    <td class="fw-bold">
                                        {{ $data->attendanceLocation?->name ?? 'Unknown Location' }}
                                    </td>

                                    <td class="fw-bold text-primary">
                                        {{ number_format($data->score, 0) }} visits
                                    </td>

                                    <td>
                                        @php
                                            $totalMinutes = $data->secondary_score ?? 0;
                                            $hours = intdiv($totalMinutes, 60);
                                            $minutes = $totalMinutes % 60;
                                        @endphp
                                        @if($hours > 0) {{ $hours }}h @endif
                                        {{ $minutes }}m
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
