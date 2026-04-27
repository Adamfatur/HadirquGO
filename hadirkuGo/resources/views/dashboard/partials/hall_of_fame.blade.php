@php
use App\Helpers\RankHelper;
$rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
@endphp

<style>
    .hall-of-fame-card { background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; }
    .hof-title { background: #1e3a8a; color: white; border-radius: 20px; padding: 6px 16px; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.5px; display: inline-flex; align-items: center; }
    
    .podium-item { position: relative; transition: transform 0.3s ease; }
    .podium-item:hover { transform: translateY(-5px); }
    .podium-name-hof { font-size: 0.75rem; font-weight: 800; line-height: 1.2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.4em; }
    .podium-base-hof { border-top: 4px solid; border-radius: 12px 12px 8px 8px; background: linear-gradient(180deg, rgba(255,255,255,0.8), #fff); }
    
    .highlight-box { border-radius: 16px; padding: 12px; height: 100%; transition: transform 0.3s ease; }
    .highlight-box:hover { transform: scale(1.03); }
    .highlight-icon-circle { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
    .highlight-name { font-size: 0.9rem; font-weight: 700; color: #1e293b; line-height: 1.2; }
</style>

<div class="hall-of-fame-card shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Left Side: Top Global Podium -->
            <div class="col-12 col-lg-6 border-end-lg">
                <div class="text-center mb-4">
                    <div class="hof-title shadow-sm">
                        <i class="fas fa-trophy me-2 text-warning"></i> {{ __('TOP GLOBAL PLAYERS') }}
                    </div>
                </div>

                @if(isset($topUsers) && $topUsers->count() > 0)
                    @php
                        $top3 = $topUsers->values();
                        $podium = collect([null, null, null]);
                        if($top3->count() >= 1) $podium[1] = $top3[0]; // 1st
                        if($top3->count() >= 2) $podium[0] = $top3[1]; // 2nd
                        if($top3->count() >= 3) $podium[2] = $top3[2]; // 3rd
                    @endphp
                    <div class="row align-items-end g-2">
                        @foreach($podium as $pos => $item)
                            @if($item)
                                @php
                                    $actualRank = $pos == 1 ? 1 : ($pos == 0 ? 2 : 3);
                                    $isFirst = $actualRank == 1;
                                    $podiumRankData = $item->user->leaderboards->first();
                                    $pStyle = RankHelper::getRankStyle($actualRank, $podiumRankData?->frame_color);
                                    $frameColor = $podiumRankData?->frame_color ?? ($actualRank == 1 ? '#fbbf24' : ($actualRank == 2 ? '#9ca3af' : '#cd7f32'));
                                    $evalUrl = $item->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $item->user->member_id]) : null;
                                @endphp
                                <div class="col-4">
                                    <div class="podium-item text-center">
                                        <div class="mb-1" style="font-size: 1.2rem;">
                                            @if($actualRank == 1) 👑 @elseif($actualRank == 2) 🥈 @else 🥉 @endif
                                        </div>
                                        @if($evalUrl)<a href="{{ $evalUrl }}" class="text-decoration-none">@endif
                                            <div class="position-relative d-inline-block mb-2">
                                                <img src="{{ ($item->user->avatar && str_starts_with($item->user->avatar, 'http')) ? $item->user->avatar : ($item->user->avatar ? asset($item->user->avatar) : asset('images/default-avatar.png')) }}" 
                                                     class="rounded-circle {{ $pStyle['class'] }}" 
                                                     style="width: {{ $isFirst ? '60px' : '45px' }}; height: {{ $isFirst ? '60px' : '45px' }}; object-fit: cover; {{ $pStyle['glow'] }}">
                                            </div>
                                            <div class="podium-base-hof shadow-sm p-2" style="border-top-color: {{ $frameColor }}; min-height: {{ $isFirst ? '100px' : '80px' }};">
                                                <div class="podium-name-hof text-dark">
                                                    {{ $item->user->name }}
                                                </div>
                                                <div class="badge rounded-pill mb-1" style="font-size: 0.6rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; border: 1px solid rgba(255,255,255,0.2); font-weight: 800; padding: 2px 6px;">Lvl {{ RankHelper::getLevelNumber($item->user) }}</div>
                                                <div class="fw-bold text-primary" style="font-size: 0.8rem;">{{ number_format($item->score ?? $item->total_points) }}</div>
                                                <div class="small text-muted" style="font-size: 0.6rem;">Pts</div>
                                            </div>
                                        @if($evalUrl)</a>@endif
                                    </div>
                                </div>
                            @else
                                <div class="col-4"></div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Side: Today's Highlights -->
            <div class="col-12 col-lg-6">
                <div class="text-center mb-4">
                    <div class="hof-title shadow-sm" style="background: #047857;">
                        <i class="fas fa-bolt me-2 text-warning"></i> {{ __('TODAY\'S HIGHLIGHTS') }}
                    </div>
                </div>

                <div class="row g-2">
                    <!-- Morning -->
                    <div class="col-4">
                        <div class="highlight-box shadow-sm text-center" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); border: 1px solid #86efac;">
                            <div class="d-flex justify-content-center">
                                <div class="highlight-icon-circle"><i class="fas fa-sun text-warning"></i></div>
                            </div>
                            <p class="small fw-bold text-success-emphasis mb-1" style="font-size: 0.6rem;">{{ __('Morning Person') }}</p>
                            @if($morningPerson)
                                @php $mpUrl = $morningPerson->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $morningPerson->user->member_id]) : null; @endphp
                                @if($mpUrl)<a href="{{ $mpUrl }}" class="text-decoration-none">@endif
                                    <img src="{{ $morningPerson->user->avatar ? (str_starts_with($morningPerson->user->avatar, 'http') ? $morningPerson->user->avatar : asset($morningPerson->user->avatar)) : asset('images/default-avatar.png') }}" 
                                         class="rounded-circle mb-1 border border-white" style="width: 35px; height: 35px; object-fit: cover;">
                                    <div class="highlight-name text-truncate">{{ $morningPerson->user->name }}</div>
                                    <div class="badge rounded-pill mb-1" style="font-size: 0.55rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; font-weight: 800; padding: 1px 4px;">Lvl {{ RankHelper::getLevelNumber($morningPerson->user) }}</div>
                                    <div class="small text-muted" style="font-size: 0.6rem;">{{ $morningPerson->checkin_time->format('h:i A') }}</div>
                                @if($mpUrl)</a>@endif
                            @else
                                <div class="text-muted small">N/A</div>
                            @endif
                        </div>
                    </div>

                    <!-- Last -->
                    <div class="col-4">
                        <div class="highlight-box shadow-sm text-center" style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fecaca;">
                            <div class="d-flex justify-content-center">
                                <div class="highlight-icon-circle"><i class="fas fa-moon text-primary"></i></div>
                            </div>
                            <p class="small fw-bold text-danger-emphasis mb-1" style="font-size: 0.6rem;">{{ __('Last Person') }}</p>
                            @if($lastCheckinUser)
                                @php $lcUrl = $lastCheckinUser->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $lastCheckinUser->user->member_id]) : null; @endphp
                                @if($lcUrl)<a href="{{ $lcUrl }}" class="text-decoration-none">@endif
                                    <img src="{{ $lastCheckinUser->user->avatar ? (str_starts_with($lastCheckinUser->user->avatar, 'http') ? $lastCheckinUser->user->avatar : asset($lastCheckinUser->user->avatar)) : asset('images/default-avatar.png') }}" 
                                         class="rounded-circle mb-1 border border-white" style="width: 35px; height: 35px; object-fit: cover;">
                                    <div class="highlight-name text-truncate">{{ $lastCheckinUser->user->name }}</div>
                                    <div class="badge rounded-pill mb-1" style="font-size: 0.55rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; font-weight: 800; padding: 1px 4px;">Lvl {{ RankHelper::getLevelNumber($lastCheckinUser->user) }}</div>
                                    <div class="small text-muted" style="font-size: 0.6rem;">{{ $lastCheckinUser->checkin_time->format('h:i A') }}</div>
                                @if($lcUrl)</a>@endif
                            @else
                                <div class="text-muted small">N/A</div>
                            @endif
                        </div>
                    </div>

                    <!-- Top Points -->
                    <div class="col-4">
                        <div class="highlight-box shadow-sm text-center" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe;">
                            <div class="d-flex justify-content-center">
                                <div class="highlight-icon-circle"><i class="fas fa-star text-primary"></i></div>
                            </div>
                            <p class="small fw-bold text-primary-emphasis mb-1" style="font-size: 0.6rem;">Top Points</p>
                            @if($topPointsUser)
                                @php $tpUrl = $topPointsUser->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $topPointsUser->user->member_id]) : null; @endphp
                                @if($tpUrl)<a href="{{ $tpUrl }}" class="text-decoration-none">@endif
                                    <img src="{{ $topPointsUser->user->avatar ? (str_starts_with($topPointsUser->user->avatar, 'http') ? $topPointsUser->user->avatar : asset($topPointsUser->user->avatar)) : asset('images/default-avatar.png') }}" 
                                         class="rounded-circle mb-1 border border-white" style="width: 35px; height: 35px; object-fit: cover;">
                                    <div class="highlight-name text-truncate">{{ $topPointsUser->user->name }}</div>
                                    <div class="badge rounded-pill mb-1" style="font-size: 0.55rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; font-weight: 800; padding: 1px 4px;">Lvl {{ RankHelper::getLevelNumber($topPointsUser->user) }}</div>
                                    <div class="small text-muted" style="font-size: 0.6rem;">{{ number_format($topPointsUser->total_points) }} Pts</div>
                                @if($tpUrl)</a>@endif
                            @else
                                <div class="text-muted small">N/A</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 992px) {
        .border-end-lg { border-right: 1px solid #e2e8f0; }
    }
</style>
