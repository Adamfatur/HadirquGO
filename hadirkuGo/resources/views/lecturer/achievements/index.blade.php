@extends('layout.lecturer')

@section('content')
    <style>
        /* General card styles */
        .card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden; /* Ensure child elements respect border-radius */
        }

        /* Locked cards: dark and grayscale */
        .card.locked {
            background-color: #3a3a3a; /* Dark gray */
            color: #8a8a8a; /* Dimmed text */
            filter: grayscale(100%) brightness(80%);
            position: relative;
        }

        .card.locked img {
            filter: grayscale(100%) brightness(50%);
        }

        .card.locked .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Dark overlay */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 15px;
            z-index: 1;
        }

        .card.locked .overlay i {
            font-size: 3rem;
            color: #ffffff;
        }

        /* Unlocked cards: bright and active */
        .card.unlocked {
            background-color: #2a3e5c; /* Bright navy */
            color: #ffffff; /* Bright text */
            border: 2px solid #00c853; /* Green border */
            box-shadow: 0px 8px 15px rgba(0, 255, 100, 0.4); /* Glow effect */
        }

        .card.unlocked:hover {
            transform: translateY(-5px); /* Lift card on hover */
            box-shadow: 0 10px 20px rgba(0, 255, 100, 0.5); /* Stronger glow */
        }

        .card.unlocked img {
            filter: none; /* Full color image for unlocked cards */
        }

        /* Header and subheading styles */
        h2 {
            color: #ffffff; /* Bright white header */
        }

        .subheading {
            color: #dcdcdc; /* Softer white for subheading */
            font-size: 1.2rem;
        }

        /* Badge for achievement count */
        .badge {
            background-color: #00c853; /* Bright green for success */
            color: #ffffff;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 20px;
            display: inline-block;
        }

        /* Unlock info text */
        .unlock-info {
            font-style: italic;
            color: #dcdcdc;
        }

        /* Empty state text */
        .empty-state {
            color: #d4d4d4;
            font-style: italic;
        }

        /* Ensure the image maintains its aspect ratio */
        .card-img-top {
            object-fit: contain;
            width: 100%;
            height: 200px;
            border-radius: 15px 15px 0 0;
            background-color: #0a463b;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-img-top {
                height: 150px;
            }

            .modal-content {
                padding: 1rem;
            }

            .achievement-detail-card {
                padding: 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .card-img-top {
                height: 120px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .subheading {
                font-size: 1rem;
            }

            .badge {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }

            .modal-dialog {
                max-width: 90%;
                margin: 1.75rem auto;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Daily Missions Section -->
        <div class="mb-5">
            <h2 class="fw-bold text-warning text-center mb-4">Today's Missions</h2>
            <div class="row g-4">
                <!-- Mission 1: Daily MP -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-warning">Mission: Daily MP</h5>
                            <p class="text-muted">Be the first to check in and earn the <strong>Daily MP</strong> badge!</p>
                            @if($dailyMissionStatus['dailyMP']['isAchieved'])
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h6 class="text-success">1/1</h6>
                                <p class="text-muted">Congratulations! You earned this badge today.</p>
                            @elseif($dailyMissionStatus['dailyMP']['isFailed'])
                                <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                <h6 class="text-danger">0/1</h6>
                                <p class="text-muted">Mission completed by <strong>{{ $dailyMissionStatus['dailyMP']['winnerName'] }}</strong>.</p>
                            @else
                                <i class="fas fa-hourglass-half fa-3x text-primary mb-3"></i>
                                <h6 class="text-primary">0/1</h6>
                                <p class="text-muted">The mission is still available! Check in now to claim it!</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mission 2: Longest Activity -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-primary">Mission: Longest Activity</h5>
                            <p class="text-muted">Spend the longest time in activities today to earn the <strong>Time Master</strong> badge!</p>
                            @if($dailyMissionStatus['longestActivity']['isAchieved'])
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h6 class="text-success">1/1</h6>
                                <p class="text-muted">Congratulations! You've spent the longest time today.</p>
                            @elseif($dailyMissionStatus['longestActivity']['isFailed'])
                                <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                <h6 class="text-danger">0/1</h6>
                                <p class="text-muted">
                                    Winner: <strong>{{ $dailyMissionStatus['longestActivity']['winnerName'] }}</strong>
                                    ({{ $dailyMissionStatus['longestActivity']['winnerDuration'] }} minutes).
                                </p>
                            @else
                                <i class="fas fa-hourglass-half fa-3x text-primary mb-3"></i>
                                <h6 class="text-primary">{{ $dailyMissionStatus['longestActivity']['progress'] }} min</h6>
                                <p class="text-muted">Keep going to spend the most time today!</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mission 3: Explorer -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center p-4">
                            <h5 class="fw-bold text-warning">Mission: Visit 5+ Locations</h5>
                            <p class="text-muted">Visit at least 5 unique locations today to earn the <strong>Explorer</strong> badge!</p>
                            @if($dailyMissionStatus['visitLocations']['isAchieved'])
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h6 class="text-success">5/5</h6>
                                <p class="text-muted">Congratulations! You've visited 5+ locations today.</p>
                            @elseif($dailyMissionStatus['visitLocations']['isFailed'])
                                <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                <h6 class="text-danger">{{ $dailyMissionStatus['visitLocations']['locationsVisited'] }}/5</h6>
                                <p class="text-muted">Mission completed by <strong>{{ $dailyMissionStatus['visitLocations']['winnerName'] }}</strong>.</p>
                            @else
                                <i class="fas fa-map-marker-alt fa-3x text-warning mb-3"></i>
                                <h6 class="text-warning">{{ $dailyMissionStatus['visitLocations']['locationsVisited'] }}/5</h6>
                                <p class="text-muted">Keep visiting locations to achieve this mission!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="row g-4">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Your Achievements</h2>
                <p class="subheading">Celebrate your milestones and see what's next!</p>
            </div>
            @forelse($allAchievements as $achievement)
                @php
                    $isUnlocked = $unlockedAchievements->contains('achievement_id', $achievement->id);
                    $unlockedData = $unlockedAchievements->where('achievement_id', $achievement->id);
                @endphp
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm h-100 {{ $isUnlocked ? 'unlocked' : 'locked' }}">
                        <div class="position-relative">
                            <img src="{{ $achievement->image }}" class="card-img-top" alt="{{ $achievement->name }}">
                            @if(!$isUnlocked)
                                <div class="overlay">
                                    <i class="fas fa-lock"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $achievement->name }}</h5>
                            <p class="card-text">{{ $achievement->description }}</p>

                            @if($isUnlocked)
                                <span class="badge mb-2">
                                    Achieved {{ $unlockedData->count() }} {{ Str::plural('time', $unlockedData->count()) }}
                                </span>
                                <button class="btn btn-outline-light w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#achievementDetailsModal" onclick="showAchievementDetails({{ $achievement->id }})">
                                    {{ __('View Details') }}
                                </button>
                            @else
                                <!-- Tambahkan informasi syarat unlock jika diperlukan -->
                                <p class="unlock-info">Unlock by completing the mission: {{ $achievement->name }}.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="empty-state">No achievements available at the moment. Check back later!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Achievement Details Modal -->
    <div class="modal fade" id="achievementDetailsModal" tabindex="-1" aria-labelledby="achievementDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background-color: #1c2541; color: #e0e0e0; border-radius: 15px;">
                <div class="modal-header" style="border-bottom: 1px solid #576f91;">
                    <h5 class="modal-title" id="achievementDetailsModalLabel">Achievement Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body">
                    <div id="achievementDetailsContent" class="text-center">
                        <div class="spinner-border text-light" role="status" id="loadingSpinner" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div id="achievementDetailsData"></div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #576f91;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const loadedAchievements = new Map(); // Cache loaded achievements

        function showAchievementDetails(achievementId) {
            const modalTitle = document.getElementById('achievementDetailsModalLabel');
            const modalContent = document.getElementById('achievementDetailsData');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // If already loaded, use cached data
            if (loadedAchievements.has(achievementId)) {
                const cachedData = loadedAchievements.get(achievementId);
                modalTitle.innerText = `${cachedData.achievement.name} - Details`;
                modalContent.innerHTML = cachedData.html;
                return;
            }

            // Show loading spinner
            loadingSpinner.style.display = 'block';
            modalContent.innerHTML = ''; // Clear previous content

            fetch(`/lecturer/achievements/${achievementId}/details`)
                .then(response => response.json())
                .then(data => {
                    // Hide loading spinner
                    loadingSpinner.style.display = 'none';

                    // Update modal title
                    modalTitle.innerText = `${data.achievement.name} - Details`;

                    if (data.details.length > 0) {
                        const detailsHtml = data.details.map((detail, index) => `
                            <div class="achievement-detail-card mb-3 p-3" style="background-color: #243447; border-radius: 10px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-warning">Record #${index + 1}</h6>
                                    <span class="badge bg-success">Achieved</span>
                                </div>
                                <p class="mt-2 mb-0">
                                    <strong>Date:</strong> ${detail.date}<br>
                                    <strong>Time:</strong> ${detail.time}
                                </p>
                            </div>
                        `).join('');

                        modalContent.innerHTML = detailsHtml;

                        // Cache the data
                        loadedAchievements.set(achievementId, {
                            achievement: data.achievement,
                            html: detailsHtml,
                        });
                    } else {
                        const noDataHtml = '<p class="text-muted">No records available for this achievement.</p>';
                        modalContent.innerHTML = noDataHtml;

                        // Cache the no-data state
                        loadedAchievements.set(achievementId, {
                            achievement: data.achievement,
                            html: noDataHtml,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching achievement details:', error);
                    modalContent.innerHTML = '<p class="text-danger">An error occurred while fetching details. Please try again later.</p>';
                });
        }
    </script>
@endsection