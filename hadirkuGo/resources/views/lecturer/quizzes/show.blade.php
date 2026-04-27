@extends('layout.lecturer')

@section('content')
    <div class="container py-5">
        <!-- BAGIAN PENTING: RESET localStorage JIKA BOLEH QUIZ BARU -->
        @if(isset($resetLocalStorage) && $resetLocalStorage)
            <script>
                // Karena user belum attempt quiz hari ini (menurut controller),
                // reset localStorage agar timer mulai dari awal
                localStorage.removeItem('quizStartTime');
            </script>
        @endif
        <!-- END BAGIAN RESET -->

        <!-- Header -->
        <div class="text-center mb-5">
            <div class="badge bg-danger rounded-pill px-4 py-2 mb-3 animate__animated animate__fadeInDown">
                <h2 class="mb-0 text-white">{{ $quiz->title }}</h2>
            </div>
        </div>

        <!-- Timer Card -->
        <div class="timer-card mb-4">
            <div class="card bg-light border shadow-sm" style="border-radius: 8px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="time-text fw-bold text-success" style="font-size: 1rem;">
                            <i class="fas fa-clock me-2"></i>
                            <!-- Jika 30 detik, tampilan awal "00:30" -->
                            <!-- Jika 30 menit, tampilan awal "30:00" -->
                            <span class="time-value">00:30</span>
                        </span>
                    </div>
                    <!-- Warning Message -->
                    <div class="text-center text-muted mt-2" style="font-size: 0.8rem;">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        The quiz will auto-submit when time expires.
                        Please complete before time runs out.
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Form -->
        <form action="{{ route('lecturer.quizzes.store', $quiz->unique_id) }}" method="POST" id="quizForm">
            @csrf
            <div id="quizContainer">
                @if ($questions->isNotEmpty())
                    @foreach ($questions as $index => $question)
                        <div class="quiz-step card border-0 shadow-lg mb-4 animate__animated animate__fadeIn"
                             id="question_{{ $index }}"
                             style="display: {{ $index == 0 ? 'block' : 'none' }}">
                            <div class="card-body p-4">
                                <!-- Question Header -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="question-number badge bg-danger rounded-pill px-3">
                                        Pertanyaan #{{ $index + 1 }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-coins me-1"></i>
                                        +{{ $question->points }} Tesla
                                    </div>
                                </div>

                                <!-- Question Text -->
                                <h4 class="card-title mb-4 fw-semibold text-dark">{{ $question->question_text }}</h4>

                                <!-- Options -->
                                <div class="options-grid">
                                    @foreach ($question->options as $option)
                                        <div class="option-card">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   id="option_{{ $option->id }}"
                                                   value="{{ $option->option_letter }}"
                                                   class="option-input"
                                                   required>
                                            <label for="option_{{ $option->id }}"
                                                   class="option-label d-flex align-items-center p-3 rounded-3">
                                                <div class="option-letter bg-danger text-white rounded-circle me-3">
                                                    {{ strtoupper($option->option_letter) }}
                                                </div>
                                                <div class="option-text">{{ $option->option_text }}</div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-dark text-center rounded-4 p-5 bg-white bg-opacity-25 border-0">
                        <i class="fas fa-telescope fa-4x text-light mb-4"></i>
                        <p class="h2 text-light mb-3">Tidak Ada Pertanyaan</p>
                        <p class="text-light opacity-75">Silahkan kembali nanti!</p>
                    </div>
                @endif
            </div>

            <!-- Navigation Controls -->
            <div class="quiz-controls fixed-bottom bg-white border-top py-3">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button"
                                class="btn btn-outline-danger prev-btn rounded-pill px-4"
                                disabled>
                            <i class="fas fa-chevron-left me-2"></i>Sebelumnya
                        </button>

                        <div class="progress-text small text-muted">
                            <span class="current-question">1</span>/{{ count($questions) }}
                        </div>

                        <button type="button"
                                class="btn btn-danger next-btn rounded-pill px-4">
                            Selanjutnya <i class="fas fa-chevron-right ms-2"></i>
                        </button>

                        <button type="submit"
                                class="btn btn-success submit-btn rounded-pill px-4"
                                style="display: none;">
                            <i class="fas fa-paper-plane me-2"></i>{{ __('Submit') }} Jawaban
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .quiz-step {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
            opacity: 1;
            position: relative;
        }
        .quiz-step.hidden {
            transform: translateY(20px);
            opacity: 0;
            display: none;
        }
        .timer-card .card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .timer-card .card-body {
            padding: 0.5rem;
        }
        .timer-card .time-text {
            font-size: 1rem;
        }
        .timer-card .fa-clock {
            font-size: 1rem;
        }
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }
        .option-card {
            position: relative;
        }
        .option-input {
            position: absolute;
            opacity: 0;
        }
        .option-label {
            border: 2px solid #e9ecef;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .option-input:checked + .option-label {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }
        .option-input:focus + .option-label {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
        }
        .option-letter {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .quiz-controls {
            box-shadow: 0 -5px 30px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
            .quiz-controls .btn {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = document.querySelectorAll('.quiz-step');
            const nextButtons = document.querySelectorAll('.next-btn');
            const prevButtons = document.querySelectorAll('.prev-btn');
            const submitBtn = document.querySelector('.submit-btn');
            const progressText = document.querySelector('.progress-text');
            const totalQuestions = questions.length;
            let currentQuestion = 0;

            // Timer Configuration
            // Ubah totalTime (detik) --> 30 detik atau 1800 utk 30 menit
            const totalTime = 30; // 30 detik untuk contoh
            let timeLeft = totalTime;
            let timerInterval;

            // Ambil waktu mulai dari localStorage jika ada
            const startTime = localStorage.getItem('quizStartTime');
            if (startTime) {
                const elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                timeLeft = totalTime - elapsedTime;
                if (timeLeft <= 0) {
                    timeLeft = 0;
                    localStorage.removeItem('quizStartTime');
                }
            } else {
                // Simpan waktu mulai di localStorage
                localStorage.setItem('quizStartTime', Date.now());
            }

            function updateTimerColor(timeLeft) {
                const timerText = document.querySelector('.time-text');
                // Ubah threshold jadi 10 detik (silakan disesuaikan)
                if (timeLeft <= 10) {
                    timerText.classList.remove('text-success');
                    timerText.classList.add('text-danger');
                } else {
                    timerText.classList.remove('text-danger');
                    timerText.classList.add('text-success');
                }
            }

            function startTimer() {
                let timePassed = totalTime - timeLeft;

                timerInterval = setInterval(() => {
                    timePassed += 1;
                    timeLeft = totalTime - timePassed;

                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    document.querySelector('.time-value').textContent =
                        `${minutes}:${seconds.toString().padStart(2, '0')}`;

                    updateTimerColor(timeLeft);

                    if (timePassed >= totalTime) {
                        clearInterval(timerInterval);
                        localStorage.removeItem('quizStartTime');
                        submitForm();
                    }
                }, 1000);
            }

            function updateNavigation() {
                if (progressText) {
                    // Misal: "1/5"
                    progressText.querySelector('.current-question').textContent = currentQuestion + 1;
                }
                if (submitBtn) {
                    submitBtn.style.display = currentQuestion === totalQuestions - 1 ? 'block' : 'none';
                }
                nextButtons.forEach(btn => {
                    if (btn) btn.style.display = currentQuestion < totalQuestions - 1 ? 'block' : 'none';
                });
                prevButtons.forEach(btn => {
                    if (btn) btn.disabled = currentQuestion === 0;
                });
            }

            function showQuestion(index) {
                questions.forEach((question, i) => {
                    if (question) {
                        question.style.display = i === index ? 'block' : 'none';
                    }
                });
                updateNavigation();
            }

            // Navigation Handlers
            nextButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentQuestion < totalQuestions - 1) {
                        currentQuestion++;
                        showQuestion(currentQuestion);
                    }
                });
            });

            prevButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentQuestion > 0) {
                        currentQuestion--;
                        showQuestion(currentQuestion);
                    }
                });
            });

            // Form Submission
            function submitForm() {
                document.getElementById('quizForm').submit();
            }

            // Start timer when page loads
            startTimer();
        });
    </script>
@endsection