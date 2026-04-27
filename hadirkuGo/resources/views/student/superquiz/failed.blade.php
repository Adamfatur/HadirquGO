@extends('layout.student') {{-- Layout diubah menjadi student --}}

@section('title', 'Quiz Failed')
@section('page-title', 'Quiz Failed')

@section('content')
    <div class="min-vh-100 bg-light-primary"> {{-- Add light primary background for consistency --}}
        <div class="container py-5">
            <div class="max-w-600 mx-auto">
                <div class="text-center mb-5 animate__animated animate__fadeInDown">
                    <div class="mb-4">
                        <i class="fas fa-times-circle fa-4x text-danger"></i> {{-- Red times-circle icon --}}
                    </div>
                    <h3 class="display-5 fw-bold text-light mb-3">Quiz Failed!</h3> {{-- Updated title --}}
                    <div class="wave-divider wave-divider-danger"></div> {{-- Danger colored wave divider --}}
                </div>

                <div class="card shadow-lg border-0 rounded-3 animate__animated animate__zoomIn">
                    <div class="card-body p-4 p-md-5 text-center"> {{-- Center align text in card body --}}

                        <h2 class="card-title fw-semibold text-danger mb-3" style="font-size: 2.3rem;">
                            You Failed This Question!
                        </h2>

                        <p class="card-text lead text-center mb-4 text-muted" style="font-size: 1.1rem; line-height: 1.7;">
                            Unfortunately, your answer to question number <span class="fw-bold text-danger">{{ $questionNumber }}</span> was incorrect.
                        </p>

                        <div class="text-center mb-4"> {{-- Spacing before points lost message --}}
                            <p class="card-text text-center mb-1" style="font-size: 1.2rem;">
                                Points lost: <span class="fw-bold text-danger" style="font-size: 1.5rem;">All {{ __('Tesla Points') }} <i class="fas fa-bolt ms-1"></i></span>
                            </p>
                            <p class="card-text text-center text-muted" style="font-size: 1rem;">
                                Better luck next time!
                            </p>
                        </div>


                        <div class="d-grid gap-3 col-md-6 mx-auto mb-4">
                            <a href="{{ route('student.superquiz.index') }}" {{-- Route back to index diubah ke student --}}
                            class="btn btn-primary btn-sm rounded-pill px-5 py-3 shadow-sm hover-lift"> {{-- Primary button style --}}
                                <i class="fas fa-arrow-left me-2"></i> Back to Quiz Collection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .max-w-600 {
            max-width: 600px;
        }

        .wave-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #4e54c8 50%, transparent 100%);
            border-radius: 2px;
            margin: 0 auto 1.5rem; /* Center and add margin bottom */
        }

        .wave-divider-danger { /* New class for danger color wave divider */
            background: linear-gradient(90deg, transparent 0%, #dc3545 50%, transparent 100%);
        }


        .bg-light-primary {
            background: #f0faff; /* Light primary background */
        }


        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }


        @media (max-width: 576px) {

        }
    </style>

@endsection