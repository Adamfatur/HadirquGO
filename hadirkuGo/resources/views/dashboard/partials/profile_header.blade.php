<style>
    @keyframes shineGradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .level-banner-animate {
        background-size: 200% 200% !important;
        animation: shineGradient 4s ease infinite;
    }
    .level-card-wrapper {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .level-card-wrapper:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    @keyframes glowPulse {
        0% { box-shadow: 0 0 5px rgba(255, 255, 255, 0.4), 0 0 10px rgba(59, 130, 246, 0.6); }
        50% { box-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(59, 130, 246, 1); transform: scale(1.1); }
        100% { box-shadow: 0 0 5px rgba(255, 255, 255, 0.4), 0 0 10px rgba(59, 130, 246, 0.6); }
    }
    .progress-head-node {
        position: absolute; right: -4px; top: -2px; width: 12px; height: 12px; 
        background: white; border-radius: 50%; border: 2px solid #3b82f6; 
        animation: glowPulse 2s infinite;
    }
</style>

@php
    // Elite Rank Visual Metadata
    $dRankData = Auth::user()->leaderboards->first();
    $dbRank = $dRankData?->current_rank ?? 999;
    $dRankStyle = \App\Helpers\RankHelper::getRankStyle($dbRank, $dRankData?->frame_color);
    $dRankClass = ($dbRank <= 50) ? $dRankStyle['class'] : 'border-primary';
    $dGlowClass = ($dbRank <= 50) ? $dRankStyle['glow'] : '';
    $dIconColor = ($dbRank <= 50) ? $dRankStyle['iconColor'] : 'text-primary';
    $dbTitle = ($dbRank <= 50) ? $dRankData?->title : null;
    $dTitleBadgeStyle = ($dbRank <= 50) ? $dRankStyle['badge'] : '';

    $levelGradients = [
        'Pioneer' => 'linear-gradient(135deg, #8b5cf6, #3b82f6, #8b5cf6)',
        'Academic Voyager' => 'linear-gradient(135deg, #9ca3af, #d1d5db, #9ca3af)',
        'Scholastic Trailblazer' => 'linear-gradient(135deg, #ca8a04, #fde047, #ca8a04)',
        'Intellectual Pathfinder' => 'linear-gradient(135deg, #0f766e, #2dd4bf, #0f766e)',
        'Knowledge Vanguard' => 'linear-gradient(135deg, #1d4ed8, #38bdf8, #1d4ed8)',
        'Master of Attendance' => 'linear-gradient(135deg, #b91c1c, #f87171, #b91c1c)',
        'Savants of the Semester' => 'linear-gradient(135deg, #7e22ce, #c084fc, #7e22ce)',
        'Attendance Luminary' => 'linear-gradient(135deg, #4338ca, #ec4899, #4338ca)',
        'Legendary Learner' => 'linear-gradient(135deg, #ea580c, #ef4444, #eab308, #ea580c)',
    ];

    $levels = \App\Models\Level::orderBy('minimum_points', 'asc')->get();
    $currentLevel = null;
    foreach ($levels as $level) {
        if ($totalPoints >= $level->minimum_points && $totalPoints <= $level->maximum_points) {
            $currentLevel = $level;
            break;
        }
    }
    if (!$currentLevel) {
        $currentLevel = \App\Models\Level::where('name', 'like', 'Pioneer%')->first();
    }
    $baseLevelName = preg_replace('/\s+[IVX]+$/', '', $currentLevel->name);
    $currentLevelGradient = $levelGradients[$baseLevelName] ?? 'linear-gradient(45deg, #3b3b98, #4c4cff)';
@endphp

<div class="card shadow-sm mb-4 animate-card level-card-wrapper border-0"
     style="background: white; border-radius: 24px; overflow: hidden; position: relative; border: 1px solid #e2e8f0;">
    
    <div class="card-body p-3 p-md-4">
        <div class="row g-3">
            <!-- Profile Card -->
            <div class="col-12 col-md-7">
                <div class="card border shadow-sm rounded-4 h-100" style="background: white; border-color: #e2e8f0 !important;">
                    <div class="card-body p-2 p-md-3 d-flex align-items-center">
                        @php 
                            $rolePrefix = strtolower(Auth::user()->role) === 'lecturer' ? 'lecturer' : 'student';
                            $myTopEvalUrl = Auth::user()->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => Auth::user()->member_id]) : null; 
                        @endphp
                        <div class="position-relative me-3 me-md-4">
                            @if($myTopEvalUrl)<a href="{{ $myTopEvalUrl }}" class="text-decoration-none">@endif
                            <img src="{{ Auth::user()->avatar ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar)) : asset('images/default-avatar.png') }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="rounded-circle {{ $dRankClass }}"
                                 style="width: 85px; height: 85px; object-fit: cover; {{ $dGlowClass }}">
                            @if($myTopEvalUrl)</a>@endif
                            @if($dbRank <= 3)
                                <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $dIconColor }}" style="font-size: 1.8rem; filter: drop-shadow(0 3px 5px rgba(0,0,0,0.3)); transform: translate(-50%, -70%) !important; z-index: 10;"></i>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="fw-bold mb-1 text-dark text-truncate" style="font-size: 1.4rem;">
                                @if($myTopEvalUrl)<a href="{{ $myTopEvalUrl }}" class="text-decoration-none text-dark">@endif
                                {{ Auth::user()->name }}
                                @if($myTopEvalUrl)</a>@endif
                            </h3>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @if($dbTitle)
                                    <div class="small px-3 py-1 rounded-pill d-inline-flex align-items-center" style="font-size: 0.8rem; font-weight: 700; {{ $dTitleBadgeStyle }}">
                                        <i class="fas fa-award me-1"></i> {{ $dbTitle }}
                                    </div>
                                @endif
                                <span class="badge rounded-pill px-3 py-1" style="font-size: 0.75rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 800;">
                                    LEVEL {{ \App\Helpers\RankHelper::getLevelNumber(Auth::user()) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="col-12 col-md-5">
                <div class="card border shadow-sm rounded-4 h-100" style="background: white; border-color: #e2e8f0 !important;">
                    <div class="card-body p-2 p-md-3 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="small text-muted mb-0 fw-bold text-uppercase" style="letter-spacing: 0.8px; font-size: 0.65rem;">{{ __('Tesla Points') }}</p>
                                <div class="d-flex align-items-center mt-1">
                                    <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla" style="width: 28px; height: auto;" class="me-2">
                                    <h2 class="fw-bold mb-0 text-dark" style="font-size: 1.8rem; letter-spacing: -0.5px;">{{ number_format($totalPoints) }}</h2>
                                </div>
                            </div>
                            <a href="{{ route('lecturer.attendance.history') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: #f8fafc;">
                                <i class="fas fa-history text-primary" style="font-size: 1.1rem;"></i>
                            </a>
                        </div>
                        
                        @php
                            $rolePrefix = strtolower(Auth::user()->role) === 'lecturer' ? 'lecturer' : 'student';
                        @endphp
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="small text-muted mb-0 fw-bold text-uppercase" style="letter-spacing: 0.8px; font-size: 0.65rem;">{{ __('Weekly Ranking') }}</p>
                                    @if($rank !== 'N/A')
                                        <h3 class="fw-bold mb-0 text-primary" style="font-size: 1.6rem;">#{{ $rank }}</h3>
                                    @else
                                        <span class="badge bg-light text-muted rounded-pill mt-1">Pending</span>
                                    @endif
                                </div>
                                @if($rank !== 'N/A')
                                    <div class="text-end">
                                        <p class="small text-muted mb-0" style="font-size: 0.65rem;">vs <strong>{{ \App\Models\WeeklyRanking::where('week_start_date', \Carbon\Carbon::now()->startOfWeek())->count() }}</strong> others</p>
                                        <a href="{{ route($rolePrefix . '.viewboard.top-levels') }}" class="small fw-bold text-decoration-none d-flex align-items-center justify-content-end" style="font-size: 0.75rem;">
                                            {{ __('Leaderboard') }} <i class="fas fa-chevron-right ms-1" style="font-size: 0.6rem;"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Activity -->
        @if($activeAttendance ?? '')
        <div class="mt-3">
            <div class="card border-0 shadow-sm rounded-4" style="background: #f0fdf4; border: 1px solid #dcfce7 !important;">
                <div class="card-body p-2 px-md-3">
                    <div class="row align-items-center g-3 text-center text-md-start">
                        <div class="col-12 col-md-auto">
                            <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill bg-success text-white small fw-bold">
                                <span class="pulse-animation me-2"></span> {{ __('LIVE ACTIVITY') }}
                            </div>
                        </div>
                        <div class="col-4 col-md-auto ms-md-auto">
                            <p class="small text-muted mb-0 text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">{{ __('Check-In') }}</p>
                            <h6 class="fw-bold mb-0 text-success">{{ $checkInTime ? $checkInTime->format('h:i A') : 'N/A' }}</h6>
                        </div>
                        <div class="col-4 col-md-auto border-start border-end border-light px-3">
                            <p class="small text-muted mb-0 text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">{{ __('Elapsed') }}</p>
                            <h6 class="fw-bold mb-0 text-primary">
                                {{ $elapsedMinutes > 60 ? floor($elapsedMinutes / 60) . 'h ' . ($elapsedMinutes % 60) . 'm' : $elapsedMinutes . 'm' }}
                            </h6>
                        </div>
                        <div class="col-4 col-md-auto">
                            <p class="small text-muted mb-0 text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">{{ __('Location') }}</p>
                            <h6 class="fw-bold mb-0 text-danger text-truncate" style="max-width: 150px;">{{ $currentLocation ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .pulse-animation { width: 8px; height: 8px; background: white; border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite; }
            @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(255, 255, 255, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); } }
        </style>
        @endif

        <!-- Progress Bar & Action Button -->
        <div class="mt-4 px-1">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    @php
                        $nextLevel = \App\Models\Level::where('minimum_points', '>', $totalPoints)->orderBy('minimum_points', 'asc')->first();
                    @endphp
                    @if($nextLevel)
                        @php
                            $minPts = $currentLevel->minimum_points ?? 0;
                            $maxPts = $nextLevel->minimum_points ?? 100;
                            $progress = (($totalPoints - $minPts) / ($maxPts - $minPts)) * 100;
                            $progress = max(0, min(100, $progress));
                        @endphp
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div>
                                <span class="fw-bold text-primary" style="font-size: 0.95rem;">Next: {{ $nextLevel->name }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill fw-bold" style="font-size: 0.8rem; background: #fffbeb; color: #92400e;">
                                    ⚡ {{ number_format($maxPts - $totalPoints) }} pts left
                                </span>
                            </div>
                        </div>
                        <div class="progress rounded-pill shadow-sm" style="height: 12px; background: #f1f5f9;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill" 
                                 role="progressbar" 
                                 style="width: {{ $progress }}%; background: {{ $currentLevelGradient }}; position: relative;" 
                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                @if($progress > 0)
                                <div class="progress-head-node" style="width: 16px; height: 16px; top: -2px; right: -8px;"></div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-white rounded-4 shadow-sm text-center border border-success border-opacity-10">
                            <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                <i class="fas fa-crown me-2 text-warning"></i> {{ __('Master Level Reached!') }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route($rolePrefix . '.attendance.stats', ['memberId' => Auth::user()->member_id]) }}"
                       class="btn btn-warning w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center py-2"
                       style="border-radius: 16px; font-size: 0.9rem; border: none; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; height: 45px;">
                        <i class="fas fa-map-marked-alt me-2"></i> {{ __('View My Journey') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Banner -->
    <div class="level-banner-animate mt-2 py-2 text-center text-white" style="background: {{ $currentLevelGradient }}; border-top: 1px solid rgba(255,255,255,0.15);">
        <p class="small mb-0 fw-bold" style="letter-spacing: 0.8px; font-size: 0.85rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
            <i class="fas fa-medal me-2"></i> {{ __('You are currently in') }} <strong>{{ $currentLevel->name }}</strong> rank
        </p>
    </div>
</div>
