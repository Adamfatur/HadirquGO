<div class="col-12 col-md-12">
    @php
        $rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
    @endphp
    <div class="card shadow-sm mb-4 p-3" style="border-radius: 15px; background-color: #ffffff; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-center mb-4">
                <div style="background-color: #1e3a8a; color: white; border-radius: 20px; padding: 8px 20px; font-size: 1rem;">
                    <i class="fas fa-trophy me-2"></i> <strong>Today's Highlights</strong>
                </div>
            </div>

            <div class="row">
                <!-- {{ __('Morning Person') }} -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="text-center" style="background: linear-gradient(135deg, #96e6a1, #d4fc79); border-radius: 10px; padding: 15px;">
                        <p class="small text-muted mb-1" style="color: #ffffff;"><i class="fas fa-sun me-2" style="color: #ffc107;"></i> {{ __('Morning Person') }}</p>
                        @if($morningPerson)
                            @php
                                $mpRankData = $morningPerson->user->leaderboards->first();
                                $mpRank = $mpRankData?->current_rank ?? 999;
                                $mpStyle = \App\Helpers\RankHelper::getRankStyle($mpRank, $mpRankData?->frame_color);
                                $mpGlow = ($mpRank <= 50) ? $mpStyle['glow'] : 'border: 2px solid #ffffff;';
                                $mpTitle = ($mpRank <= 50) ? $mpRankData?->title : null;
                                $mpUrl = $morningPerson->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $morningPerson->user->member_id]) : null;
                            @endphp
                            <div class="d-flex flex-column align-items-center">
                                @if($mpUrl)<a href="{{ $mpUrl }}" class="text-decoration-none">@endif
                                <img src="{{ $morningPerson->user->avatar ? (str_starts_with($morningPerson->user->avatar, 'http') ? $morningPerson->user->avatar : asset($morningPerson->user->avatar)) : asset('images/default-avatar.png') }}"
                                     alt="{{ $morningPerson->user->name }}"
                                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-bottom: 8px; {{ $mpGlow }}"
                                     class="transition-scale shadow-sm">
                                @if($mpUrl)</a>@endif
                                <h5 class="fw-bold mb-0 text-dark">
                                    @if($mpUrl)<a href="{{ $mpUrl }}" class="text-decoration-none text-dark">@endif
                                    {{ $morningPerson->user->name }}
                                    @if($mpUrl)</a>@endif
                                </h5>
                                @if($mpTitle)
                                    <div class="small mt-1 px-2 py-1 rounded-pill d-inline-flex align-items-center" style="font-size: 0.7rem; font-weight: 600; {{ $mpStyle['badge'] }}">
                                        <i class="fas fa-award me-1"></i> {{ $mpTitle }}
                                    </div>
                                @endif
                                <p class="small text-muted mb-0 mt-1">
                                    <i class="fas fa-clock me-1"></i> {{ $morningPerson->checkin_time->format('h:i A') }}
                                </p>
                            </div>
                        @else
                            <h5 class="fw-bold text-dark">N/A</h5>
                        @endif
                    </div>
                </div>

                <!-- Last Checkin User -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="text-center" style="background: linear-gradient(135deg, #ff8e8e, #fbc2eb); border-radius: 10px; padding: 15px;">
                        <p class="small text-muted mb-1" style="color: #ffffff;"><i class="fas fa-moon me-2" style="color: #6f42c1;"></i> {{ __('Last Person') }}</p>
                        @if($lastCheckinUser)
                            @php
                                $lcRankData = $lastCheckinUser->user->leaderboards->first();
                                $lcRank = $lcRankData?->current_rank ?? 999;
                                $lcStyle = \App\Helpers\RankHelper::getRankStyle($lcRank, $lcRankData?->frame_color);
                                $lcGlow = ($lcRank <= 50) ? $lcStyle['glow'] : 'border: 2px solid #ffffff;';
                                $lcTitle = ($lcRank <= 50) ? $lcRankData?->title : null;
                                $lcUrl = $lastCheckinUser->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $lastCheckinUser->user->member_id]) : null;
                            @endphp
                            <div class="d-flex flex-column align-items-center">
                                @if($lcUrl)<a href="{{ $lcUrl }}" class="text-decoration-none">@endif
                                <img src="{{ $lastCheckinUser->user->avatar ? (str_starts_with($lastCheckinUser->user->avatar, 'http') ? $lastCheckinUser->user->avatar : asset($lastCheckinUser->user->avatar)) : asset('images/default-avatar.png') }}"
                                     alt="{{ $lastCheckinUser->user->name }}"
                                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-bottom: 8px; {{ $lcGlow }}"
                                     class="transition-scale shadow-sm">
                                @if($lcUrl)</a>@endif
                                <h5 class="fw-bold mb-0 text-dark">
                                    @if($lcUrl)<a href="{{ $lcUrl }}" class="text-decoration-none text-dark">@endif
                                    {{ $lastCheckinUser->user->name }}
                                    @if($lcUrl)</a>@endif
                                </h5>
                                @if($lcTitle)
                                    <div class="small mt-1 px-2 py-1 rounded-pill d-inline-flex align-items-center" style="font-size: 0.7rem; font-weight: 600; {{ $lcStyle['badge'] }}">
                                        <i class="fas fa-award me-1"></i> {{ $lcTitle }}
                                    </div>
                                @endif
                                <p class="small text-muted mb-0 mt-1">
                                    <i class="fas fa-clock me-1"></i> {{ $lastCheckinUser->checkin_time->format('h:i A') }}
                                </p>
                            </div>
                        @else
                            <h5 class="fw-bold text-dark">N/A</h5>
                        @endif
                    </div>
                </div>

                <!-- Top Points User -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="text-center" style="background: linear-gradient(135deg, #96c2e6, #79fcce); border-radius: 10px; padding: 15px;">
                        <p class="small text-muted mb-1" style="color: #ffffff;"><i class="fas fa-stopwatch me-2" style="color: #28a745;"></i> Top Points</p>
                        @if($topPointsUser)
                            @php
                                $tpRankData = $topPointsUser->user->leaderboards->first();
                                $tpRank = $tpRankData?->current_rank ?? 999;
                                $tpStyle = \App\Helpers\RankHelper::getRankStyle($tpRank, $tpRankData?->frame_color);
                                $tpGlow = ($tpRank <= 50) ? $tpStyle['glow'] : 'border: 2px solid #ffffff;';
                                $tpTitle = ($tpRank <= 50) ? $tpRankData?->title : null;
                                $tpUrl = $topPointsUser->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $topPointsUser->user->member_id]) : null;
                            @endphp
                            <div class="d-flex flex-column align-items-center">
                                @if($tpUrl)<a href="{{ $tpUrl }}" class="text-decoration-none">@endif
                                <img src="{{ $topPointsUser->user->avatar ? (str_starts_with($topPointsUser->user->avatar, 'http') ? $topPointsUser->user->avatar : asset($topPointsUser->user->avatar)) : asset('images/default-avatar.png') }}"
                                     alt="{{ $topPointsUser->user->name }}"
                                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-bottom: 8px; {{ $tpGlow }}"
                                     class="transition-scale shadow-sm">
                                @if($tpUrl)</a>@endif
                                <h5 class="fw-bold mb-0 text-dark">
                                    @if($tpUrl)<a href="{{ $tpUrl }}" class="text-decoration-none text-dark">@endif
                                    {{ $topPointsUser->user->name }}
                                    @if($tpUrl)</a>@endif
                                </h5>
                                @if($tpTitle)
                                    <div class="small mt-1 px-2 py-1 rounded-pill d-inline-flex align-items-center" style="font-size: 0.7rem; font-weight: 600; {{ $tpStyle['badge'] }}">
                                        <i class="fas fa-award me-1"></i> {{ $tpTitle }}
                                    </div>
                                @endif
                                <p class="small text-muted mb-0 mt-1">
                                    <i class="fas fa-star me-1"></i> {{ $topPointsUser->total_points }} Points
                                </p>
                            </div>
                        @else
                            <h5 class="fw-bold text-dark">N/A</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
