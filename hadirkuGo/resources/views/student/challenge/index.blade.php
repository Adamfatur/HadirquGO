@extends('layout.student')

@section('title', 'Challenge Dashboard')

@section('content')
    <div class="container mt-4">
        <!-- Flash messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistik User -->
        <div class="card shadow-sm mb-4" style="background: white; border-radius: 15px;">
            <div class="card-body">
                <h5 class="fw-bold mb-3" style="color: #1e3a8a;">Your Statistics</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Card Win -->
                    <div class="text-center p-3 stat-card" style="background: #e6f4ea; border-radius: 12px; width: 30%;">
                        <div class="icon-container mb-2">
                            <i class="fas fa-trophy fa-2x" style="color: #2e7d32;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #2e7d32;">Win</h6>
                        <p class="mb-0" style="font-size: 1.5rem; color: #2e7d32;">{{ $totalWins }}</p>
                    </div>

                    <!-- Win Rate -->
                    <div class="text-center p-3 stat-card" style="background: #eef2ff; border-radius: 12px; width: 30%;">
                        <div class="icon-container mb-2">
                            <i class="fas fa-chart-line fa-2x" style="color: #1e3a8a;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #1e3a8a;">Win Rate</h6>
                        <p class="mb-0" style="font-size: 1.5rem; color: #1e3a8a;">{{ $winRate }}%</p>
                    </div>

                    <!-- Card Lose -->
                    <div class="text-center p-3 stat-card" style="background: #ffebee; border-radius: 12px; width: 30%;">
                        <div class="icon-container mb-2">
                            <i class="fas fa-sad-tear fa-2x" style="color: #c62828;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #c62828;">Lose</h6>
                        <p class="mb-0" style="font-size: 1.5rem; color: #c62828;">{{ $totalLoses }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Win Streak & Lose Streak Card -->
        <div class="card shadow-sm p-3 mb-4"
             style="border-radius: 15px; background-color: white; color: #1e3a8a;">
            <div class="card-body">
                <!-- Tab Section -->
                <div class="d-flex align-items-center"
                     style="background-color: #eef2ff; border-radius: 12px; padding: 5px;">
                    <div class="flex-fill text-center"
                         style="background-color: #1e3a8a; color: white;
                                border-radius: 10px; padding: 8px 12px;">
                        <span class="fw-bold">Streak</span>
                    </div>
                    <div class="flex-fill text-center" style="color: #1e3a8a; padding: 8px 12px;">
                        <span><i class="fas fa-fire"></i> Streak Stats</span>
                    </div>
                </div>

                <!-- Win Streak & Lose Streak Data Section -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <!-- Win Streak -->
                    <div>
                        <h3 class="fw-bold" style="color: #2e7d32;">
                            {{ $winStreak ?? 0 }}
                        </h3>
                        <p class="mb-1 text-muted small">Win Streak</p>
                    </div>
                    <!-- Lose Streak -->
                    <div>
                        <h3 class="fw-bold" style="color: #c62828;">
                            {{ $loseStreak ?? 0 }}
                        </h3>
                        <p class="mb-1 text-muted small">Lose Streak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: {{ __('Challenges') }} -->
        <div class="card shadow-sm mb-4" style="background: white; border-radius: 15px;">
            <div class="card-body">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold" style="color: #1e3a8a;">{{ __('Challenges') }}</h5>
                    <!-- Tombol untuk membuka modal Create Challenge -->
                    <button type="button" class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#createChallengeModal"
                            style="border-radius: 12px;">
                        <i class="fas fa-plus me-2"></i> Create Challenge
                    </button>
                </div>

                <!-- Challenge List -->
                <div id="challengeList">
                    @if($challenges->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No challenges available. Create one to get started!</p>
                        </div>
                    @else
                        @foreach($challenges as $challenge)
                            <div class="card shadow-sm mb-3 challenge-item" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Challenger -->
                                        <div class="col-md-4 text-center">
                                            <img src="{{ $challenge->challenger->avatar }}" alt="Challenger Avatar"
                                                 class="rounded-circle mb-2" style="width: 60px; height: 60px;">
                                            <h6 class="fw-bold mb-0" style="color: #1e3a8a;">
                                                {{ $challenge->challenger->name }}
                                            </h6>
                                            <p class="small text-muted mb-0">Challenger</p>
                                        </div>

                                        <!-- VS Icon -->
                                        <div class="col-md-4 text-center">
                                            <h5 class="fw-bold" style="color: #1e3a8a;">VS</h5>
                                        </div>

                                        <!-- Challenged -->
                                        <div class="col-md-4 text-center">
                                            <img src="{{ $challenge->challenged->avatar }}" alt="Challenged Avatar"
                                                 class="rounded-circle mb-2" style="width: 60px; height: 60px;">
                                            <h6 class="fw-bold mb-0" style="color: #1e3a8a;">
                                                {{ $challenge->challenged->name }}
                                            </h6>
                                            <p class="small text-muted mb-0">Challenged</p>
                                        </div>
                                    </div>

                                    <!-- Challenge Details -->
                                    <div class="mt-3 text-center">
                                        <p class="mb-1 small text-muted">
                                            <strong>Type:</strong> {{ ucfirst($challenge->type) }} |
                                            <strong>Duration:</strong> {{ $challenge->duration_days }} days
                                        </p>
                                        <p>
                                            <span class="badge
                                                {{ $challenge->status === 'pending' ? 'bg-warning' : ($challenge->status === 'ongoing' ? 'bg-primary' : 'bg-success') }}">
                                                {{ ucfirst($challenge->status) }}
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-center mt-3 gap-2">
                                        @if($challenge->status === 'pending')
                                            <form action="{{ route('challenges.delete', ['challengeId' => $challenge->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-cancel"
                                                        onclick="return confirm('Are you sure you want to cancel this challenge?')">
                                                    <i class="fas fa-times me-1"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-details"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal-{{ $challenge->id }}">
                                            <i class="fas fa-info-circle me-1"></i> Details
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL: Detail Challenge -->
                            <div class="modal fade"
                                 id="detailModal-{{ $challenge->id }}"
                                 tabindex="-1"
                                 aria-labelledby="detailModalLabel-{{ $challenge->id }}"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 15px;">
                                        <div class="modal-header" style="background: #1e3a8a; color: white;">
                                            <h5 class="modal-title" id="detailModalLabel-{{ $challenge->id }}">
                                                <i class="fas fa-info-circle me-2"></i> Challenge Details
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Challenger:</strong> {{ $challenge->challenger->name }}</p>
                                            <p><strong>Challenged:</strong> {{ $challenge->challenged->name }}</p>
                                            <p><strong>Type:</strong> {{ ucfirst($challenge->type) }}</p>
                                            <p><strong>Duration:</strong> {{ $challenge->duration_days }} days</p>
                                            <p><strong>Status:</strong> {{ ucfirst($challenge->status) }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px;">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END MODAL: Detail Challenge -->
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!-- End Card: {{ __('Challenges') }} -->

        <!-- Modal for Create Challenge -->
        <div class="modal fade"
             id="createChallengeModal"
             tabindex="-1"
             aria-labelledby="createChallengeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 15px;">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background: #1e3a8a; color: white;">
                        <h5 class="modal-title fw-bold" id="createChallengeModalLabel">
                            <i class="fas fa-trophy me-2"></i> Create New Challenge
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Form untuk membuat challenge -->
                        <form id="createChallengeForm" action="{{ route('challenges.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="challenger_id" value="{{ auth()->user()->id }}">
                            <div class="mb-4">
                                <label for="challenged_id" class="form-label fw-bold" style="color: #1e3a8a;">
                                    <i class="fas fa-user me-2"></i> Select Opponent
                                </label>
                                <select class="form-select" id="challenged_id" name="challenged_id" required
                                        onchange="updateOpponentInfo(this)"
                                        style="border-radius: 10px; border: 2px solid #1e3a8a;">
                                    <option value="" disabled selected>Choose a user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user['id'] }}"
                                                data-points="{{ $user['total_points'] }}"
                                                data-avatar="{{ $user['avatar'] }}">
                                            {{ $user['name'] }} ({{ $user['total_points'] }} Points)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="type" class="form-label fw-bold" style="color: #1e3a8a;">
                                    <i class="fas fa-flag me-2"></i> Challenge Type
                                </label>
                                <select class="form-select" id="type" name="type" required
                                        style="border-radius: 10px; border: 2px solid #1e3a8a;">
                                    <option value="" disabled selected>Choose a type</option>
                                    <option value="points">Most Points</option>
                                    <option value="duration">Longest Duration</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="duration_days" class="form-label fw-bold" style="color: #1e3a8a;">
                                    <i class="fas fa-clock me-2"></i> Duration (Days)
                                </label>
                                <input type="number" class="form-control" id="duration_days" name="duration_days"
                                       min="1" max="7" required
                                       style="border-radius: 10px; border: 2px solid #1e3a8a;">
                            </div>
                        </form>

                        <!-- Informasi Lawan dengan Desain Card -->
                        <div id="opponentInfo" class="mt-4" style="display: none;">
                            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3" style="color: #1e3a8a;">
                                        <i class="fas fa-info-circle me-2"></i> Opponent Information
                                    </h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <img id="opponentAvatar" src="" alt="Opponent Avatar"
                                             class="rounded-circle me-3" style="width: 50px; height: 50px;">
                                        <div>
                                            <p class="mb-1">
                                                <strong>Total Points:</strong>
                                                <span id="opponentPoints">0</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="riskRewardInfo">
                                        <p class="mb-2">
                                            <strong>If You Win:</strong>
                                            <span id="pointsIfWin">0</span> Points
                                        </p>
                                        <p class="mb-2">
                                            <strong>If You Lose:</strong>
                                            <span id="pointsIfLose">0</span> Points Deducted
                                        </p>
                                        <p class="text-muted small" id="riskMessage"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer" style="background: #eef2ff;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                style="border-radius: 12px; font-weight: bold;">
                            <i class="fas fa-times me-2"></i> Cancel
                        </button>
                        <button type="submit" form="createChallengeForm" class="btn btn-primary"
                                style="border-radius: 12px; font-weight: bold;">
                            <i class="fas fa-plus me-2"></i> Create Challenge
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Styles -->
<style>
    .challenge-item {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .challenge-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Tombol Cancel */
    .btn-cancel {
        background-color: #e74c3c; /* Warna merah */
        color: #ffffff;
        border: none;
        border-radius: 12px;
        width: 120px; /* Lebar yang sama */
        padding: 8px 16px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-cancel:hover {
        background-color: #c0392b; /* Sedikit lebih gelap */
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Tombol Details */
    .btn-details {
        background-color: #1e3a8a; /* Warna navy */
        color: #ffffff;
        border: none;
        border-radius: 12px;
        width: 120px; /* Lebar yang sama */
        padding: 8px 16px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-details:hover {
        background-color: #0f2c66; /* Sedikit lebih gelap */
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Tata Letak Tombol */
    .d-flex.gap-2 {
        gap: 1rem;
        justify-content: center; /* Center buttons */
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .btn-cancel,
        .btn-details {
            width: 100%; /* Tombol memenuhi lebar container di layar kecil */
            margin-bottom: 10px; /* Jarak antar tombol di layar kecil */
        }

        .d-flex.gap-2 {
            flex-direction: column; /* Tombol ditumpuk vertikal di layar kecil */
        }
    }
</style>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            console.error('CSRF token meta tag not found!');
            return;
        }

        const csrfToken = csrfTokenMeta.getAttribute('content');
        const form = document.getElementById('createChallengeForm');

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            const modal = document.getElementById('createChallengeModal');
                            if (modal) {
                                const modalInstance = bootstrap.Modal.getInstance(modal);
                                modalInstance.hide();
                            }
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to create challenge. Please try again.');
                    });
            });
        }
    });

    window.updateOpponentInfo = function (select) {
        const selectedOption = select.options[select.selectedIndex];
        const opponentPoints = parseInt(selectedOption.getAttribute('data-points') || 0);
        const opponentAvatar = selectedOption.getAttribute('data-avatar') || '';
        const userPoints = {{ auth()->user()->pointSummary ? auth()->user()->pointSummary->total_points : 0 }};

        document.getElementById('opponentPoints').textContent = opponentPoints;
        document.getElementById('opponentInfo').style.display = 'block';

        const opponentAvatarElement = document.getElementById('opponentAvatar');
        if (opponentAvatarElement && opponentAvatar) {
            opponentAvatarElement.src = opponentAvatar;
        }

        let pointsIfWin = 0;
        let pointsIfLose = 0;
        let riskMessage = '';
        let riskLevel = '';

        if (opponentPoints < userPoints) {
            pointsIfWin = 5;
            pointsIfLose = 10;
            riskMessage = `Warning: Challenging an opponent with fewer points (${opponentPoints} vs your ${userPoints}) is risky.`;
            riskLevel = 'high';
        } else if (opponentPoints > userPoints) {
            pointsIfWin = 20;
            pointsIfLose = 1;
            riskMessage = `Challenging an opponent with more points (${opponentPoints} vs your ${userPoints}) is rewarding.`;
            riskLevel = 'low';
        } else {
            pointsIfWin = 10;
            pointsIfLose = 2;
            riskMessage = `Challenging an opponent with equal points (${opponentPoints} vs your ${userPoints}) is balanced.`;
            riskLevel = 'medium';
        }

        document.getElementById('pointsIfWin').textContent = pointsIfWin;
        document.getElementById('pointsIfLose').textContent = pointsIfLose;
        document.getElementById('riskMessage').textContent = riskMessage;

        const riskRewardInfo = document.getElementById('riskRewardInfo');
        if (riskRewardInfo) {
            riskRewardInfo.classList.remove('risk-low', 'risk-medium', 'risk-high');
            riskRewardInfo.classList.add(`risk-${riskLevel}`);
        }
    };
</script>