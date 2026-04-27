@extends('layout.lecturer')

@section('content')
    <div class="container my-5" style="max-width: 600px;">
        <!-- Level Cover -->
        <div class="text-center position-relative mb-4 animate-fade-in">
            <div class="position-relative">
                <!-- Gambar Level -->
                <img src="{{ $userLevel->image_url }}"
                     alt="{{ $userLevel->name }}"
                     class="img-fluid shadow-lg"
                     style="border-radius: 15px; max-width: 100%; height: auto; transition: transform 0.3s ease;">

                <!-- Efek Aura (Muncul hanya jika level baru tercapai) -->
                @php
                    $currentStage = explode(' ', $userLevel->name)[1]; // Ambil tahap (I, II, III, IV, V)
                    $isNewStage = $currentStage !== 'I' || $totalPoints > $userLevel->minimum_points;
                @endphp

                @if($isNewStage)
                    <div class="position-absolute top-0 start-0 w-100 h-100 aura-border-effect"
                         style="border-radius: 15px; pointer-events: none;"></div>
                @endif
            </div>

            <div class="position-absolute top-50 start-50 translate-middle text-white text-center"
                 style="text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7); width: 100%;">
                @php
                    $levelNumber = $allLevels->search(function($l) use ($userLevel) {
                        return $l->id === ($userLevel->id ?? null);
                    });
                    $levelNumber = ($levelNumber !== false) ? $levelNumber + 1 : 0;
                @endphp
                <h1 class="fw-bold glow-effect" style="font-size: 2.5rem;">{{ $userLevel->name }}</h1>
                @if($levelNumber > 0)
                    <div class="d-inline-block px-4 py-1 rounded-pill mt-2 shadow-lg" 
                         style="background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: #fff; font-weight: 800; font-size: 1.2rem; border: 2px solid rgba(255,255,255,0.8); text-shadow: 1px 1px 2px rgba(0,0,0,0.3); animation: pulseGlow 2s infinite;">
                        LEVEL {{ $levelNumber }}
                    </div>
                @endif
                <p class="mb-0 mt-2 fw-bold" style="font-size: 1.1rem; opacity: 0.9;">
                    <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png"
                         alt="Tesla Point Icon"
                         style="width: 20px; height: auto; vertical-align: middle; margin-right: 5px; filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));">
                    {{ $userLevel->minimum_points }} - {{ $userLevel->maximum_points }}
                </p>
            </div>
        </div>

        <!-- {{ __('Tesla Points') }} Display -->
        <div class="text-center animate-scale-in">
            <h2 class="text-white fw-bold">{{ Auth::user()->name }}'s {{ __('Tesla Points') }}</h2>
            <div class="d-flex justify-content-center align-items-center mb-4">
                <!-- Badge untuk menampilkan poin -->
                <div class="tesla-points-circle shadow-lg d-flex flex-column justify-content-center align-items-center"
                      style="width: 200px; height: 200px; border-radius: 50%; background: white; border: 8px solid rgba(255,255,255,0.2); animation: float 3s ease-in-out infinite;">
                    <span class="fw-bold text-dark" style="font-size: 2.2rem; letter-spacing: -1px;">{{ number_format($totalPoints) }}</span>
                    <div class="d-flex align-items-center mt-1">
                        <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla" style="width: 28px; height: auto;">
                        <span class="ms-1 fw-bold text-muted" style="font-size: 0.8rem; text-uppercase; letter-spacing: 1px;">Points</span>
                    </div>
                </div>
            </div>
            <p class="text-light">Collect <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 24px; height: auto; margin-left: 5px;"> to unlock rewards and achievements!</p>
        </div>

        <!-- Progress Bar for Level -->
        <div class="mb-4 text-center animate-fade-in">
            @php
                $progress = 0;
                $nextLevelPoints = $nextLevel ? $nextLevel->minimum_points : null;
                $levelRange = $userLevel->maximum_points - $userLevel->minimum_points;

                if ($levelRange > 0) {
                    $progress = (($totalPoints - $userLevel->minimum_points) / $levelRange) * 100;
                }
            @endphp

                    <!-- Progress Bar -->
            <div class="progress" style="height: 30px; border-radius: 50px; overflow: hidden; background-color: rgba(255, 255, 255, 0.2);">
                <div class="progress-bar bg-light progress-bar-striped progress-bar-animated" role="progressbar"
                     style="width: {{ min($progress, 100) }}%; color: #000; font-weight: bold;"
                     aria-valuenow="{{ $progress }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    {{ floor($progress) }}%
                </div>
            </div>

            <!-- Progress Details -->
            <div class="mt-3">
                <!-- Progress in X/Y {{ __('Tesla Points') }} -->
                <p class="text-light fw-bold mb-1">
                    {{ $totalPoints }} <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;"> / {{ $userLevel->maximum_points }} <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;">
                </p>

                <!-- Next Level Information -->
                @if($progress >= 100)
                    <p class="text-light fw-bold mb-0">Maxed out level! 🎉</p>
                @elseif($nextLevel)
                    <p class="text-light fw-bold mb-0">
                        Progress to <strong>{{ $nextLevel->name }}</strong>
                    </p>
                @else
                    <p class="text-light fw-bold mb-0">You have reached the highest level! 🏆</p>
                @endif
            </div>

            <!-- Button to View All Levels and How to Get {{ __('Tesla Points') }} -->
            <div class="text-center my-4">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                    <button type="button" class="btn btn-outline-light shadow" data-bs-toggle="modal" data-bs-target="#allLevelsModal" style="width: 100%; max-width: 300px;">
                        View All Levels
                    </button>
                    <button type="button" class="btn btn-outline-light shadow" data-bs-toggle="modal" data-bs-target="#howToGetPointsModal" style="width: 100%; max-width: 300px;">
                        How to Get <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 24px; height: auto; margin-left: 5px;">
                    </button>
                </div>
            </div>
        </div>

        <!-- Today's {{ __('Tesla Points') }} History -->
        <div class="card mt-5 shadow-sm border-0 animate-slide-in" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header text-center" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white;">
                <h5 class="mb-0">Today's {{ __('Tesla Points') }} History</h5>
            </div>
            <div class="card-body p-0">
                @if($dailyPointsHistory->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle fa-2x text-secondary mb-3"></i>
                        <p class="text-secondary">No {{ __('Tesla Points') }} earned today. Start your check-ins to level up!</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($dailyPointsHistory as $point)
                            <li class="list-group-item d-flex justify-content-between align-items-center hover-effect"
                                style="background: #f9fafb; transition: background 0.3s;">
                                <div class="d-flex align-items-center">
                                    <!-- Icon for Description -->
                                    <i class="fas fa-circle-notch me-3 text-primary" style="font-size: 1rem;"></i>
                                    <div>
                                        <span class="fw-bold">{{ $point->description }}</span><br>
                                        <small class="text-muted">
                                            {{ $point->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <!-- Badge with Color Based on Points -->
                                <span class="badge rounded-pill"
                                      style="background-color: {{ $point->points < 0 ? '#ef4444' : '#10b981' }};
                                     color: white;">
                                    {{ $point->points }} <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;">
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Button for Older History -->
        @if($olderPointsHistory->isNotEmpty())
            <div class="text-center my-4">
                <button type="button" class="btn btn-outline-light shadow" data-bs-toggle="modal" data-bs-target="#olderPointsModal">
                    View Older History
                </button>
            </div>
        @endif

        <!-- Modal for Older {{ __('Tesla Points') }} History -->
        <div class="modal fade" id="olderPointsModal" tabindex="-1" aria-labelledby="olderPointsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-navy text-white">
                        <h5 class="modal-title" id="olderPointsModalLabel">Older {{ __('Tesla Points') }} History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($olderPointsHistory->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-2x text-secondary mb-3"></i>
                                <p class="text-secondary">No older {{ __('Tesla Points') }} history available.</p>
                            </div>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($olderPointsHistory as $point)
                                    <li class="list-group-item d-flex justify-content-between align-items-center hover-effect"
                                        style="padding: 15px; background: #f9fafb; transition: background 0.3s;">
                                        <div>
                                            <span class="fw-bold text-navy">{{ $point->description }}</span><br>
                                            <small class="text-muted">{{ $point->created_at->format('d M Y, H:i') }}</small>
                                        </div>
                                        <!-- Badge with Color Based on Points -->
                                        <span class="badge rounded-pill"
                                              style="background-color: {{ $point->points < 0 ? '#ef4444' : '#10b981' }};
                                             color: white;">
                                            {{ $point->points }} <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;">
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for All Levels -->
        <div class="modal fade" id="allLevelsModal" tabindex="-1" aria-labelledby="allLevelsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark text-white" style="border-radius: 20px; overflow: hidden; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.8);">
                    <div class="modal-header" style="background: linear-gradient(90deg, #001f3f, #004080); border: none;">
                        <h5 class="modal-title fw-bold text-glow" id="allLevelsModalLabel">All Levels</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            @foreach($allLevels as $index => $level)
                                <li class="list-group-item bg-dark text-white">
                                    <div class="row align-items-center">
                                        <!-- Level Image with Name Overlay -->
                                        <div class="col-md-3 text-center position-relative">
                                            <img src="{{ $level->image_url }}" alt="{{ $level->name }}" class="img-fluid rounded shadow" style="max-height: 100px;">
                                            <!-- Level Name Overlay -->
                                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100">
                                                <h5 class="fw-bold text-glow2 mb-0" style="text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.6); font-size: 0.9rem;">
                                                    {{ $level->name }}
                                                </h5>
                                                <div class="small fw-bold text-info">Level {{ $index + 1 }}</div>
                                            </div>
                                        </div>
                                        <!-- Level Details -->
                                        <div class="col-md-7">
                                            <p class="mb-1">{{ $level->minimum_points }} <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;"> - {{ $level->maximum_points }} <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;"></p>
                                            <p class="mb-0">{{ $level->description }}</p>
                                        </div>
                                        <!-- Level Status -->
                                        <div class="col-md-2 text-end">
                                            @if($level->id === $userLevel->id)
                                                <span class="badge bg-primary">You are here</span>
                                            @elseif($level->minimum_points > $totalPoints)
                                                <span class="badge bg-secondary">{{ __('Locked') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ __('Unlocked') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer justify-content-center" style="background: linear-gradient(90deg, #004080, #001f3f); border: none;">
                        <button type="button" class="btn btn-outline-light btn-lg" data-bs-dismiss="modal" style="border-radius: 30px; animation: glowButton 1.5s infinite;">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for How to Get {{ __('Tesla Points') }} -->
        <div class="modal fade" id="howToGetPointsModal" tabindex="-1" aria-labelledby="howToGetPointsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white" style="border-radius: 20px; overflow: hidden; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.8);">
                    <div class="modal-header" style="background: linear-gradient(90deg, #001f3f, #004080); border: none;">
                        <h5 class="modal-title fw-bold text-glow" id="howToGetPointsModalLabel">How to Get {{ __('Tesla Points') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Ikon Tesla Besar di Tengah dengan Animasi -->
                        <div class="position-relative">
                            <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png"
                                 alt="Tesla Icon"
                                 class="animate-float"
                                 style="width: 100px; height: auto; margin-bottom: 20px; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));">
                            <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 aura-effect"
                                 style="pointer-events: none;"></div>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-dark text-white animate-slide-in-left">
                                Earn <strong>1 <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;"></strong> for every successful check-in at available locations.
                            </li>
                            <li class="list-group-item bg-dark text-white animate-slide-in-right">
                                Gain <strong>1 <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Point Icon" style="width: 16px; height: auto; vertical-align: middle;"> per minute</strong> for staying longer. The longer you stay, the more points you earn!
                            </li>
                            <!-- Aturan Baru: Checkout untuk Mendapatkan Poin -->
                            <li class="list-group-item bg-dark text-white animate-slide-in-left">
                                <strong>Checkout is required</strong> to receive points per minute. If you don't check out, you won't receive these points.
                            </li>
                            <li class="list-group-item bg-dark text-white animate-slide-in-right">
                                Achieve milestones like <strong>{{ __('Morning Person') }}</strong> or <strong>Longest Stay in a Day</strong> for bonus points.
                            </li>
                            <li class="list-group-item bg-dark text-white animate-slide-in-left">
                                Get surprise rewards through <strong>random giveaways</strong>.
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer justify-content-center" style="background: linear-gradient(90deg, #004080, #001f3f); border: none;">
                        <button type="button" class="btn btn-outline-light btn-lg animate-pulse" data-bs-dismiss="modal" style="border-radius: 30px;">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Efek Aura dari Pinggir Border */
        .aura-border-effect {
            box-shadow: 0 0 20px 10px rgba(173, 216, 230, 0.6); /* Warna biru muda dengan transparansi */
            animation: auraBorderGlow 3s infinite ease-in-out;
        }

        /* Animasi untuk efek aura dari pinggir border */
        @keyframes auraBorderGlow {
            0% {
                box-shadow: 0 0 20px 10px rgba(173, 216, 230, 0.6);
            }
            50% {
                box-shadow: 0 0 30px 15px rgba(173, 216, 230, 0.8); /* Efek lebih terang */
            }
            100% {
                box-shadow: 0 0 20px 10px rgba(173, 216, 230, 0.6);
            }
        }

        /* Efek glow untuk teks */
        .glow-effect {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.6);
            color: #ffffff;
        }

        .text-glow2 {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.6);
            color: #ffffff;
        }

        /* Animasi fade-in untuk level cover */
        .animate-fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Animasi scale-in untuk {{ __('Tesla Points') }} Display */
        .animate-scale-in {
            animation: scaleIn 1s ease-in-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1);
            }
        }

        /* Animasi slide-in untuk Today's {{ __('Tesla Points') }} History */
        .animate-slide-in {
            animation: slideIn 1s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Animasi bounce untuk badge poin */
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Glow effect untuk tombol */
        @keyframes glowButton {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 255, 255, 0.3), 0 0 20px rgba(255, 255, 255, 0.2); }
            50% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.6); }
        }

        @keyframes pulseGlow {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 242, 254, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(0, 242, 254, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 242, 254, 0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
@endsection