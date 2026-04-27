@php
use App\Helpers\RankHelper;
$rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
@endphp


<style>
    .podium-name {
        font-size: 0.85rem;
        font-weight: 800;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.4em;
        margin-top: 5px;
    }
    @media (max-width: 576px) {
        .podium-name {
            font-size: 0.7rem;
            height: 2.8em;
        }
        .podium-base {
            padding: 8px 4px !important;
        }
    }
    .podium-avatar-wrap {
        cursor: pointer;
        transition: transform 0.2s ease;
        text-decoration: none;
    }
    .podium-avatar-wrap:hover {
        transform: scale(1.06);
    }
</style>

@if(isset($topUsers) && $topUsers->count() > 0)
    <div class="card shadow-sm mb-4 animate-card" style="border-radius: 20px; border: none; background: #fff; overflow: hidden;">
        <div class="card-body p-3 p-sm-4">
            <div class="text-center mb-4">
                <div class="d-inline-block px-4 py-2 rounded-pill shadow-sm" style="background: #1e3a8a; color: white;">
                    <i class="fas fa-trophy me-2 text-warning"></i> <strong style="letter-spacing: 1px;">{{ __('TOP GLOBAL PLAYERS') }}</strong>
                </div>
            </div>

            @php
                $top3 = $topUsers->values();
                $podium = collect([null, null, null]);
                if($top3->count() >= 1) $podium[1] = $top3[0]; // 1st
                if($top3->count() >= 2) $podium[0] = $top3[1]; // 2nd
                if($top3->count() >= 3) $podium[2] = $top3[2]; // 3rd
            @endphp

            <div class="row align-items-end g-2 podium-container">
                @foreach($podium as $pos => $item)
                    @if($item)
                        @php
                            $actualRank = $pos == 1 ? 1 : ($pos == 0 ? 2 : 3);
                            $isFirst    = $actualRank == 1;
                            $height     = $isFirst ? '140px' : '110px';
                            // Use RankHelper for consistent Elite styling
                            $podiumRankData = $item->user->leaderboards->first();
                            $pStyle = RankHelper::getRankStyle($actualRank, $podiumRankData?->frame_color);
                            $podiumFrameColor = $podiumRankData?->frame_color ?? ($actualRank == 1 ? '#fbbf24' : ($actualRank == 2 ? '#9ca3af' : '#cd7f32'));
                            
                            $displayScore = isset($item->score) ? $item->score : ($item->total_points ?? 0);
                            // Build evaluation URL
                            $podiumEvalUrl = ($item->user->member_id)
                                ? route($rolePrefix . '.evaluation.show', ['member_id' => $item->user->member_id])
                                : null;
                        @endphp
                        <div class="col-4 px-1">
                            <div class="podium-item text-center mb-2" style="position: relative;">
                                <div class="podium-icon mb-2" style="font-size: 1.5rem; animation: bounce 2s infinite;">
                                    @if($actualRank == 1) 👑 @elseif($actualRank == 2) 🥈 @else 🥉 @endif
                                </div>
                                
                                {{-- Clickable avatar area --}}
                                @if($podiumEvalUrl)
                                <a href="{{ $podiumEvalUrl }}" class="podium-avatar-wrap d-inline-block mb-2">
                                @else
                                <div class="d-inline-block mb-2">
                                @endif
                                    <div class="position-relative">
                                        <img src="{{ ($item->user->avatar && str_starts_with($item->user->avatar, 'http')) ? $item->user->avatar : ($item->user->avatar ? asset($item->user->avatar) : asset('images/default-avatar.png')) }}" 
                                             alt="{{ $item->user->name }}"
                                             class="rounded-circle shadow-sm {{ $pStyle['class'] }}"
                                             style="width: {{ $isFirst ? '70px' : '55px' }}; height: {{ $isFirst ? '70px' : '55px' }}; object-fit: cover; {{ $pStyle['glow'] }}">
                                        <div class="rank-badge position-absolute" 
                                             style="bottom: -2px; right: -2px; background: {{ $podiumFrameColor }}; color: #fff; width: 20px; height: 20px; border-radius: 50%; font-size: 0.65rem; line-height: 20px; font-weight: bold; border: 2px solid #fff; z-index: 5;">
                                            {{ $actualRank }}
                                        </div>
                                    </div>
                                @if($podiumEvalUrl)
                                </a>
                                @else
                                </div>
                                @endif

                                @if($podiumEvalUrl)
                                <a href="{{ $podiumEvalUrl }}" class="text-decoration-none text-dark">
                                @endif
                                <div class="podium-base shadow-sm d-flex flex-column justify-content-center p-2" 
                                     style="height: {{ $height }}; background: linear-gradient(180deg, {{ $podiumFrameColor }}15, #fff); border-top: 4px solid {{ $podiumFrameColor }}; border-radius: 15px 15px 10px 10px;">
                                    <div class="podium-name text-dark px-1">
                                        {{ $item->user->name }}
                                    </div>
                                    <div class="text-primary fw-bold mt-1" style="font-size: 0.85rem;">{{ number_format($displayScore) }}</div>
                                    <div class="small text-muted" style="font-size: 0.6rem;">Pts</div>
                                </div>
                                @if($podiumEvalUrl)
                                </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="col-4 px-1"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif


