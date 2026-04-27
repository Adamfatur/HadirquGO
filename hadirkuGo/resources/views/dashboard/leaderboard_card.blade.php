@php
use App\Helpers\RankHelper;
$rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
@endphp

<style>
    .battle-bar-container { height: 16px; border-radius: 20px; background: #e2e8f0; position: relative; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
    .battle-bar-me { height: 100%; background: linear-gradient(90deg, #1e3a8a, #3b82f6); transition: width 1s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; }
    .battle-bar-rival { height: 100%; background: linear-gradient(90deg, #ef4444, #b91c1c); transition: width 1s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .battle-center-line { position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; background: rgba(255,255,255,0.5); z-index: 5; transform: translateX(-50%); }
    .battle-indicator { position: absolute; top: 50%; transform: translateY(-50%); font-size: 11px; font-weight: 800; color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.5); z-index: 6; }
    .winning-pulse { animation: activePulse 2s infinite; }
    @keyframes activePulse { 0% { opacity: 1; } 50% { opacity: 0.8; } 100% { opacity: 1; } }
    
    /* Improved Standing List */
    .standing-item { padding: 16px; border-radius: 20px; transition: all 0.3s ease; border: 1px solid #f1f5f9; }
    .standing-avatar { width: 55px; height: 55px; object-fit: cover; border-radius: 50%; }
    .standing-avatar-large { width: 70px; height: 70px; object-fit: cover; border-radius: 50%; }
    .standing-name { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
    .standing-rank { font-size: 0.9rem; font-weight: 800; background: #fff; padding: 2px 10px; border-radius: 8px; border: 1px solid #e2e8f0; }
</style>

@if(isset($leaderboardContext))
    <!-- {{ __('Leaderboard') }} Standing Section -->
    <div class="card shadow-sm mb-4 border-0" style="border-radius: 24px; overflow: hidden; background: #fff;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0" style="color: #1e3a8a;"><i class="fas fa-trophy me-2 text-warning"></i>{{ __('Leaderboard') }} Standing</h5>
                @php 
                    $rank = $leaderboardContext['rank']; 
                    $myStyle = RankHelper::getRankStyle($rank, $leaderboardContext['myEntry']->frame_color ?? null);
                @endphp
                <span class="badge rounded-pill px-3 py-2" style="font-size: 0.95rem; {{ $myStyle['badge'] }}">Rank #{{ number_format($rank) }}</span>
            </div>

            <div class="standing-list">
                <!-- Neighbor Above -->
                @if($leaderboardContext['above'])
                    @php 
                        $above = $leaderboardContext['above'];
                        $aboveStyle = RankHelper::getRankStyle($above->current_rank, $above->frame_color);
                    @endphp
                    @php $aboveEvalUrl = $above->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $above->user->member_id]) : null; @endphp
                    <div class="standing-item mb-3" style="background-color: #f8fafc;">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                @if($aboveEvalUrl)<a href="{{ $aboveEvalUrl }}" class="text-decoration-none">@endif
                                <img src="{{ $above->user->avatar ? (str_starts_with($above->user->avatar, 'http') ? $above->user->avatar : asset($above->user->avatar)) : asset('images/default-avatar.png') }}" 
                                     class="standing-avatar {{ $aboveStyle['class'] }}" style="{{ $aboveStyle['glow'] }}">
                                @if($aboveEvalUrl)</a>@endif
                                @if($above->current_rank <= 3)
                                    <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $aboveStyle['iconColor'] }}" style="font-size: 1.2rem; transform: translate(-50%, -110%) !important;"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="standing-name text-truncate">
                                            @if($aboveEvalUrl)<a href="{{ $aboveEvalUrl }}" class="text-decoration-none text-dark">@endif
                                            {{ $above->user->name }}
                                            @if($aboveEvalUrl)</a>@endif
                                        </div>
                                        <div class="mb-1">{!! RankHelper::getTitleBadge($above->title, $above->current_rank, $above->frame_color) !!}</div>
                                    </div>
                                    <span class="standing-rank text-muted">#{{ $above->current_rank }}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-1 align-items-center">
                                    <span class="small fw-bold text-primary">{{ number_format($above->score) }} Points</span>
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 0.75rem; font-weight: 700;">
                                        <i class="fas fa-caret-up me-1"></i>{{ number_format($above->score - $leaderboardContext['score']) }} to catch up
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Current User (YOU) -->
                @php $myEvalUrl = Auth::user()->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => Auth::user()->member_id]) : null; @endphp
                <div class="standing-item my-3 shadow-sm border-0" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; transform: scale(1.02); z-index: 5;">
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-3">
                            @if($myEvalUrl)<a href="{{ $myEvalUrl }}" class="text-decoration-none">@endif
                            <img src="{{ Auth::user()->avatar ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar)) : asset('images/default-avatar.png') }}" 
                                 class="standing-avatar-large {{ $myStyle['class'] }}" style="{{ $myStyle['glow'] }}">
                            @if($myEvalUrl)</a>@endif
                            @if($rank <= 3)
                                <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $myStyle['iconColor'] }}" style="font-size: 1.5rem; transform: translate(-50%, -110%) !important; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($myEvalUrl)<a href="{{ $myEvalUrl }}" class="text-decoration-none text-white">@endif
                                    <div class="standing-name text-white">YOU</div>
                                    @if($myEvalUrl)</a>@endif
                                    @if($leaderboardContext['myEntry'] && $leaderboardContext['myEntry']->title)
                                        <div class="small mt-1 px-3 py-1 rounded-pill bg-white text-primary fw-bold shadow-sm" style="font-size: 0.8rem; width: fit-content;">
                                            <i class="fas fa-award me-1"></i>{{ $leaderboardContext['myEntry']->title }}
                                        </div>
                                    @endif
                                </div>
                                <span class="badge bg-warning text-dark px-3 py-2 fw-bold shadow-sm" style="font-size: 1.1rem; border-radius: 12px;">#{{ $rank }}</span>
                            </div>
                            <div class="mt-2 small fw-bold opacity-90"><i class="fas fa-bolt me-1 text-warning"></i>{{ number_format($leaderboardContext['score']) }} Points Accumulated</div>
                        </div>

                    </div>
                </div>

                <!-- Neighbor Below -->
                @if($leaderboardContext['below'])
                    @php 
                        $below = $leaderboardContext['below'];
                        $belowStyle = RankHelper::getRankStyle($below->current_rank, $below->frame_color);
                    @endphp
                    @php $belowEvalUrl = $below->user->member_id ? route($rolePrefix . '.evaluation.show', ['member_id' => $below->user->member_id]) : null; @endphp
                    <div class="standing-item mt-3" style="background-color: #f8fafc;">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                @if($belowEvalUrl)<a href="{{ $belowEvalUrl }}" class="text-decoration-none">@endif
                                <img src="{{ $below->user->avatar ? (str_starts_with($below->user->avatar, 'http') ? $below->user->avatar : asset($below->user->avatar)) : asset('images/default-avatar.png') }}" 
                                     class="standing-avatar {{ $belowStyle['class'] }}" style="{{ $belowStyle['glow'] }}">
                                @if($belowEvalUrl)</a>@endif
                                @if($below->current_rank <= 3)
                                    <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $belowStyle['iconColor'] }}" style="font-size: 1.2rem; transform: translate(-50%, -110%) !important;"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="standing-name text-truncate">
                                            @if($belowEvalUrl)<a href="{{ $belowEvalUrl }}" class="text-decoration-none text-dark">@endif
                                            {{ $below->user->name }}
                                            @if($belowEvalUrl)</a>@endif
                                        </div>
                                        <div class="mb-1">{!! RankHelper::getTitleBadge($below->title, $below->current_rank, $below->frame_color) !!}</div>
                                    </div>
                                    <span class="standing-rank text-muted">#{{ $below->current_rank }}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-1 align-items-center">
                                    <span class="small fw-bold text-primary">{{ number_format($below->score) }} Points</span>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.75rem; font-weight: 700;">
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
@endif

@if(isset($recommendedRivals))
    <!-- Points Rivalry Section -->
    <div class="card shadow-sm mb-4 border-0" style="border-radius: 24px; background: #fff; overflow: hidden;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color: #1e3a8a;"><i class="fas fa-swords me-2 text-danger"></i>Points Rivalry</h5>
            <p class="small text-muted mb-4 border-bottom pb-2">Challenge a user and compare your performance directly.</p>

            @if(isset($rivalContext))
                @php
                    $rivalRankData = $rivalContext['user']->leaderboards->first();
                    $rivalRank = $rivalRankData?->current_rank ?? 999;
                    $rivalStyle = \App\Helpers\RankHelper::getRankStyle($rivalRank, $rivalRankData?->frame_color);
                @endphp
                <div class="p-3 rounded-4 shadow-sm mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="{{ ($rivalContext['user']->avatar && str_starts_with($rivalContext['user']->avatar, 'http')) ? $rivalContext['user']->avatar : ($rivalContext['user']->avatar ? asset($rivalContext['user']->avatar) : asset('images/default-avatar.png')) }}" 
                                     class="rounded-circle {{ $rivalStyle['class'] }}" style="width: 60px; height: 60px; object-fit: cover; {{ $rivalStyle['glow'] }}">
                                <span class="position-absolute top-0 start-0 badge rounded-pill bg-danger shadow-sm" style="font-size: 0.6rem; transform: translate(-20%, -20%); z-index: 5;">TARGET</span>
                                @if($rivalRank <= 3)
                                    <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $rivalStyle['iconColor'] }}" style="font-size: 1.2rem; transform: translate(-50%, -100%) !important; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));"></i>
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-dark text-truncate" style="max-width: 140px;">{{ $rivalContext['user']->name }}</h6>
                                @if($rivalRankData && $rivalRankData->title)
                                    <div class="small px-2 py-0 rounded-pill d-inline-flex align-items-center my-1" style="font-size: 0.65rem; font-weight: 700; {{ $rivalStyle['badge'] }}">
                                        <i class="fas fa-award me-1"></i> {{ $rivalRankData->title }}
                                    </div>
                                @endif
                                <p class="small text-muted mb-0"><i class="fas fa-star text-warning"></i> {{ number_format($rivalContext['score']) }} pts</p>
                            </div>
                        </div>
                        <div class="text-end ms-2">
                            @if($rivalContext['diff'] > 0)
                                <div class="badge bg-success rounded-pill px-3 py-1 mb-1 shadow-sm" style="font-size: 0.8rem;">Lead</div>
                            @elseif($rivalContext['diff'] < 0)
                                <div class="badge bg-danger rounded-pill px-3 py-1 mb-1 shadow-sm" style="font-size: 0.8rem;">Chasing</div>
                            @else
                                <div class="badge bg-secondary rounded-pill px-3 py-1 mb-1 shadow-sm">Tied</div>
                            @endif
                            <div class="small fw-bold text-dark">{{ number_format(abs($rivalContext['diff'])) }}</div>
                        </div>
                    </div>

                    
                    <div class="battle-bar-container mt-3">
                        <div class="battle-center-line"></div>
                        @php
                            $myP = $leaderboardContext['score'] ?? 0;
                            $rP = $rivalContext['score'] ?? 0;
                            $totalPool = max($myP + $rP, 1);
                            $myW = ($myP / $totalPool) * 100;
                            $rivalW = 100 - $myW;
                        @endphp
                        <div class="d-flex h-100">
                            <div class="battle-bar-me {{ $myP >= $rP ? 'winning-pulse' : '' }}" style="width: {{ $myW }}%">
                                @if($myW > 25) <span class="battle-indicator" style="left: 10px;">YOU</span> @endif
                            </div>
                            <div class="battle-bar-rival {{ $rP > $myP ? 'winning-pulse' : '' }}" style="width: {{ $rivalW }}%">
                                @if($rivalW > 25) <span class="battle-indicator" style="right: 10px;">RIVAL</span> @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route(Auth::user()->hasRole('Lecturer') ? 'lecturer.dashboard.updateRival' : 'student.dashboard.updateRival') }}" method="POST" class="mb-4">
                @csrf
                <div class="position-relative">
                    <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="rivalSearchInput" class="form-control border-0 px-1" placeholder="Search for a rival..." autocomplete="off" style="font-size: 0.95rem; box-shadow: none;">
                        <input type="hidden" name="rival_id" id="rivalIdInput">
                        <button type="submit" class="btn btn-warning px-4 fw-bold">SET</button>
                    </div>
                    <div id="rivalSearchResults" class="list-group shadow position-absolute w-100 mt-1 d-none" style="z-index: 1000; border-radius: 10px; overflow: hidden;"></div>
                </div>
            </form>

            <div>
                <label class="small fw-bold text-muted mb-2"><i class="fas fa-magic me-1 text-primary"></i>SUGGESTIONS:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($recommendedRivals as $u)
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" onclick="selectRival('{{ $u->id }}', '{{ $u->name }}')" style="font-size: 0.75rem;">{{ $u->name }}</button>
                    @endforeach
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
            fetch("{{ Auth::user()->hasRole('Lecturer') ? route('lecturer.dashboard.searchRivals') : route('student.dashboard.searchRivals') }}?query=" + encodeURIComponent(query))
                .then(r => r.json()).then(data => {
                    list.innerHTML = '';
                    if (data.length === 0) list.innerHTML = '<div class="list-group-item small">No users found</div>';
                    else data.forEach(u => {
                        const avatar = u.avatar ? (u.avatar.startsWith('http') ? u.avatar : '/' + u.avatar) : '/images/default-avatar.png';
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action d-flex align-items-center py-2';
                        item.innerHTML = `<img src="${avatar}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;"><span class="small fw-bold">${u.name}</span>`;
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
