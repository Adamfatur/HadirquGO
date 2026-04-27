@extends('layout.student')

@section('content')
    <div class="min-vh-100 ">
        <div class="container py-5">
            <div class="max-w-600 mx-auto">
                <!-- Header -->
                <div class="text-center mb-5 animate__animated animate__fadeInDown">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-4x text-gradient-primary"></i>
                    </div>
                    <h3 class="display-5 fw-bold text-light mb-3">Quiz Result</h3>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="wave-divider"></div>
                    </div>
                </div>

                <!-- Result Card -->
                <div class="card shadow-lg border-0 rounded-3 animate__animated animate__zoomIn">
                    <div class="card-body p-4 p-md-5">
                        <!-- Progress Circle -->
                        <!--
                             Tetap gunakan $correctCount * 20 di data-progress,
                             karena itu adalah persentase (0-100%) dari 5 soal
                        -->
                        <div class="progress-circle mx-auto mb-4" data-progress="{{ $correctCount * 20 }}">
                            <div class="progress-circle-inner">
                                <div class="progress-circle-number text-gradient-primary">
                                    <!-- Persentase jawaban benar -->
                                    {{ $correctCount * 20 }}<small>%</small>
                                </div>
                            </div>
                        </div>

                        <!-- Score Details -->
                        <div class="text-center mb-4">
                            <h3 class="h2 fw-bold text-dark mb-2">{{ $correctCount }}/5 Correct Answers</h3>
                            <p class="text-muted mb-0">You answered {{ $correctCount }} questions correctly</p>

                            <div class="result-separator my-4">
                                <p class="text-muted mb-0">
                                    <span class="h5 fw-bold me-2 text-gradient-primary">Total Points:</span>
                                    <!-- Ubah menjadi correctCount * 6 untuk poin yang diperoleh,
                                         dan tampilkan /30 sebagai poin maksimal -->
                                    <span class="h3">{{ $correctCount * 6 }}</span>
                                    <small class="text-muted ms-2">/ 30</small>
                                </p>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-6">
                                <div class="stat-card bg-primary-soft text-primary p-3 rounded-3 d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-check-double fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="h5 fw-bold mb-1">{{ $correctCount }}</div>
                                        <small>Correct</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="stat-card bg-danger-soft text-danger p-3 rounded-3 d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-times-circle fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="h5 fw-bold mb-1">{{ 5 - $correctCount }}</div>
                                        <small>Incorrect</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="text-center mt-4">
                            <a href="{{ route('student.quizzes.index') }}"
                               class="btn btn-sm btn-primary rounded-pill px-5 py-3 shadow-sm hover-lift">
                                <i class="fas fa-arrow-left me-2"></i>Back to Quizzes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --progress-size: 160px;
            --progress-border: 8px;
        }

        .max-w-600 {
            max-width: 600px;
        }

        .text-gradient-primary {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .progress-circle {
            width: var(--progress-size);
            height: var(--progress-size);
            position: relative;
        }

        .progress-circle-inner {
            position: absolute;
            width: calc(100% - var(--progress-border)*2);
            height: calc(100% - var(--progress-border)*2);
            border-radius: 50%;
            background: white;
            top: var(--progress-border);
            left: var(--progress-border);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .progress-circle-number {
            font-size: 2.2rem;
            font-weight: 700;
        }

        .wave-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #4e54c8 50%, transparent 100%);
            border-radius: 2px;
        }

        .stat-card {
            min-height: 100px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .bg-primary-soft {
            background: rgba(78, 84, 200, 0.1);
        }

        .bg-danger-soft {
            background: rgba(220, 53, 69, 0.1);
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .result-separator {
            border-bottom: 2px solid rgba(78, 84, 200, 0.2);
            margin: 1rem 0;
        }

        @media (max-width: 576px) {
            :root {
                --progress-size: 140px;
                --progress-border: 6px;
            }

            .progress-circle-number {
                font-size: 1.8rem;
            }

            .stat-card {
                min-height: 80px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress circle
            const progressCircle = document.querySelector('.progress-circle');
            const progress = parseInt(progressCircle.dataset.progress);
            const circumference = 2 * Math.PI * (parseInt(getComputedStyle(progressCircle).width) / 2 - 8);

            const circle = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            circle.setAttribute('width', '100%');
            circle.setAttribute('height', '100%');
            circle.innerHTML = `
                <circle class="progress-ring__circle"
                        stroke="#4e54c8"
                        stroke-width="8"
                        stroke-dasharray="${circumference} ${circumference}"
                        stroke-linecap="round"
                        fill="transparent"
                        r="${parseInt(getComputedStyle(progressCircle).width) / 2 - 8}"
                        cx="50%"
                        cy="50%"/>
            `;

            progressCircle.prepend(circle);
            const progressRing = circle.querySelector('.progress-ring__circle');
            const offset = circumference - (progress / 100 * circumference);
            progressRing.style.strokeDashoffset = offset;
            progressRing.style.transform = 'rotate(-90deg)';
            progressRing.style.transformOrigin = '50% 50%';
        });
    </script>
@endsection