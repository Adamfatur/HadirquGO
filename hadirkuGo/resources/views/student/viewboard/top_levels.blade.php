@extends('layout.student')
@php use App\Helpers\RankHelper; @endphp

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 {{ __('Top 50 Rankings by Levels') }} 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">
                Check the overall elite rankings based on <strong>Level</strong> and <strong>Total Points</strong>!
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

        @if(isset($userRank))
            <div class="card shadow-sm border-0 mb-4 animate__animated animate__pulse" style="border-radius: 15px; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                                     class="rounded-circle border border-3 border-white shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark" style="font-size: 0.7rem;">YOU</span>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ __('Your Current Standing') }}</h5>
                                <p class="mb-0 opacity-90" style="font-size: 0.9rem;">
                                    @if(isset($userRank->is_outside))
                                        You are currently at <strong>Rank #{{ number_format($userRank->current_rank) }}</strong>.
                                    @else
                                        Amazing! You are in the Top 50 at <strong>Rank #{{ $userRank->current_rank }}</strong>!
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="display-6 fw-bold">#{{ number_format($userRank->current_rank) }}</div>
                            <div class="small opacity-75">{{ number_format($userRank->score) }} Points</div>
                        </div>
                    </div>
                    
                    @if(isset($userRank->is_outside))
                        <div class="mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.1); border: 1px dashed rgba(255,255,255,0.3);">
                            <p class="mb-0 small italic text-center">
                                <i class="fas fa-rocket me-2"></i>
                                "Jangan menyerah! Setiap poin yang kamu kumpulkan membawamu lebih dekat ke puncak. Teruslah aktif dan tunjukkan dedikasimu!"
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #f9f9fb;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-secondary font-weight-bold" style="color: #1e3a8a;">
                    {{ __('Elite {{ __('Leaderboard') }} (All Time)') }}
                </h2>
                <small class="text-muted">{{ __('Updated hourly') }}</small>
            </div>
            <div class="card-body">
                @include('partials.leaderboard_search', ['searchCategory' => 'top_levels'])
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
                                <th>{{ __('Level') }}</th>
                                <th>Points</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rankings as $data)
                                <tr class="border-bottom animate__animated animate__fadeIn {{ Auth::id() == $data->user_id ? 'table-primary' : '' }}">
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
                                    <td class="text-dark">
                                        @php
                                            $rankClass = '';
                                            $glowClass = '';
                                            $titleBadgeStyle = '';
                                            $iconColor = 'text-warning';
                                            
                                            if ($data->current_rank == 1) {
                                                $rankClass = 'border-warning shadow-lg';
                                                $glowClass = 'border: 3px solid #fbbf24; box-shadow: 0 0 15px #fbbf24, inset 0 0 10px #fbbf24; padding: 2px; background: linear-gradient(45deg, #fef3c7, #f59e0b);';
                                                $titleBadgeStyle = 'background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; border: 1px solid #fbbf24; box-shadow: 0 2px 4px rgba(251,191,36,0.3); font-weight: 700;';
                                                $iconColor = 'text-warning';
                                            } elseif ($data->current_rank == 2) {
                                                $rankClass = 'border-secondary shadow-lg';
                                                $glowClass = 'border: 3px solid #9ca3af; box-shadow: 0 0 12px #9ca3af; padding: 2px; background: linear-gradient(45deg, #f3f4f6, #9ca3af);';
                                                $titleBadgeStyle = 'background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #4b5563; border: 1px solid #9ca3af; font-weight: 700;';
                                                $iconColor = 'text-secondary';
                                            } elseif ($data->current_rank == 3) {
                                                $rankClass = 'shadow-lg';
                                                $glowClass = 'border: 3px solid #cd7f32; box-shadow: 0 0 10px #cd7f32; padding: 2px; background: linear-gradient(45deg, #fdf5e6, #cd7f32);';
                                                $titleBadgeStyle = 'background: linear-gradient(135deg, #ffedd5, #fcd34d); color: #b45309; border: 1px solid #d97706; font-weight: 700;';
                                                $iconColor = 'text-warning';
                                            } elseif ($data->current_rank <= 5) {
                                                $rankClass = 'border-danger shadow-sm';
                                                $glowClass = 'border: 2px solid #ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.5); padding: 2px;';
                                                $titleBadgeStyle = 'background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5;';
                                                $iconColor = 'text-danger';
                                            } elseif ($data->current_rank <= 10) {
                                                $rankClass = 'border-success shadow-sm';
                                                $glowClass = 'border: 2px solid #10b981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.4); padding: 2px;';
                                                $titleBadgeStyle = 'background: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;';
                                                $iconColor = 'text-success';
                                            } else {
                                                $rankClass = 'border-primary shadow-sm';
                                                $glowClass = 'border: 2px solid #3b82f6; box-shadow: 0 0 4px rgba(59, 130, 246, 0.3); padding: 2px;';
                                                $titleBadgeStyle = 'background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd;';
                                                $iconColor = 'text-primary';
                                            }
                                        @endphp
                                        @if($data->user && $data->user->member_id)
                                        <a href="{{ route('student.evaluation.show', ['member_id' => $data->user->member_id]) }}" class="text-decoration-none text-dark d-flex align-items-center" style="transition: transform 0.2s ease;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                                        @else
                                        <div class="d-flex align-items-center">
                                        @endif
                                            <div class="position-relative me-3">
                                                <img src="{{ $data->user?->avatar ?? asset('images/default-avatar.png') }}"
                                                     alt="Avatar" class="rounded-circle {{ $rankClass }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover; {{ $glowClass }}">
                                                @if($data->current_rank <= 3)
                                                    <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $iconColor }}" style="font-size: 1.2rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3)); transform: translate(-50%, -40%) !important; z-index: 10;"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                    {{ $data->user?->name ?? __('Unknown User') }}
                                                    @if(Auth::id() == $data->user_id)
                                                        <span class="badge bg-primary ms-1" style="font-size: 0.7rem; vertical-align: middle;">YOU</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center gap-1 mt-1">
                                                    @if($data->title)
                                                        <div class="small px-2 py-1 rounded-pill" style="font-size: 0.75rem; display: inline-flex; align-items: center; {{ $titleBadgeStyle }}">
                                                            <i class="fas fa-award me-1" style="font-size: 0.8rem;"></i> {{ $data->title }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @if($data->user && $data->user->member_id)
                                        </a>
                                        @else
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $levelStyles = [
                                                "Pioneer" => ["bg" => "#eff6ff", "text" => "#2563eb", "border" => "#bfdbfe"],
                                                "Academic Voyager" => ["bg" => "#f3f4f6", "text" => "#4b5563", "border" => "#d1d5db"],
                                                "Scholastic Trailblazer" => ["bg" => "#fefce8", "text" => "#ca8a04", "border" => "#fef08a"],
                                                "Intellectual Pathfinder" => ["bg" => "#f0fdfa", "text" => "#0d9488", "border" => "#99f6e4"],
                                                "Knowledge Vanguard" => ["bg" => "#f0f9ff", "text" => "#0284c7", "border" => "#bae6fd"],
                                                "Master of Attendance" => ["bg" => "#faf5ff", "text" => "#9333ea", "border" => "#e9d5ff"],
                                                "Savants of the Semester" => ["bg" => "#fef2f2", "text" => "#dc2626", "border" => "#fecaca"],
                                                "Attendance Luminary" => ["bg" => "#eef2ff", "text" => "#4f46e5", "border" => "#c7d2fe"],
                                                "Legendary Learner" => ["bg" => "#fffbeb", "text" => "#d97706", "border" => "#fde68a"]
                                            ];
                                            $baseName = preg_replace('/\s+[IVX]+$/', '', $data->level);
                                            $style = $levelStyles[$baseName] ?? ["bg" => "#f8fafc", "text" => "#334155", "border" => "#cbd5e1"];
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            @if($data->level_image)
                                                <img src="{{ asset($data->level_image) }}" alt="Level" class="rounded-circle shadow-sm me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                            @endif
                                            <span class="badge rounded-pill px-3 py-2 shadow-sm" style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }}; border: 1px solid {{ $style['border'] }}; font-weight: 600; font-size: 0.85rem;">
                                                {{ $data->level }} <span class="ms-1 opacity-75" style="font-size: 0.75rem;">(Level {{ \App\Helpers\RankHelper::getLevelNumber($data->user) }})</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-warning">
                                        ⭐ {{ number_format($data->score, 0) }} pts
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
