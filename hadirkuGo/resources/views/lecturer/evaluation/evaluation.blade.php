@extends(Auth::user()->hasRole('Lecturer') ? 'layout.lecturer' : 'layout.student')
@php use App\Helpers\RankHelper; @endphp

@section('title', 'Student {{ __('Evaluation Report') }}')

@section('content')
    <style>
        /* === Premium Glass & Card Styling === */
        .eval-card {
            background: rgba(255, 255, 255, 0.05);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
            color: #f8fafc;
        }
        .eval-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .eval-header {
            background: rgba(255, 255, 255, 0.03);
            color: white;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* === Smooth Entrance Animations === */
        @keyframes evalSlideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animated-block {
            animation: evalSlideInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        
        @keyframes shineSweep {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
        
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 15px rgba(251, 191, 36, 0.5), inset 0 0 10px rgba(251, 191, 36, 0.5); }
            50% { box-shadow: 0 0 25px rgba(251, 191, 36, 0.8), inset 0 0 20px rgba(251, 191, 36, 0.8); }
            100% { box-shadow: 0 0 15px rgba(251, 191, 36, 0.5), inset 0 0 10px rgba(251, 191, 36, 0.5); }
        }

        /* === GRADE S GOLD THEME === */
        .grade-s-card {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%) !important;
            border: 2px solid rgba(251, 191, 36, 0.6) !important;
            animation: pulseGlow 3s infinite alternate !important;
            position: relative;
            overflow: hidden;
        }
        .grade-s-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shineSweep 3s infinite linear;
            pointer-events: none;
        }
        .grade-s-text {
            color: #fbbf24 !important;
            font-weight: 900;
            text-shadow: 0 0 15px rgba(251, 191, 36, 0.8), 0 0 5px rgba(251, 191, 36, 0.4);
        }
        .crown-icon {
            color: #fbbf24;
            filter: drop-shadow(0 2px 5px rgba(251,191,36,0.6));
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        /* === Profile Header === */
        .profile-header-card {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.8) 0%, rgba(59, 130, 246, 0.3) 100%);
            -webkit-backdrop-filter: blur(15px);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            position: relative;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }

        /* === Stats Grid === */
        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            color: white;
        }
        .stat-box:hover {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .stat-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
            background: rgba(255, 255, 255, 0.1);
        }

        /* === Narrative Block Styling === */
        .narrative-container {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            border-left: 4px solid #3b82f6;
            margin-bottom: 1rem;
        }
        
        /* Modern Table */
        .modern-table { color: #f8fafc; }
        .modern-table th { color: #94a3b8; font-size: 0.85rem; border: none; padding: 1rem; }
        .modern-table td { border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 1rem; vertical-align: middle; background: rgba(255,255,255,0.02); }
        .modern-table tr:hover td { background: rgba(255,255,255,0.08); }

        .rank-badge-glow {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }
        .level-badge-glow {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            box-shadow: 0 0 15px rgba(79, 172, 254, 0.4);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }
        .exp-badge-glow {
            background: rgba(255, 255, 255, 0.1);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }
    </style>

<div class="container-fluid py-4 text-white">
    
    {{-- Header & Filter Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 animated-block" style="animation-delay: 0.1s;">
        <div>
            <h2 class="fw-bold mb-1 text-white"><i class="fas fa-chart-line me-2 text-primary"></i>{{ __('Evaluation Report') }}</h2>
            <p class="text-white-50 mb-0">Detailed insight into performance & rankings.</p>
        </div>
        
        <div class="mt-3 mt-md-0">
            <form action="" method="GET" class="m-0" id="evalFilterForm">
                <div class="d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 6px 12px; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-calendar-alt text-info" style="font-size: 0.85rem;"></i>
                    <select name="period" id="periodSelect" class="form-select form-select-sm border-0 shadow-none fw-bold pe-4" style="background: transparent; color: white; cursor: pointer; font-size: 0.85rem; width: auto; min-width: 130px;" onchange="handlePeriodChange(this)">
                        <option value="this_month" style="color: #333;" {{ $currentPeriod == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" style="color: #333;" {{ $currentPeriod == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <optgroup label="Extended Range" style="color: #333;">
                            <option value="90_days" style="color: #333;" {{ $currentPeriod == '90_days' ? 'selected' : '' }}>Last 90 Days</option>
                            <option value="6_months" style="color: #333;" {{ $currentPeriod == '6_months' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="1_year" style="color: #333;" {{ $currentPeriod == '1_year' ? 'selected' : '' }}>Last 1 Year</option>
                        </optgroup>
                        <optgroup label="Specific Month" style="color: #333;">
                            @for ($i = 2; $i < 6; $i++)
                                @php $month = now()->subMonths($i); @endphp
                                <option value="{{ $month->format('Y-m') }}" style="color: #333;" {{ $currentPeriod == $month->format('Y-m') ? 'selected' : '' }}>
                                    {{ $month->format('F Y') }}
                                </option>
                            @endfor
                        </optgroup>
                        <option value="custom" style="color: #333;" {{ str_contains($currentPeriod, '_to_') ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div id="customRangeInputs" class="d-none mt-2">
                    <div class="d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 6px 12px; border: 1px solid rgba(255,255,255,0.2);">
                        <input type="date" id="customStart" class="form-control form-control-sm border-0" style="background: rgba(255,255,255,0.12); color: white; border-radius: 6px; font-size: 0.8rem; max-width: 130px;"
                               value="{{ str_contains($currentPeriod, '_to_') ? explode('_to_', $currentPeriod)[0] : '' }}">
                        <span class="text-white-50 small">to</span>
                        <input type="date" id="customEnd" class="form-control form-control-sm border-0" style="background: rgba(255,255,255,0.12); color: white; border-radius: 6px; font-size: 0.8rem; max-width: 130px;"
                               value="{{ str_contains($currentPeriod, '_to_') ? explode('_to_', $currentPeriod)[1] : '' }}">
                        <button type="button" class="btn btn-sm btn-info rounded-pill px-3 py-1" style="font-size: 0.8rem;" onclick="applyCustomRange()">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Profile Summary Card --}}
    @php
        $gradeVal = is_array($data['final_summary']['final_grade']) ? $data['final_summary']['final_grade']['grade'] : $data['final_summary']['final_grade'];
        $isGradeS = ($gradeVal === 'S');
    @endphp

    <div class="profile-header-card mb-5 animated-block {{ $isGradeS ? 'grade-s-card' : '' }}" style="animation-delay: 0.2s;">
        @if($isGradeS)
            <!-- S-Rank Background Particles/Glow -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(251,191,36,0.4) 0%, transparent 70%); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -50px; left: 10%; width: 150px; height: 150px; background: radial-gradient(circle, rgba(251,191,36,0.3) 0%, transparent 70%); border-radius: 50%;"></div>
        @endif

        <div class="row align-items-center position-relative z-1">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="d-flex align-items-center">
                    @php
                        $rankStyle = RankHelper::getRankStyle(
                            $data['user_rank'],
                            $data['frame_color'] ?? null
                        );
                        $avatarStyle = $rankStyle['glow'] . "; border-color: " . ($data['frame_color'] ?? '#ffffff') . " !important;";
                    @endphp
                    <div class="position-relative">
                        <img src="{{ $data['user_info']['avatar'] ?? asset('images/default-avatar.png') }}" alt="Avatar" class="profile-avatar me-4" style="{{ $avatarStyle }}">
                        @if($data['user_rank'] <= 3 && $data['user_rank'] !== '-')
                            <i class="fas fa-crown crown-icon position-absolute start-50 translate-middle-x" style="top: -25px; font-size: 2.2rem; color: {{ $rankStyle['iconColor'] === 'text-warning' ? '#fbbf24' : ($rankStyle['iconColor'] === 'text-secondary' ? '#9ca3af' : '#fbbf24') }}; z-index: 10;"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1 text-white {{ $isGradeS ? 'grade-s-text' : '' }}">{{ $data['user_info']['name'] }}</h3>
                        @if($data['user_title'])
                            <div class="mb-2">
                                <span class="badge rounded-pill px-3 py-1 shadow-sm" style="{{ $rankStyle['badge'] }}; font-size: 0.8rem;">
                                    <i class="fas fa-award me-1"></i> {{ $data['user_title'] }}
                                </span>
                            </div>
                        @endif
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <p class="mb-0 text-white-50">ID: {{ $data['user_info']['member_id'] }}</p>
                            <span class="text-white-50 opacity-25">|</span>
                            <div class="text-white-50 small">
                                <i class="fas fa-clock me-1"></i> {{ $data['user_info']['evaluation_period'] }}
                            </div>
                            <span class="text-white-50 opacity-25">|</span>
                            <a href="{{ route('journey.stats', ['memberId' => $data['user_info']['member_id']]) }}" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill px-3 py-0" style="font-size: 0.75rem;">
                                <i class="fas fa-route me-1"></i> Journey
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row g-3">
                    {{-- Level, Rank & EXP Banner --}}
                    <div class="col-12 mb-3">
                        <div class="d-flex flex-wrap align-items-center justify-content-lg-end justify-content-start gap-2">
                            {{-- Level Badge --}}
                            <div class="level-badge-glow">
                                <i class="fas fa-medal me-2"></i> 
                                LEVEL {{ $data['level_number'] ?? '?' }}
                                <span class="ms-2 opacity-75 fw-normal d-none d-sm-inline" style="font-size: 0.8rem;">({{ $data['level_name'] ?? 'Pioneer' }})</span>
                            </div>

                            {{-- Rank Badge --}}
                            @if(isset($data['user_rank']) && $data['user_rank'] !== '-')
                                <div class="rank-badge-glow">
                                    <i class="fas fa-trophy me-2"></i> 
                                    Rank #{{ $data['user_rank'] }} 
                                </div>
                            @endif

                            {{-- EXP Badge --}}
                            <div class="exp-badge-glow">
                                <i class="fas fa-star me-2"></i> 
                                {{ number_format($data['total_points_exp'] ?? 0) }} EXP
                            </div>
                        </div>
                    </div>

                    {{-- Grade & Score --}}
                    <div class="col-6">
                        <div class="p-3 rounded-4 text-center h-100" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); {{ $isGradeS ? 'border-color: rgba(251,191,36,0.5);' : '' }}">
                            <p class="text-white-50 small mb-1 fw-bold text-uppercase tracking-wide">{{ __('Final Grade') }}</p>
                            <h2 class="display-4 fw-bold mb-0 {{ $isGradeS ? 'grade-s-text' : 'text-white' }}">{{ $gradeVal }}</h2>
                            @if(is_array($data['final_summary']['final_grade']))
                                <span class="badge {{ $isGradeS ? 'bg-warning text-dark' : 'bg-success' }} rounded-pill mt-1 px-3 py-1">
                                    {{ $data['final_summary']['final_grade']['label'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 text-center h-100" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); {{ $isGradeS ? 'border-color: rgba(251,191,36,0.5);' : '' }}">
                            <p class="text-white-50 small mb-1 fw-bold text-uppercase tracking-wide">{{ __('Total Score') }}</p>
                            <h2 class="display-4 fw-bold mb-0 {{ $isGradeS ? 'text-warning' : 'text-info' }}">
                                {{ $data['final_summary']['final_average_score'] }}<span style="font-size: 1.5rem; color: rgba(255,255,255,0.3);">/100</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Narrative & Radar Chart Row --}}
    <div class="row g-4 mb-5">
        {{-- Narrative Summary --}}
        <div class="col-xl-7 animated-block" style="animation-delay: 0.3s;">
            <div class="eval-card h-100">
                <div class="eval-header d-flex justify-content-between align-items-center">
                    <h5 class="eval-header-title">
                        @if($data['final_summary']['ai_failed'])
                            <i class="fas fa-robot me-2 text-info"></i> System Performance Analysis
                        @else
                            <i class="fas fa-brain me-2 text-primary"></i> AI Narrative Summary
                        @endif
                    </h5>
                    @if($data['final_summary']['ai_failed'])
                        <span class="badge bg-secondary text-white rounded-pill border border-secondary">System Generated</span>
                    @else
                        <span class="badge bg-primary bg-opacity-25 text-info rounded-pill border border-info border-opacity-50"><i class="fas fa-bolt text-warning me-1"></i>Powered by Gemini</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <div class="narrative-container">
                            <h6 class="narrative-title"><i class="fas fa-id-card"></i> Personality Summary</h6>
                            <p class="narrative-text">{{ $data['final_summary']['narrative']['personality_summary'] ?? 'Data unavailable.' }}</p>
                        </div>
                        <div class="narrative-container" style="border-left-color: #f59e0b; background: rgba(245, 158, 11, 0.05);">
                            <h6 class="narrative-title" style="color: #fbbf24;"><i class="fas fa-seedling text-warning"></i> Motivation & Growth</h6>
                            <p class="narrative-text" style="color: #fde68a;">
                                {!! isset($data['final_summary']['narrative']['motivation_and_growth']) ? preg_replace('/\*\*(.*?)\*\*/', '<strong class="text-warning">$1</strong>', $data['final_summary']['narrative']['motivation_and_growth']) : 'Data unavailable.' !!}
                            </p>
                        </div>
                        <div class="narrative-container" style="border-left-color: #10b981; background: rgba(16, 185, 129, 0.05);">
                            <h6 class="narrative-title" style="color: #34d399;"><i class="fas fa-crown text-success"></i> Leadership Summary</h6>
                            <p class="narrative-text" style="color: #a7f3d0;">{{ $data['final_summary']['narrative']['leadership_summary'] ?? 'Data unavailable.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Radar Chart --}}
        <div class="col-xl-5 animated-block" style="animation-delay: 0.4s;">
            <div class="eval-card h-100">
                <div class="eval-header" style="background: rgba(0,0,0,0.2);">
                    <h5 class="eval-header-title"><i class="fas fa-radar me-2 text-danger"></i>Competency Radar</h5>
                </div>
                <div class="card-body p-4 d-flex justify-content-center align-items-center position-relative" style="min-height: 350px;">
                    <!-- Memastikan wrapper chart responsif dan proporsional -->
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Period Statistics --}}
    <h4 class="fw-bold mb-4 animated-block text-white" style="animation-delay: 0.5s;"><i class="fas fa-chart-pie me-2 text-primary"></i>{{ __('Period Statistics Overview') }}</h4>
    <div class="row g-4 mb-5">
        @php
            $details = [
                'total_hadir_sebulan_hari' => ['icon' => 'fa-check-circle', 'color' => 'success', 'label' => __('Total Attendances'), 'unit' => __('days')],
                'total_durasi_sebulan_menit' => ['icon' => 'fa-clock', 'color' => 'info', 'label' => __('Total Duration'), 'unit' => __('min')],
                'tidak_masuk_atau_lupa_checkout_hari' => ['icon' => 'fa-times-circle', 'color' => 'danger', 'label' => __('Absences'), 'unit' => __('days')],
                'lupa_checkout_kali' => ['icon' => 'fa-exclamation-circle', 'color' => 'warning', 'label' => __('Missed Checkouts'), 'unit' => __('times')],
                'durasi_terlama_menit' => ['icon' => 'fa-arrow-up', 'color' => 'primary', 'label' => __('Longest Session'), 'unit' => __('min')],
                'durasi_tercepat_menit' => ['icon' => 'fa-arrow-down', 'color' => 'secondary', 'label' => __('Shortest Session'), 'unit' => __('min')],
                'rata_rata_durasi_harian_menit' => ['icon' => 'fa-compress-arrows-alt', 'color' => 'info', 'label' => __('Average Duration'), 'unit' => __('min')],
                'hadir_beruntun_terpanjang_hari' => ['icon' => 'fa-fire', 'color' => 'danger', 'label' => __('Longest Streak'), 'unit' => __('days')],
            ];
            $delay = 0.5;
        @endphp
        @foreach($details as $key => $detail)
            @if(isset($data['monthly_statistics'][$key]))
                @php 
                    $value = $data['monthly_statistics'][$key]; 
                    $delay += 0.05;
                @endphp
                <div class="col-xl-3 col-md-4 col-sm-6 animated-block" style="animation-delay: {{ $delay }}s;">
                    <div class="stat-box h-100">
                        <div class="stat-icon-wrapper text-{{ $detail['color'] }}">
                            <i class="fas {{ $detail['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-white-50 small fw-bold mb-1 text-uppercase tracking-wide">{{ $detail['label'] }}</p>
                            <h4 class="fw-bold mb-0 text-white">{{ $value }} <span style="font-size: 0.9rem; color: rgba(255,255,255,0.5); font-weight: 500;">{{ $detail['unit'] }}</span></h4>
                            @if(str_contains($key, '_menit') && $value > 0)
                                @php
                                    $hours = floor($value / 60);
                                    $rem_minutes = $value % 60;
                                @endphp
                                <small class="text-white-50 fw-medium">{{ $hours }}h {{ $rem_minutes }}m</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Details & Calendar Row --}}
    <div class="row g-4 mb-5">
        
        {{-- Detailed Breakdown --}}
        <div class="col-xl-5 animated-block" style="animation-delay: 0.9s;">
            <div class="eval-card h-100">
                <div class="eval-header">
                    <h5 class="eval-header-title d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list-ul me-2 text-info"></i>Score Breakdown</span>
                        <button class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modal-calculation">
                            <i class="fas fa-info-circle me-1"></i> How it works
                        </button>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($data['performance_evaluation'] as $metricName => $metricData)
                            @php
                                $metricDetails = [
                                    'consistency' => ['icon' => 'fa-calendar-check', 'color' => '#60a5fa'],
                                    'discipline' => ['icon' => 'fa-stopwatch', 'color' => '#34d399'],
                                    'perseverance' => ['icon' => 'fa-dumbbell', 'color' => '#fbbf24'],
                                    'ambition' => ['icon' => 'fa-rocket', 'color' => '#f87171'],
                                    'initiative_and_commitment' => ['icon' => 'fa-hand-sparkles', 'color' => '#a78bfa'],
                                ];
                                $score = $metricData['score_percentage'] ?? 0;
                                $color = $metricDetails[$metricName]['color'] ?? '#60a5fa';
                                $title = ucwords(str_replace('_', ' ', $metricName));
                            @endphp
                            <li class="list-group-item p-4" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper" style="width: 40px; height: 40px; font-size: 1.2rem; background: rgba(255,255,255,0.05); color: {{ $color }};">
                                            <i class="fas {{ $metricDetails[$metricName]['icon'] ?? 'fa-star' }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-white">{{ $title }}</h6>
                                            <span class="badge bg-dark border border-secondary text-light mt-1">{{ $metricData['grade'] }} Grade</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="fw-bold mb-0" style="color: {{ $color }};">{{ number_format($score, 1) }}</h4>
                                    </div>
                                </div>
                                <div class="progress" style="height: 6px; background-color: rgba(0,0,0,0.3); border-radius: 50px;">
                                    <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $score }}%; background-color: {{ $color }}; box-shadow: 0 0 10px {{ $color }};"></div>
                                </div>
                                
                                {{-- Rincian Nilai / Breakdown Details --}}
                                <div class="mt-3 pt-3" style="border-top: 1px dashed rgba(255,255,255,0.1);">
                                    <ul class="list-unstyled mb-0 small">
                                        @if($metricName == 'consistency')
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-calendar-day me-2" style="color: {{ $color }};"></i>Workdays Attended</span> 
                                                <span class="text-white fw-bold">{{ $metricData['days_attended_on_weekdays'] ?? 0 }} / {{ $metricData['total_workdays_in_period'] ?? 0 }} days</span>
                                            </li>
                                        @elseif($metricName == 'discipline')
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-trophy me-2" style="color: {{ $color }};"></i>Pioneer Arrivals (1st)</span> 
                                                <span class="text-white fw-bold">{{ $metricData['pioneer_days'] ?? 0 }} days</span>
                                            </li>
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-check-circle me-2" style="color: {{ $color }};"></i>On-Time Arrivals</span> 
                                                <span class="text-white fw-bold">{{ $metricData['on_time_days'] ?? 0 }} days</span>
                                            </li>
                                            <li class="d-flex justify-content-between mb-1">
                                                <span class="text-white-50"><i class="fas fa-clock me-2" style="color: {{ $color }};"></i>Late / Present</span> 
                                                <span class="text-white fw-bold">{{ $metricData['present_days'] ?? 0 }} days</span>
                                            </li>
                                        @elseif($metricName == 'perseverance')
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-stopwatch me-2" style="color: {{ $color }};"></i>{{ __('Average Duration') }}</span> 
                                                <span class="text-white fw-bold">{{ $metricData['average_duration_minutes'] ?? 0 }} min/day</span>
                                            </li>
                                            <li class="d-flex justify-content-between mb-1">
                                                <span class="text-white-50"><i class="fas fa-flag-checkered me-2" style="color: {{ $color }};"></i>Standard Target</span> 
                                                <span class="text-white fw-bold">{{ $metricData['standard_duration_minutes'] ?? 0 }} min/day</span>
                                            </li>
                                        @elseif($metricName == 'ambition')
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-fire me-2" style="color: {{ $color }};"></i>Extra Duration Score</span> 
                                                <span class="text-white fw-bold">{{ $metricData['indicators']['exceeding_duration_score'] ?? 0 }} pts</span>
                                            </li>
                                            <li class="d-flex justify-content-between mb-1">
                                                <span class="text-white-50"><i class="fas fa-tasks me-2" style="color: {{ $color }};"></i>Proactivity Score</span> 
                                                <span class="text-white fw-bold">{{ $metricData['indicators']['proactivity_score'] ?? 0 }} pts</span>
                                            </li>
                                        @elseif($metricName == 'initiative_and_commitment')
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-sun me-2" style="color: {{ $color }};"></i>Saturday Attendances</span> 
                                                <span class="text-white fw-bold">{{ $metricData['saturday_attendance_count'] ?? 0 }}x</span>
                                            </li>
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-moon me-2" style="color: {{ $color }};"></i>Sunday Attendances</span> 
                                                <span class="text-white fw-bold">{{ $metricData['sunday_attendance_count'] ?? 0 }}x</span>
                                            </li>
                                            <li class="d-flex justify-content-between mb-2">
                                                <span class="text-white-50"><i class="fas fa-history me-2" style="color: {{ $color }};"></i>Overtime Checkouts</span> 
                                                <span class="text-white fw-bold">{{ $metricData['overtime_checkout_count'] ?? 0 }}x</span>
                                            </li>
                                            <li class="d-flex justify-content-between mt-2 pt-2 border-top border-secondary">
                                                <span class="text-info fw-bold"><i class="fas fa-plus-circle me-2"></i>Earned Bonus Points</span> 
                                                <span class="text-warning fw-bold">+{{ $metricData['bonus_points'] ?? 0 }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Calendar Log --}}
        <div class="col-xl-7 animated-block" style="animation-delay: 1s;">
            <div class="eval-card h-100">
                <div class="eval-header" style="background: rgba(0,0,0,0.2);">
                    <h5 class="eval-header-title"><i class="fas fa-calendar-day me-2 text-success"></i>Daily Log History</h5>
                </div>
                <div class="card-body p-4">
                    @if(isset($startDate) && isset($endDate))
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table modern-table w-100 m-0">
                                <thead style="position: sticky; top: 0; background: rgba(30, 41, 59, 0.95); z-index: 1;">
                                    <tr>
                                        <th>Date</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate->copy()->addDay());
                                    $dailyLogsCollection = collect($data['daily_log'])->keyBy(fn($log) => \Carbon\Carbon::parse($log['date'])->toDateString());
                                    $currentMonthLabel = null;
                                @endphp

                                @foreach ($period as $day)
                                    @php
                                        $dayCarbon = \Carbon\Carbon::instance($day);
                                        $dateString = $dayCarbon->toDateString();
                                        $monthLabel = $dayCarbon->format('F Y');
                                        $log = $dailyLogsCollection->get($dateString);

                                        $isHoliday = in_array($dateString, $publicHolidays ?? []);
                                        $isWeekend = $dayCarbon->isWeekend();
                                        $isWorkday = !$isWeekend && !$isHoliday;
                                        $isFuture = $dayCarbon->isFuture();

                                        $rowClass = '';
                                        $statusBadge = '';
                                        $checkin = '--:--';
                                        $checkout = '--:--';

                                        if ($log) {
                                            if ($log['checkout']) {
                                                $statusBadge = '<span class="badge bg-success bg-opacity-25 text-success border border-success rounded-pill px-3">Present</span>';
                                                $checkin = substr($log['checkin'], 0, 5);
                                                $checkout = substr($log['checkout'], 0, 5);
                                            } else {
                                                $statusBadge = '<span class="badge bg-warning bg-opacity-25 text-warning border border-warning rounded-pill px-3">Missed Out</span>';
                                                $checkin = substr($log['checkin'], 0, 5);
                                            }
                                        } elseif ($isFuture) {
                                            $rowClass = 'opacity-50';
                                            $statusBadge = '<span class="badge bg-dark text-white-50 border rounded-pill px-3">Upcoming</span>';
                                        } elseif (!$isWorkday) {
                                            $rowClass = 'opacity-75';
                                            $statusBadge = '<span class="badge bg-secondary bg-opacity-25 text-white border border-secondary rounded-pill px-3">'.($isHoliday ? 'Holiday' : 'Weekend').'</span>';
                                        } else {
                                            $statusBadge = '<span class="badge bg-danger bg-opacity-25 text-danger border border-danger rounded-pill px-3">Absent</span>';
                                        }
                                    @endphp

                                    @if(($isMultiMonth ?? false) && $monthLabel !== $currentMonthLabel)
                                        @php $currentMonthLabel = $monthLabel; @endphp
                                        <tr>
                                            <td colspan="4" class="text-center py-2" style="background: rgba(59, 130, 246, 0.15); border-top: 2px solid rgba(59, 130, 246, 0.3);">
                                                <span class="fw-bold text-info small"><i class="fas fa-calendar me-1"></i>{{ $monthLabel }}</span>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <div class="fw-bold text-white">{{ $dayCarbon->format('d M') }}</div>
                                            <div class="small text-white-50">{{ $dayCarbon->format('D') }}</div>
                                        </td>
                                        <td class="fw-medium text-white">{{ $checkin }}</td>
                                        <td class="fw-medium text-white">{{ $checkout }}</td>
                                        <td>{!! $statusBadge !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="stat-icon-wrapper mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.5); font-size: 2.5rem;">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h5 class="fw-bold text-white">Log Unavailable</h5>
                            <p class="text-white-50">Daily log requires a specific date range. Please select a period from the filter above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HOW SCORES ARE CALCULATED (BILINGUAL) --}}
<!-- Modal content kept clean, using the styles defined above -->
<div class="modal fade" id="modal-calculation" tabindex="-1" aria-labelledby="modalCalculationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4" style="background: rgba(30, 41, 59, 0.95); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9;">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="modal-title fw-bold text-white" id="modalCalculationLabel">
                    <i class="fas fa-book-open me-2 text-info"></i> Evaluation Guide
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                {{-- Language Tabs --}}
                <ul class="nav nav-pills nav-pills-custom mb-4 justify-content-center" id="lang-tab" role="tablist" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active bg-primary bg-opacity-25 border border-primary text-white px-4 rounded-pill" id="eng-tab" data-bs-toggle="pill" data-bs-target="#eng-content" type="button" role="tab">English</button>
                    </li>
                    <li class="nav-item ms-2" role="presentation">
                        <button class="nav-link text-white-50 border border-secondary px-4 rounded-pill bg-transparent" id="id-tab" data-bs-toggle="pill" data-bs-target="#id-content" type="button" role="tab">Indonesia</button>
                    </li>
                </ul>

                <div class="tab-content" id="lang-tabContent">
                    {{-- ENGLISH CONTENT --}}
                    <div class="tab-pane fade show active" id="eng-content" role="tabpanel">
                        <p class="text-white-50 mb-4">The evaluation engine utilizes a multi-weighted scoring algorithm to determine performance tiers.</p>
                        
                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-calendar-check me-2"></i>1. Consistency Matrix (<var>C</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">C</span> = (<span class="text-white">Days_Attended</span> / <span class="text-white">Total_Workdays</span>) × 100
                            </div>
                            <small class="text-white-50">Measures the absolute attendance rate against mandatory operational days.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-stopwatch me-2"></i>2. Discipline Index (<var>D</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">D</span> = ∑ (<span class="text-warning">Pioneer(100)</span> + <span class="text-info">OnTime(90)</span> + <span class="text-secondary">Late(75)</span>) / <span class="text-white">Total_Attendances</span>
                            </div>
                            <small class="text-white-50">Evaluates punctuality. 'Pioneer' is awarded to the absolute first arrival of the day.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-dumbbell me-2"></i>3. Perseverance Coefficient (<var>P</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">P</span> = (<span class="text-white">Avg_Duration_Mins</span> / <span class="text-white">Standard_Target_Mins</span>) × 100
                            </div>
                            <small class="text-white-50">Calculates resilience by comparing actual working hours against the baseline target.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-rocket me-2"></i>4. Ambition Metric (<var>A</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">A</span> = (<span class="text-white">Extra_Duration</span> × 0.5) + (<span class="text-white">Proactivity</span> × 0.5)
                            </div>
                            <small class="text-white-50">A blended metric measuring willingness to exceed standard expectations.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-hand-sparkles me-2"></i>5. Initiative Bonus (<var>I</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">I</span> = (<span class="text-white">Sat</span> × 1) + (<span class="text-white">Sun</span> × 2) + (<span class="text-white">Overtime_Checkout</span> × 0.5)
                            </div>
                            <small class="text-white-50">Direct bonus points appended to the total score for out-of-hours contributions.</small>
                        </div>

                        <hr class="border-secondary my-4">

                        <div class="mb-2">
                            <h5 class="fw-bold text-primary mb-3">Final Aggregation</h5>
                            <div class="p-3 rounded-3 mb-3" style="background: rgba(59,130,246,0.1); border: 1px dashed rgba(147,197,253,0.5);">
                                <strong class="text-info">{{ __('Final Grade') }} (Average):</strong> <br>
                                <span style="font-family: monospace; color: #bae6fd;">(<var>C</var> + <var>D</var> + <var>P</var> + <var>A</var>) / 4</span>
                            </div>
                            <div class="p-3 rounded-3" style="background: rgba(245,158,11,0.1); border: 1px dashed rgba(251,191,36,0.5);">
                                <strong class="text-warning">Total Performance Score:</strong> <br>
                                <span style="font-family: monospace; color: #fde68a;">(<var>C</var> + <var>D</var> + <var>P</var> + <var>A</var>) + <var>I</var></span>
                            </div>
                        </div>
                    </div>

                    {{-- INDONESIAN CONTENT --}}
                    <div class="tab-pane fade" id="id-content" role="tabpanel">
                        <p class="text-white-50 mb-4">Mesin evaluasi menggunakan algoritma penilaian multi-bobot untuk menentukan peringkat performa.</p>
                        
                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-calendar-check me-2"></i>1. Matriks Konsistensi (<var>C</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">C</span> = (<span class="text-white">Hari_Hadir</span> / <span class="text-white">Total_Hari_Kerja</span>) × 100
                            </div>
                            <small class="text-white-50">Mengukur tingkat kehadiran absolut terhadap hari operasional wajib.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-stopwatch me-2"></i>2. Indeks Kedisiplinan (<var>D</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">D</span> = ∑ (<span class="text-warning">Pionir(100)</span> + <span class="text-info">TepatWaktu(90)</span> + <span class="text-secondary">Telat(75)</span>) / <span class="text-white">Total_Kehadiran</span>
                            </div>
                            <small class="text-white-50">Mengevaluasi ketepatan waktu. 'Pionir' diberikan kepada orang pertama yang hadir di hari tersebut.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-dumbbell me-2"></i>3. Koefisien Ketekunan (<var>P</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">P</span> = (<span class="text-white">RataRata_Durasi</span> / <span class="text-white">Target_Standar</span>) × 100
                            </div>
                            <small class="text-white-50">Menghitung daya tahan dengan membandingkan jam kerja aktual terhadap target dasar.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-rocket me-2"></i>4. Metrik Ambisi (<var>A</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">A</span> = (<span class="text-white">Durasi_Ekstra</span> × 0.5) + (<span class="text-white">Proaktivitas</span> × 0.5)
                            </div>
                            <small class="text-white-50">Metrik campuran yang mengukur kemauan untuk melampaui ekspektasi standar.</small>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info fw-bold"><i class="fas fa-hand-sparkles me-2"></i>5. Bonus Inisiatif (<var>I</var>)</h6>
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); font-family: monospace;">
                                <span class="text-success">I</span> = (<span class="text-white">Sabtu</span> × 1) + (<span class="text-white">Minggu</span> × 2) + (<span class="text-white">Lembur</span> × 0.5)
                            </div>
                            <small class="text-white-50">Poin bonus langsung yang ditambahkan ke total skor atas kontribusi di luar jam kerja.</small>
                        </div>

                        <hr class="border-secondary my-4">

                        <div class="mb-2">
                            <h5 class="fw-bold text-primary mb-3">Agregasi Akhir</h5>
                            <div class="p-3 rounded-3 mb-3" style="background: rgba(59,130,246,0.1); border: 1px dashed rgba(147,197,253,0.5);">
                                <strong class="text-info">Nilai Akhir (Rata-rata):</strong> <br>
                                <span style="font-family: monospace; color: #bae6fd;">(<var>C</var> + <var>D</var> + <var>P</var> + <var>A</var>) / 4</span>
                            </div>
                            <div class="p-3 rounded-3" style="background: rgba(245,158,11,0.1); border: 1px dashed rgba(251,191,36,0.5);">
                                <strong class="text-warning">Total Skor Performa:</strong> <br>
                                <span style="font-family: monospace; color: #fde68a;">(<var>C</var> + <var>D</var> + <var>P</var> + <var>A</var>) + <var>I</var></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 justify-content-center">
                <button type="button" class="btn btn-outline-light rounded-pill px-5" data-bs-dismiss="modal">Tutup / Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Tab styling toggler
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.nav-link').forEach(t => {
                    t.classList.remove('bg-primary', 'bg-opacity-25', 'border-primary', 'text-white');
                    t.classList.add('text-white-50', 'border-secondary', 'bg-transparent');
                });
                this.classList.remove('text-white-50', 'border-secondary', 'bg-transparent');
                this.classList.add('bg-primary', 'bg-opacity-25', 'border-primary', 'text-white');
            });
        });

        // Render Radar Chart safely
        if (typeof Chart !== 'undefined' && typeof @json($data['chart_data']) !== 'undefined') {
            const rawChartData = @json($data['chart_data']);
            
            // Extract properly to array since pluck() might return objects if keys aren't sequential
            const labels = Array.isArray(rawChartData.labels) ? rawChartData.labels : Object.values(rawChartData.labels);
            const scores = Array.isArray(rawChartData.scores) ? rawChartData.scores : Object.values(rawChartData.scores);

            const ctx = document.getElementById('radarChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(96, 165, 250, 0.5)'); 
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Score',
                        data: scores,
                        backgroundColor: gradient,
                        borderColor: '#60a5fa',
                        pointBackgroundColor: '#eff6ff',
                        pointBorderColor: '#2563eb',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#1e3a8a',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: (context) => ` Score: ${context.parsed.r.toFixed(1)} / 100`
                            }
                        }
                    },
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            pointLabels: {
                                color: '#94a3b8',
                                font: { size: 12, weight: '500' }
                            },
                            ticks: {
                                display: false,
                                stepSize: 20
                            },
                            min: 0,
                            max: 100
                        }
                    }
                }
            });
        }
    });

    // Period filter: custom range handling
    function handlePeriodChange(select) {
        const customInputs = document.getElementById('customRangeInputs');
        if (select.value === 'custom') {
            customInputs.classList.remove('d-none');
        } else {
            customInputs.classList.add('d-none');
            select.form.submit();
        }
    }

    function applyCustomRange() {
        const start = document.getElementById('customStart').value;
        const end = document.getElementById('customEnd').value;
        if (!start || !end) { alert('Please select both start and end dates.'); return; }
        if (start > end) { alert('Start date must be before end date.'); return; }
        const select = document.getElementById('periodSelect');
        const opt = document.createElement('option');
        opt.value = start + '_to_' + end;
        opt.selected = true;
        select.appendChild(opt);
        select.form.submit();
    }

    // On page load, show custom inputs if custom range is active
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('periodSelect');
        if (select && select.value === 'custom') {
            document.getElementById('customRangeInputs').classList.remove('d-none');
        }
    });
</script>
@endpush
@endsection