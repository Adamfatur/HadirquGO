@php
use App\Helpers\RankHelper;
$rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
@endphp

<style>
    .battle-bar-container { height: 16px; border-radius: 20px; background: #f1f5f9; position: relative; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    .battle-bar-me { height: 100%; background: linear-gradient(90deg, #1d4ed8, #3b82f6); transition: width 1s ease; position: relative; }
    .battle-bar-rival { height: 100%; background: linear-gradient(90deg, #ef4444, #b91c1c); transition: width 1s ease; }
    .battle-center-line { position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; background: rgba(255,255,255,0.4); z-index: 5; transform: translateX(-50%); }
    .battle-indicator { position: absolute; top: 50%; transform: translateY(-50%); font-size: 10px; font-weight: 800; color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.3); z-index: 6; }
    
    .standing-item { padding: 12px; border-radius: 16px; transition: all 0.3s ease; border: 1px solid #f1f5f9; background: #fff; }
    .standing-item:hover { transform: translateX(5px); border-color: #dbeafe; }
    .standing-avatar { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 2px solid white; }
    .standing-avatar-large { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 3px solid white; }
    .standing-name { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
    .standing-rank { font-size: 0.85rem; font-weight: 800; color: #64748b; }
    
    .rivalry-card { background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; height: 100%; }
    .standing-card { background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; height: 100%; }
    
    @keyframes shine-anim {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    .shine-banner { position: relative; overflow: hidden; }
    .shine-banner::after {
        content: ""; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.3), transparent);
        transform: skewX(-25deg); animation: shine-anim 3s infinite;
    }
</style>

@if(isset($leaderboardContext))
    @php
        $rank = $leaderboardContext['rank'];
        $bannerTitle = '';
        $bannerSub = '';
        $bannerGradient = '';
        $showBanner = false;
        
        if ($rank == 1) {
            $bannerGradient = 'linear-gradient(135deg, #fbbf24, #f59e0b)';
            $bannerTitle = '👑 THE ULTIMATE CHAMPION 👑';
            $bannerSub = 'You are the #1 Player! Incredible dedication!';
            $showBanner = true;
        } elseif ($rank <= 3) {
            $bannerGradient = 'linear-gradient(135deg, #94a3b8, #64748b)';
            $bannerTitle = '🥈 Podium Finisher 🥉';
            $bannerSub = 'You are in the TOP 3! Keep defending your throne!';
            $showBanner = true;
        } elseif ($rank <= 10) {
            $bannerGradient = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
            $bannerTitle = '✨ Elite TOP 10 ✨';
            $bannerSub = 'Outstanding! You are among the absolute best players.';
            $showBanner = true;
        } elseif ($rank <= 50) {
            $bannerGradient = 'linear-gradient(135deg, #ec4899, #be185d)';
            $bannerTitle = '🔥 TOP 50 WARRIOR 🔥';
            $bannerSub = 'Great job! You have secured a spot in the official Top 50.';
            $showBanner = true;
        }
    @endphp

    @if($showBanner)
        <div class="card shadow-sm mb-4 border-0 shine-banner" 
             style="border-radius: 16px; background: {{ $bannerGradient }}; color: white;">
            <div class="card-body py-3 text-center">
                <h5 class="fw-bold mb-1" style="font-size: 1.1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ $bannerTitle }}</h5>
                <p class="small mb-0 opacity-90">{{ $bannerSub }}</p>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Standing Column -->
        <div class="col-12 col-lg-7">
            <div class="standing-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-trophy me-2 text-warning"></i>{{ __('Leaderboard') }} Standing
                        </h6>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold" style="font-size: 0.85rem;">Rank #{{ number_format($rank) }}</span>
                    </div>

                    <div class="standing-list">
                        <!-- Above -->
                        @if($leaderboardContext['above'])
                            @php 
                                $above = $leaderboardContext['above'];
                                $aboveStyle = RankHelper::getRankStyle($above->current_rank, $above->frame_color);
                                $aboveEvalUrl = $above->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $above->user->member_id]) : null;
                            @endphp
                            <div class="standing-item mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        @if($aboveEvalUrl)<a href="{{ $aboveEvalUrl }}">@endif
                                        <img src="{{ $above->user->avatar ? (str_starts_with($above->user->avatar, 'http') ? $above->user->avatar : asset($above->user->avatar)) : asset('images/default-avatar.png') }}" 
                                             class="standing-avatar {{ $aboveStyle['class'] }}" style="{{ $aboveStyle['glow'] }}">
                                        @if($aboveEvalUrl)</a>@endif
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="text-truncate me-2">
                                                <div class="standing-name text-truncate">
                                                    {{ $above->user->name }}
                                                    <span class="badge rounded-pill ms-1" style="font-size: 0.65rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 800;">Lvl {{ RankHelper::getLevelNumber($above->user) }}</span>
                                                </div>
                                                <div style="transform: scale(0.85); transform-origin: left;">{!! RankHelper::getTitleBadge($above->title, $above->current_rank, $above->frame_color) !!}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="standing-rank">#{{ $above->current_rank }}</span>
                                                <div class="small fw-bold text-primary" style="font-size: 0.75rem;">{{ number_format($above->score) }} pts</div>
                                            </div>
                                        </div>
                                        <div class="text-end mt-1">
                                            <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 0.65rem;">
                                                <i class="fas fa-caret-up me-1"></i>{{ number_format($above->score - $leaderboardContext['score']) }} to catch up
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- YOU -->
                        @php 
                            $myStyle = RankHelper::getRankStyle($rank, $leaderboardContext['myEntry']->frame_color ?? null);
                            $myEvalUrl = Auth::user()->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => Auth::user()->member_id]) : null;
                        @endphp
                        <div class="standing-item my-3 shadow-sm border-0" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; transform: scale(1.02); position: relative; z-index: 2;">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    @if($myEvalUrl)<a href="{{ $myEvalUrl }}">@endif
                                    <img src="{{ Auth::user()->avatar ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar)) : asset('images/default-avatar.png') }}" 
                                         class="standing-avatar-large {{ $myStyle['class'] }}" style="{{ $myStyle['glow'] }}">
                                    @if($myEvalUrl)</a>@endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="standing-name text-white">
                                            YOU
                                            <span class="badge rounded-pill ms-1" style="font-size: 0.75rem; background: white; color: #1e3a8a; border: 1px solid rgba(255,255,255,0.3); font-weight: 800;">Lvl {{ RankHelper::getLevelNumber(Auth::user()) }}</span>
                                        </div>
                                        <span class="badge bg-warning text-dark px-2 py-1 fw-bold" style="font-size: 0.9rem; border-radius: 8px;">#{{ $rank }}</span>
                                    </div>
                                    <div class="small mt-1 opacity-90 fw-bold">{{ number_format($leaderboardContext['score']) }} Points Accumulated</div>
                                </div>
                            </div>
                        </div>

                        <!-- Below -->
                        @if($leaderboardContext['below'])
                            @php 
                                $below = $leaderboardContext['below'];
                                $belowStyle = RankHelper::getRankStyle($below->current_rank, $below->frame_color);
                                $belowEvalUrl = $below->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $below->user->member_id]) : null;
                            @endphp
                            <div class="standing-item mt-2">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        @if($belowEvalUrl)<a href="{{ $belowEvalUrl }}">@endif
                                        <img src="{{ $below->user->avatar ? (str_starts_with($below->user->avatar, 'http') ? $below->user->avatar : asset($below->user->avatar)) : asset('images/default-avatar.png') }}" 
                                             class="standing-avatar {{ $belowStyle['class'] }}" style="{{ $belowStyle['glow'] }}">
                                        @if($belowEvalUrl)</a>@endif
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="text-truncate me-2">
                                                <div class="standing-name text-truncate">
                                                    {{ $below->user->name }}
                                                    <span class="badge rounded-pill ms-1" style="font-size: 0.65rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 800;">Lvl {{ RankHelper::getLevelNumber($below->user) }}</span>
                                                </div>
                                                <div style="transform: scale(0.85); transform-origin: left;">{!! RankHelper::getTitleBadge($below->title, $below->current_rank, $below->frame_color) !!}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="standing-rank">#{{ $below->current_rank }}</span>
                                                <div class="small fw-bold text-primary" style="font-size: 0.75rem;">{{ number_format($below->score) }} pts</div>
                                            </div>
                                        </div>
                                        <div class="text-end mt-1">
                                            <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.65rem;">
                                                <i class="fas fa-caret-down me-1"></i>{{ number_format($leaderboardContext['score'] - $below->score) }} lead
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Rivalry Column -->
        <div class="col-12 col-lg-5">
            <div class="rivalry-card shadow-sm">
                <div class="card-body p-4 d-flex flex-column">
                    <h6 class="fw-bold mb-1 text-dark">
                        <i class="fas fa-swords me-2 text-danger"></i>Points Rivalry
                    </h6>
                    <p class="small text-muted mb-3 border-bottom pb-2">Compare your performance directly.</p>

                    @if(isset($rivalContext))
                        @php
                            $rivalRankData = $rivalContext['user']->leaderboards->first();
                            $rivalRank = $rivalRankData?->current_rank ?? 999;
                            $rivalStyle = \App\Helpers\RankHelper::getRankStyle($rivalRank, $rivalRankData?->frame_color);
                        @endphp
                        <div class="p-3 rounded-4 mb-3 border" style="background: #f8fafc;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-2">
                                        <img src="{{ ($rivalContext['user']->avatar && str_starts_with($rivalContext['user']->avatar, 'http')) ? $rivalContext['user']->avatar : ($rivalContext['user']->avatar ? asset($rivalContext['user']->avatar) : asset('images/default-avatar.png')) }}" 
                                             class="rounded-circle {{ $rivalStyle['class'] }}" style="width: 50px; height: 50px; object-fit: cover; {{ $rivalStyle['glow'] }}">
                                        <span class="position-absolute top-0 start-0 badge rounded-pill bg-danger" style="font-size: 0.5rem; transform: translate(-20%, -20%);">TARGET</span>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate small" style="max-width: 100px;">
                                            {{ $rivalContext['user']->name }}
                                            <span class="badge rounded-pill ms-1" style="font-size: 0.6rem; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: white; font-weight: 800;">Lvl {{ RankHelper::getLevelNumber($rivalContext['user']) }}</span>
                                        </h6>
                                        <p class="small text-muted mb-0" style="font-size: 0.7rem;">{{ number_format($rivalContext['score']) }} pts</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="badge {{ $rivalContext['diff'] >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-2 py-1 mb-1" style="font-size: 0.7rem;">
                                        {{ $rivalContext['diff'] >= 0 ? 'Lead' : 'Chasing' }}
                                    </div>
                                    <div class="small fw-bold text-dark" style="font-size: 0.8rem;">{{ number_format(abs($rivalContext['diff'])) }}</div>
                                </div>
                            </div>

                            <div class="battle-bar-container mt-2">
                                @php
                                    $myP = $leaderboardContext['score'] ?? 0;
                                    $rP = $rivalContext['score'] ?? 0;
                                    $totalPool = max($myP + $rP, 1);
                                    $myW = ($myP / $totalPool) * 100;
                                    $rivalW = 100 - $myW;
                                @endphp
                                <div class="d-flex h-100">
                                    <div class="battle-bar-me" style="width: {{ $myW }}%">
                                        @if($myW > 30) <span class="battle-indicator" style="left: 8px;">YOU</span> @endif
                                    </div>
                                    <div class="battle-bar-rival" style="width: {{ $rivalW }}%">
                                        @if($rivalW > 30) <span class="battle-indicator" style="right: 8px;">RIVAL</span> @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route($rolePrefix . '.dashboard.updateRival') }}" method="POST" class="mt-auto">
                        @csrf
                        <div class="input-group input-group-sm mb-2 shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                            <input type="text" id="rivalSearchInput" class="form-control border-0 px-2" placeholder="Search rival..." autocomplete="off" style="font-size: 0.85rem; box-shadow: none;">
                            <input type="hidden" name="rival_id" id="rivalIdInput">
                            <button type="submit" class="btn btn-warning fw-bold px-3">SET</button>
                        </div>
                        <div id="rivalSearchResults" class="list-group shadow position-absolute w-100 mt-1 d-none" style="z-index: 1000; border-radius: 10px; overflow: hidden;"></div>
                    </form>

                    <div class="mt-2">
                        <label class="small fw-bold text-muted mb-1" style="font-size: 0.65rem;">SUGGESTIONS:</label>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($recommendedRivals->take(3) as $u)
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-2 py-0 fw-bold" onclick="selectRival('{{ $u->id }}', '{{ $u->name }}')" style="font-size: 0.65rem; border-width: 1px;">{{ $u->name }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('rivalSearchInput');
        const list = document.getElementById('rivalSearchResults');
        if (!input || !list) return;
        input.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 2) { list.classList.add('d-none'); return; }
            fetch("{{ route($rolePrefix . '.dashboard.searchRivals') }}?query=" + encodeURIComponent(query))
                .then(r => r.json()).then(data => {
                    list.innerHTML = '';
                    if (data.length === 0) list.innerHTML = '<div class="list-group-item small">No users found</div>';
                    else data.forEach(u => {
                        const avatar = u.avatar ? (u.avatar.startsWith('http') ? u.avatar : '/' + u.avatar) : '/images/default-avatar.png';
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action d-flex align-items-center py-2';
                        item.innerHTML = `<img src="${avatar}" class="rounded-circle me-2" style="width: 25px; height: 25px; object-fit: cover;"><span class="small fw-bold">${u.name}</span>`;
                        item.onclick = () => { selectRival(u.id, u.name); list.classList.add('d-none'); };
                        list.appendChild(item);
                    });
                    list.classList.remove('d-none');
                });
        });
        document.addEventListener('click', e => { if (!input.contains(e.target) && !list.contains(e.target)) list.classList.add('d-none'); });
    });
    window.selectRival = (id, name) => {
        document.getElementById('rivalIdInput').value = id;
        document.getElementById('rivalSearchInput').value = name;
    };
})();
</script>
