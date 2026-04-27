@extends('layout.lecturer')

@section('title', 'Correct Answer {{ __('Confirm') }}ation')
@section('page-title', 'Correct Answer {{ __('Confirm') }}ation')

@section('content')
    <div class="min-vh-100"> {{-- Tambahkan background light primary --}}
        <div class="container py-5">
            <div class="max-w-600 mx-auto">
                <div class="text-center mb-5 animate__animated animate__fadeInDown">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-4x text-gradient-primary"></i> {{-- Ikon trophy seperti Super Quiz Result --}}
                    </div>
                    <h3 class="display-5 fw-bold text-light mb-3">Correct Answer!</h3> {{-- Judul diubah --}}
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="wave-divider"></div> {{-- Wave divider seperti Super Quiz Result --}}
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-3 animate__animated animate__zoomIn">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <h2 class="card-title fw-semibold text-dark mb-3 text-center" style="font-size: 2.3rem;">
                                Congratulations!
                            </h2>
                            <p class="card-text lead text-center mb-4 text-muted" style="font-size: 1.1rem; line-height: 1.7;">
                                You answered question number <span class="fw-bold text-gradient-primary">{{ $questionNumber }}</span> correctly!
                            </p>

                            <div class="result-separator my-4"> {{-- Separator seperti Super Quiz Result --}}
                                <p class="text-muted mb-1" style="font-size: 1rem;"> {{-- Reduced mb-0 to mb-1 and smaller font-size --}}
                                    <span class="h5 fw-bold me-2 text-gradient-primary">You earned:</span>
                                    <span class="h3">+5 Tesla </span>
                                </p>
                                <p class="text-muted mb-0" style="font-size: 1rem;"> {{-- Reduced font-size and kept mb-0 but now it's less spaced from the "earned" line --}}
                                    <span class="h5 fw-bold me-2 text-gradient-primary">Your total score is:</span>
                                    <span class="h3">{{ $quizScore }} Tesla</span>
                                </p>
                            </div>
                        </div>


                        <div class="d-grid gap-3 col-md-6 mx-auto mb-4">
                            <a href="{{ route('lecturer.superquiz.question', ['superQuiz' => $superQuiz->unique_id, 'questionNumber' => $questionNumber + 1]) }}"
                               class="btn btn-primary btn-sm rounded-pill px-5 py-3 shadow-sm hover-lift"> {{-- Styling tombol seperti Super Quiz Result --}}
                                <i class="fas fa-arrow-right me-2"></i> Next Question
                            </a>
                        </div>

                        <div class="alert alert-info rounded-lg shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle fa-lg text-warning"></i>
                            <div class="text-block">
                                <p class="mb-0 fw-semibold text-secondary">Important Note:</p>
                                <p class="mb-0 text-secondary" style="font-size: 0.95rem;">
                                    Stopping now keeps your points safe. Continue? Risk losing all points if you fail the next question.
                                </p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <form action="{{ route('lecturer.superquiz.surrenderQuiz', $superQuiz->unique_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm rounded-pill px-5 py-3 shadow-sm hover-lift"> {{-- Styling tombol seperti Super Quiz Result --}}
                                    <i class="fas fa-flag-checkered me-2"></i> Surrender Quiz
                                </button>
                            </form>
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


        .wave-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #4e54c8 50%, transparent 100%);
            border-radius: 2px;
        }


        .bg-primary-soft {
            background: rgba(78, 84, 200, 0.1);
        }
        .bg-danger-soft {
            background: rgba(220, 53, 69, 0.1);
        }
        .bg-light-primary { /* Tambahkan style untuk background light primary */
            background: #f0faff;
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

        .alert-info {
            background-color: #e0f7fa; /* Warna latar belakang alert-info yang sangat lembut */
            border-left: 0.25rem solid #ffc107; /* Border kiri tetap warna warning untuk penekanan */
        }
        .alert-info .text-block {
            flex: 1; /* Agar teks alert memenuhi ruang yang tersedia */
        }


        @media (max-width: 576px) {
            :root {
                --progress-size: 140px;
                --progress-border: 6px;
            }


        }
    </style>

@endsection