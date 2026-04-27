@extends('layout.lecturer') <!-- Use the existing layout -->

@section('title', 'User and Team List')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4 text-white">{{ __('User and Team List') }}</h1>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm animate-card" style="background: linear-gradient(135deg, #4caf50, #81c784); border-radius: 15px; padding: 20px; border: none;">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-users fa-3x mb-3"></i> <!-- Ikon FontAwesome -->
                        <h5 class="card-title">{{ __('Total Users') }}</h5>
                        <h2 class="fw-bold">{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm animate-card" style="background: linear-gradient(135deg, #1e88e5, #64b5f6); border-radius: 15px; padding: 20px; border: none;">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-crown fa-3x mb-3"></i> <!-- Ikon FontAwesome -->
                        <h5 class="card-title">{{ __('Total Leaders') }}</h5>
                        <h2 class="fw-bold">{{ $totalLeaders }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm animate-card" style="background: linear-gradient(135deg, #ff9800, #ffb74d); border-radius: 15px; padding: 20px; border: none;">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-user-friends fa-3x mb-3"></i> <!-- Ikon FontAwesome -->
                        <h5 class="card-title">{{ __('Total Members') }}</h5>
                        <h2 class="fw-bold">{{ $totalMembers }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form action="{{ route('lecturer.users.index') }}" method="GET" class="form-inline">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name...') }}" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="team_led" class="form-control" placeholder="{{ __('Filter by team led...') }}" value="{{ request('team_led') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="team_joined" class="form-control" placeholder="{{ __('Filter by team joined...') }}" value="{{ request('team_joined') }}">
                        </div>
                        <div class="col-md-6 mt-3">
                            <button type="submit" class="btn btn-primary w-100 btn-hover">
                                <i class="fas fa-filter me-2"></i> {{ __('Apply Filters') }}
                            </button>
                        </div>
                        <div class="col-md-6 mt-3">
                            <a href="{{ route('lecturer.users.index') }}" class="btn btn-secondary w-100 btn-hover">
                                <i class="fas fa-times me-2"></i> {{ __('Clear Filters') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4 animate-card" style="background: white; border-radius: 15px; padding: 20px; border: none;">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="background-color: #1e3a8a; color: white; border-radius: 10px 0 0 10px;">#</th>
                                    <th style="background-color: #1e3a8a; color: white;">{{ __('Name') }}</th>
                                    <th style="background-color: #1e3a8a; color: white;">{{ __('Level') }}</th>
                                    <th style="background-color: #1e3a8a; color: white;">{{ __('Teams Led') }}</th>
                                    <th style="background-color: #1e3a8a; color: white; border-radius: 0 10px 10px 0;">{{ __('Teams Joined') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $allLevels = \App\Models\Level::orderBy('minimum_points', 'asc')->get(); @endphp
                                @foreach ($users as $index => $user)
                                    @php
                                        $lbEntry = $user->leaderboards->first();
                                        $gRank = $lbEntry ? $lbEntry->current_rank : 999;
                                        $gStyle = \App\Helpers\RankHelper::getRankStyle($gRank, $lbEntry->frame_color ?? null);
                                        $userPts = $user->pointSummary->total_points ?? 0;
                                        $lvl = $allLevels->last(fn($l) => $userPts >= $l->minimum_points);
                                        $lvlIdx = $lvl ? $allLevels->search(fn($l) => $l->id === $lvl->id) + 1 : 0;
                                    @endphp
                                    <tr class="table-row">
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ route('lecturer.evaluation.show', ['member_id' => $user->member_id]) }}" class="text-decoration-none text-dark">
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative me-3">
                                                        <img src="{{ $user->avatar ?? 'https://via.placeholder.com/40' }}" alt="{{ $user->name }}" class="rounded-circle {{ $gRank <= 50 ? $gStyle['class'] : '' }}" style="width: 40px; height: 40px; object-fit: cover; {{ $gRank <= 50 ? $gStyle['glow'] : '' }}">
                                                        @if($gRank <= 3)<i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $gStyle['iconColor'] }}" style="font-size: 0.7rem; transform: translate(-50%, -80%) !important;"></i>@endif
                                                    </div>
                                                    <div>
                                                        <span class="fw-medium">{{ $user->display_name }}</span>
                                                        @if($lbEntry && $lbEntry->title)
                                                            <div>{!! \App\Helpers\RankHelper::getTitleBadge($lbEntry->title, $gRank, $lbEntry->frame_color) !!}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-2 py-1" style="font-size: 0.75rem; background: linear-gradient(135deg, #00f2fe, #4facfe); color: white;">
                                                Lv.{{ $lvlIdx }}
                                            </span>
                                            <div class="text-muted" style="font-size: 0.65rem;">{{ $lvl->name ?? 'Pioneer' }}</div>
                                        </td>
                                        <td>
                                            @if ($user->teamsLed->count() > 0)
                                                <ul class="list-unstyled">
                                                    @foreach ($user->teamsLed as $team)
                                                        <li>
                                                    <span class="badge bg-primary" style="background-color: #4caf50; border-radius: 12px; padding: 5px 10px;">
                                                        {{ $team->name }}
                                                    </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->teamsJoined->count() > 0)
                                                <ul class="list-unstyled">
                                                    @foreach ($user->teamsJoined as $team)
                                                        <li>
                                                    <span class="badge bg-info" style="background-color: #1e88e5; border-radius: 12px; padding: 5px 10px;">
                                                        {{ $team->name }}
                                                    </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Load Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Apply Google Font */
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Smooth fade-in animation for cards */
        .animate-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        /* Keyframes for fade-in */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Hover effect for buttons */
        .btn-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Table styling */
        .table-borderless {
            border: none;
        }

        .table-borderless thead th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            border: none;
            padding: 12px;
        }

        .table-borderless tbody tr {
            transition: background-color 0.3s ease;
        }

        .table-borderless tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-borderless tbody td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .table-borderless tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badge styling for teams */
        .badge {
            display: inline-block;
            margin: 2px;
            font-size: 0.875rem;
            font-weight: 500;
            color: white;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Avatar styling */
        .rounded-circle {
            border: 2px solid #e9ecef;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            /* Statistic Cards */
            .col-md-4 {
                margin-bottom: 1rem;
            }

            /* Table Responsive */
            .table-borderless thead {
                display: none;
            }

            .table-borderless tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e9ecef;
                border-radius: 10px;
                padding: 10px;
            }

            .table-borderless tbody td {
                display: block;
                text-align: left;
                padding: 0.75rem;
                border-bottom: 1px solid #e9ecef;
            }

            .table-borderless tbody td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                color: #1e3a8a;
                margin-right: 10px;
            }

            .table-borderless tbody td:last-child {
                border-bottom: none;
            }

            .badge {
                display: block;
                width: 100%;
                margin: 5px 0;
            }

            .d-flex.align-items-center {
                flex-direction: column;
                align-items: flex-start;
            }

            .rounded-circle {
                margin-bottom: 10px;
            }

            /* Form Responsive */
            .form-inline .row.g-3 {
                flex-direction: column;
            }

            .form-inline .col-md-4 {
                margin-bottom: 1rem;
            }

            .form-inline .col-md-12 {
                margin-top: 0;
            }
        }
    </style>
@endpush
