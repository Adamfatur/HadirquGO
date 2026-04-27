@extends('layout.student')

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-11 col-lg-10 col-xl-8">
                <div class="text-center mb-3">
                    <h1 class="display-4 text-light fw-bold mb-4">Knowledge {{ __('Challenges') }}</h1>

                    <button class="btn btn-info fw-bold mb-3" data-bs-toggle="modal" data-bs-target="#quizHistoryModal">
                        <i class="fas fa-history me-2"></i>View Quiz History
                    </button>

                    <div class="d-flex justify-content-center align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-pill px-4 py-2">
                            <p class="lead text-light mb-0">Available Quizzes: {{ count($quizzes) }}</p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4 animate-card"
                     style="background: linear-gradient(135deg, #4CAF50, #8BC34A);
                border-radius: 15px; padding: 20px; color: white; border: none;">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h5 class="fw-bold">Super Quiz Challenge</h5>
                        </div>

                        <div class="text-center">
                            <p class="text-light">
                                <em>"Want to earn more points? Let's do the Super Quiz latest game and get up to 100 bonus points!"</em><br>
                                Explore our exciting Super Quizzes, test your knowledge deeper, and get bigger rewards!
                            </p>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('student.superquiz.index') }}"
                               class="btn btn-success btn-sm fw-bold"
                               style="border-radius: 15px;">
                                Take Super Quiz Now
                            </a>
                        </div>
                    </div>
                </div>

                @if($quizzes->isEmpty())
                    <div class="alert alert-dark text-center rounded-4 p-5 bg-white bg-opacity-25 border-0">
                        <i class="fas fa-telescope fa-4x text-light mb-4"></i>
                        <p class="h2 text-light mb-3">No Quizzes Available</p>
                        <p class="text-light opacity-75">New challenges coming soon!</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($quizzes as $quiz)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card border-0 shadow-lg overflow-hidden h-100 transition-all">
                                    <div class="card-header bg-danger text-white py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-puzzle-piece fa-lg me-2"></i>
                                            <h5 class="card-title mb-0 fw-bold">{{ $quiz->title ?? 'Untitled Quiz' }}</h5>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white position-relative pb-0">
                                        <div class="quiz-content">
                                            <p class="text-muted mb-4">
                                                <i class="fas fa-align-left me-2 text-danger"></i>
                                                {{ Str::limit($quiz->description ?? 'General knowledge challenge', 80) }}
                                            </p>

                                            <div class="quiz-details">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-container bg-light-danger rounded-circle p-2">
                                                        <i class="fas fa-clock text-danger fa-fw"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="mb-0 small text-muted">Time Limit</p>
                                                        <p class="mb-0 fw-bold">30 Seconds</p>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="icon-container bg-light-success rounded-circle p-2">
                                                        <i class="fas fa-coins text-success fa-fw"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="mb-0 small text-muted">Reward</p>
                                                        <p class="mb-0 fw-bold">30 Tesla</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light border-0 pt-0 pb-3">
                                        @if ($quiz->is_completed)
                                            <a href="{{ route('student.quizzes.result', $quiz->last_attempt_unique_id) }}"
                                               class="btn btn-success btn-hover-scale w-100 py-2 fw-bold mb-3">
                                                <i class="fas fa-eye me-2"></i>View Result
                                            </a>
                                            <div class="text-center">
                                                <p class="text-muted small mb-1">You can retry this quiz in:</p>
                                                <div id="countdown-{{ $quiz->unique_id }}" class="fw-bold text-danger">
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('student.quizzes.show', $quiz->unique_id) }}"
                                               class="btn btn-danger btn-hover-scale w-100 py-2 fw-bold">
                                                <i class="fas fa-play me-2"></i>Start Challenge
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- =========== MODAL QUIZ HISTORY =========== -->
    <div class="modal fade" id="quizHistoryModal" tabindex="-1" aria-labelledby="quizHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bg-white rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="quizHistoryModalLabel">
                        <i class="fas fa-history me-2"></i>Quiz History & {{ __('Leaderboard') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Riwayat Poin User Sendiri -->
                    <h6 class="fw-bold mb-3">My Quiz Points History</h6>
                    @if($userPointsHistory->isEmpty())
                        <p class="text-muted fst-italic">You haven't completed any quiz yet.</p>
                    @else
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-striped align-middle">
                                <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Points</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($userPointsHistory as $index => $uph)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $uph->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $uph->description }}</td>
                                        <td class="fw-bold text-success">{{ $uph->points }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Riwayat / {{ __('Leaderboard') }} User Lain (Top 20) -->
                    <h6 class="fw-bold mb-3">Others' Quiz Scores (Top 20)</h6>
                    @if($otherUsersPointsHistory->isEmpty())
                        <p class="text-muted fst-italic">No one else has completed a quiz yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle">
                                <thead class="table-danger">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Points</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($otherUsersPointsHistory as $index => $ouph)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ouph->user->name ?? __('Unknown User') }}</td>
                                        <td>{{ $ouph->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $ouph->description }}</td>
                                        <td class="fw-bold text-danger">{{ $ouph->points }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- =========== END MODAL QUIZ HISTORY =========== -->


    <style>
        .quiz-content { position: relative; z-index: 1; }
        .icon-container {
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
        }
        .card {
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        .btn-hover-scale {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .btn-hover-scale:hover {
            transform: scale(1.03);
        }
        .bg-light-danger { background-color: rgba(220, 53, 69, 0.1); }
        .bg-light-success { background-color: rgba(40, 167, 69, 0.1); }
    </style>

    <!-- Countdown Script -->
    <script>
        @foreach ($quizzes as $quiz)
        @if ($quiz->is_completed && $quiz->retry_in > 0)
        @php
            // Untuk nama variabel JS yang aman, ganti "-" dengan "_"
            $sanitizedUniqueId = str_replace('-', '_', $quiz->unique_id);
        @endphp

        const countdownElement{{ $sanitizedUniqueId }} = document.getElementById('countdown-{{ $quiz->unique_id }}');
        let retryIn{{ $sanitizedUniqueId }} = {{ $quiz->retry_in }};

        const updateCountdown{{ $sanitizedUniqueId }} = () => {
            if (retryIn{{ $sanitizedUniqueId }} > 0) {
                const hours = Math.floor(retryIn{{ $sanitizedUniqueId }} / 3600);
                const minutes = Math.floor((retryIn{{ $sanitizedUniqueId }} % 3600) / 60);
                const seconds = retryIn{{ $sanitizedUniqueId }} % 60;

                countdownElement{{ $sanitizedUniqueId }}.innerHTML =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                retryIn{{ $sanitizedUniqueId }}--;
            } else {
                countdownElement{{ $sanitizedUniqueId }}.innerHTML = "00:00:00";
                clearInterval(interval{{ $sanitizedUniqueId }});
                location.reload();
            }
        };

        const interval{{ $sanitizedUniqueId }} = setInterval(updateCountdown{{ $sanitizedUniqueId }}, 1000);
        updateCountdown{{ $sanitizedUniqueId }}(); // panggil awal
        @endif
        @endforeach
    </script>
@endsection