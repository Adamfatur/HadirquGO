@extends('layout.lecturer')

@section('content')
    <div class="container py-5">
        @if(isset($resetLocalStorage) && $resetLocalStorage)
            <script>
                // Because the user has not attempted the quiz today (according to the controller),
                // reset localStorage so the timer starts from the beginning
                localStorage.removeItem('quizStartTime');
            </script>
        @endif
        <div class="text-center mb-5">
            <div class="badge bg-danger rounded-pill px-4 py-2 mb-3 animate__animated animate__fadeInDown">
                <h2 class="mb-0 text-white">{{ $superQuiz->title }}</h2>
            </div>
        </div>

        <div class="timer-card mb-4">
            <div class="card bg-light border shadow-sm" style="border-radius: 8px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-center">
                    <span class="time-text fw-bold text-success" style="font-size: 1rem;">
                        <i class="fas fa-clock me-2"></i>
                        <span class="time-value">00:10</span> {{-- Initial value remains 00:10, will be updated by JS --}}
                    </span>
                    </div>
                    <div class="text-center text-muted mt-2" style="font-size: 0.8rem;">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        The quiz will auto-submit when time expires.
                        Please answer quickly!
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('lecturer.superquiz.submitAnswer', [$superQuiz->unique_id, $questionNumber]) }}" method="POST" id="quizForm">
            @csrf
            <div id="quizContainer">
                @if ($question)
                    <div class="quiz-step card border-0 shadow-lg mb-4 animate__animated animate__fadeIn"
                         id="question_0"> {{-- Because there is only one question per page, ID can be static --}}
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="question-number badge bg-danger rounded-pill px-3">
                                    Question #{{ $questionNumber }}
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-coins me-1"></i>
                                    +5 Tesla {{-- Super Quiz points are always 5 per correct question --}}
                                </div>
                            </div>

                            <h4 class="card-title mb-4 fw-semibold text-dark">{{ $question->question_text }}</h4>

                            <div class="options-grid">
                                @foreach ($question->options as $option)
                                    <div class="option-card">
                                        <input type="radio"
                                               name="selected_option_id" {{-- Input name changed to match controller --}}
                                               id="option_{{ $option->unique_id }}" {{-- Option ID uses unique_id --}}
                                               value="{{ $option->unique_id }}" {{-- Option value uses unique_id --}}
                                               class="option-input"
                                               required>
                                        <label for="option_{{ $option->unique_id }}" {{-- Label for uses unique_id --}}
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
                @else
                    <div class="alert alert-dark text-center rounded-4 p-5 bg-white bg-opacity-25 border-0">
                        <i class="fas fa-question-circle fa-4x text-light mb-4"></i>
                        <p class="h2 text-light mb-3">Question Not Found</p>
                        <p class="text-light opacity-75">An error occurred while retrieving the question.</p>
                    </div>
                @endif
            </div>

            <div class="quiz-controls fixed-bottom bg-white border-top py-3">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Removed Surrender Button Form --}}

                        <div class="progress-text small text-muted">
                            Question <span class="current-question">{{ $questionNumber }}</span>/10
                        </div>

                        <div> {{-- Container for {{ __('Submit') }} button --}}
                            <button type="submit"
                                    class="btn btn-danger submit-btn rounded-pill px-4">
                                <i class="fas fa-paper-plane me-2"></i> {{ __('Submit') }} Answer
                            </button>
                        </div>
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
            // --- DOM Element Selectors ---
            const submitBtn = document.querySelector('.submit-btn'); // Submit button
            const progressText = document.querySelector('.progress-text'); // Question progress display
            const questionNumberDisplay = document.querySelector('.current-question'); // Current question number display
            const timerValueDisplay = document.querySelector('.time-value'); // Timer display element
            const timerTextElement = document.querySelector('.time-text'); // Timer text element (for color change)

            // --- Quiz Configuration ---
            const totalQuestions = 10; // Total number of Super Quiz questions (constant)
            let currentQuestionNumber = {{ $questionNumber }}; // Question number from backend
            const totalTime = 10; // Time limit per question in seconds
            let timeLeft = totalTime; // Time remaining for the current question
            let timerInterval; // Variable to hold the timer interval

            // --- Local Storage Key for Timer (Question-Specific) ---
            const startTimeKey = 'quizStartTime_question_' + currentQuestionNumber; // Unique key to store start time in localStorage per question
            const lastAttemptDateKey = 'lastQuizAttemptDate'; // Key to store last attempt date in localStorage

            // --- Timer Functions ---

            /**
             * Updates the timer display and color based on time left.
             * @param {number} timeLeft - Time left in seconds.
             */
            function updateTimerDisplay(timeLeft) {
                const minutes = Math.floor(timeLeft / 60); // Calculate minutes
                const seconds = timeLeft % 60;          // Calculate seconds
                timerValueDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`; // Update timer display in MM:SS format
            }

            /**
             * Updates the timer text color based on remaining time.
             * Changes color to red if time is 3 seconds or less, otherwise green.
             * @param {number} timeLeft - Time left in seconds.
             */
            function updateTimerColor(timeLeft) {
                if (timeLeft <= 3) {
                    timerTextElement.classList.remove('text-success'); // Remove green color class
                    timerTextElement.classList.add('text-danger');  // Add red color class for low time
                } else {
                    timerTextElement.classList.remove('text-danger'); // Remove red color class
                    timerTextElement.classList.add('text-success');   // Add green color class for normal time
                }
            }

            /**
             * Starts the timer interval.
             * Updates the timer display every second and auto-submits the form when time runs out.
             */
            function startTimer() {
                timeLeft = totalTime; // Reset timeLeft at the start of each timer instance
                updateTimerDisplay(timeLeft); // Initial timer display

                timerInterval = setInterval(() => {
                    timeLeft--; // Decrement timeLeft each second
                    updateTimerDisplay(timeLeft); // Update timer display

                    if (timeLeft <= 0) {
                        clearInterval(timerInterval); // Stop the timer interval
                        localStorage.removeItem(startTimeKey); // Clear start time from localStorage
                        sendTimeoutRequest(); // Send request to backend to mark attempt as failed
                        document.querySelector('.submit-btn').disabled = true; // Disable submit button
                    }
                    updateTimerColor(timeLeft); // Update timer color based on remaining time every second
                }, 1000); // Interval of 1000 milliseconds (1 second)
            }

            /**
             * Sends a request to the backend to mark the attempt as failed and redirect to index.
             */
            function sendTimeoutRequest() {
                fetch("{{ route('lecturer.superquiz.timeoutAttempt', $superQuiz->unique_id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        super_quiz_id: "{{ $superQuiz->unique_id }}",
                    })
                })
                    .then(response => {
                        if (response.ok) {
                            // Redirect to the result page after timeout
                            window.location.href = "{{ route('lecturer.superquiz.viewResult', $superQuiz->unique_id) }}";
                        } else {
                            console.error('Error marking attempt as failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            /**
             * Updates the question navigation text (e.g., Question 1/10).
             */
            function updateNavigation() {
                if (progressText && questionNumberDisplay) {
                    questionNumberDisplay.textContent = currentQuestionNumber; // Update current question number in display
                }
            }

            /**
             * {{ __('Submit') }}s the quiz form programmatically.
             */
            function submitForm() {
                document.getElementById('quizForm').submit(); // Submit the form with ID 'quizForm'
            }

            /**
             * Initializes the timer and navigation on page load.
             */
            function initializeQuizPage() {
                // --- Reset localStorage on every page access ---
                localStorage.removeItem(startTimeKey); // Reset start time in localStorage

                // --- Local Storage Time Check & Timer Adjustment ---
                const lastAttemptDate = localStorage.getItem(lastAttemptDateKey);
                const todayDate = new Date().toISOString().split('T')[0]; // Mendapatkan tanggal hari ini (yyyy-mm-dd)

                // Jika hari sudah berganti, reset localStorage
                if (lastAttemptDate !== todayDate) {
                    // localStorage.removeItem(startTimeKey); // No need to remove again, already removed above
                    localStorage.setItem(lastAttemptDateKey, todayDate); // Menyimpan tanggal hari ini
                }

                const startTime = localStorage.getItem(startTimeKey); // Try to get start time from localStorage

                if (startTime) {
                    // If start time is found in localStorage (user might have returned to question)
                    const elapsedTime = Math.floor((Date.now() - startTime) / 1000); // Calculate elapsed time since start
                    timeLeft = Math.max(0, totalTime - elapsedTime); // Recalculate timeLeft, ensure it's not negative

                    if (timeLeft <= 0) {
                        timeLeft = 0; // Ensure timeLeft is 0 if time already expired
                        localStorage.removeItem(startTimeKey); // Clear localStorage as time is up (or was up)
                        sendTimeoutRequest(); // Send timeout request to backend
                        document.querySelector('.submit-btn').disabled = true; // Disable submit button
                        return; // Exit initialization as quiz is auto-submitting
                    }
                } else {
                    // If no start time in localStorage (first time loading question)
                    localStorage.setItem(startTimeKey, Date.now()); // Set start time in localStorage
                }

                updateTimerDisplay(timeLeft); // Initial display of timer with adjusted/initial timeLeft
                startTimer(); // Start the timer countdown
                updateNavigation(); // Update question navigation text
            }

            // --- Initialize quiz functionality when DOM is fully loaded ---
            initializeQuizPage();
        });
    </script>
@endsection