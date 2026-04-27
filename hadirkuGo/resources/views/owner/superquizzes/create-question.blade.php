@extends('layout.owner')

@section('title', 'Add Question to Super Quiz')
@section('page-title', 'Add Question to Super Quiz')

@section('content')

    <div class="container">
        <div class="card shadow-lg"> {{-- Tambahkan shadow dan card untuk tampilan modern --}}
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"> {{-- Header card berwarna dan kontras --}}
                <h5 class="m-0 font-weight-bold"><i class="fas fa-plus-circle me-2"></i> Add New Question</h5> {{-- Ikon untuk memperjelas fungsi --}}
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning">
                        <b>Oops! There are some errors:</b>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('questions.store', [$business->business_unique_id, $superQuiz->unique_id]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="question_text" class="form-label fw-bold">Question Text <span class="text-danger">*</span></label> {{-- Label lebih tebal dan wajib --}}
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required placeholder="Enter the question text here"></textarea> {{-- Placeholder untuk panduan --}}
                    </div>

                    <div class="row mb-3"> {{-- Row untuk opsi A dan B --}}
                        <div class="col-md-6">
                            <label for="option_a" class="form-label fw-semibold">Option A <span class="text-danger">*</span></label> {{-- Label opsi lebih menonjol --}}
                            <div class="input-group"> {{-- Input group untuk teks dan radio button --}}
                                <input type="text" class="form-control" id="option_a" name="option_a" required placeholder="Enter option A text"> {{-- Placeholder opsi --}}
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="A" aria-label="Radio button for correct option A">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="option_b" class="form-label fw-semibold">Option B <span class="text-danger">*</span></label> {{-- Label opsi lebih menonjol --}}
                            <div class="input-group"> {{-- Input group untuk teks dan radio button --}}
                                <input type="text" class="form-control" id="option_b" name="option_b" required placeholder="Enter option B text"> {{-- Placeholder opsi --}}
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="B" aria-label="Radio button for correct option B">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3"> {{-- Row untuk opsi C dan D --}}
                        <div class="col-md-6">
                            <label for="option_c" class="form-label fw-semibold">Option C <span class="text-danger">*</span></label> {{-- Label opsi lebih menonjol --}}
                            <div class="input-group"> {{-- Input group untuk teks dan radio button --}}
                                <input type="text" class="form-control" id="option_c" name="option_c" required placeholder="Enter option C text"> {{-- Placeholder opsi --}}
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="C" aria-label="Radio button for correct option C">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="option_d" class="form-label fw-semibold">Option D <span class="text-danger">*</span></label> {{-- Label opsi lebih menonjol --}}
                            <div class="input-group"> {{-- Input group untuk teks dan radio button --}}
                                <input type="text" class="form-control" id="option_d" name="option_d" required placeholder="Enter option D text"> {{-- Placeholder opsi --}}
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="D" aria-label="Radio button for correct option D">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4"> {{-- Tombol hijau, rounded, shadow --}}
                            <i class="fas fa-save me-2"></i> Add Question {{-- Ikon save --}}
                        </button>
                        <a href="{{ route('superquizzes.show', [$business->business_unique_id, $superQuiz->unique_id]) }}"
                           class="btn btn-secondary rounded-pill shadow-sm px-4 ms-2"> {{-- Tombol secondary, rounded, shadow --}}
                            <i class="fas fa-times me-2"></i> Cancel {{-- Ikon cancel --}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card.shadow-lg {
            border-radius: 0.75rem; /* Lebih rounded card */
        }

        .card-header {
            border-radius: 0.75rem 0.75rem 0 0; /* Lebih rounded header card */
            padding: 1.25rem 1.5rem; /* Padding lebih besar di header */
        }

        .btn.rounded-pill {
            border-radius: 2.5rem; /* Lebih rounded tombol pill */
        }

        .btn-success.rounded-pill.shadow-sm,
        .btn-secondary.rounded-pill.shadow-sm {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, opacity 0.2s ease-in-out; /* Transisi lebih smooth */
        }

        .btn-success.rounded-pill.shadow-sm:hover {
            opacity: 0.95; /* Efek hover lebih halus */
            transform: translateY(-2px) scale(1.05); /* Efek translate Y dan scale saat hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Box shadow lebih menonjol saat hover */
        }

        .btn-secondary.rounded-pill.shadow-sm:hover {
            opacity: 0.95; /* Efek hover lebih halus */
            transform: translateY(-2px) scale(1.05); /* Efek translate Y dan scale saat hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Box shadow lebih menonjol saat hover */
        }

        .form-label {
            margin-bottom: 0.5rem; /* Spasi bawah label */
            color: #343a40; /* Warna teks label lebih gelap */
        }

        .form-control {
            border-radius: 0.5rem; /* Input lebih rounded */
            border-color: #ced4da; /* Warna border input default Bootstrap */
            box-shadow: inset 0 1px 2px rgba(0,0,0,.075); /* Efek shadow tipis di dalam input */
        }

        .form-control:focus {
            border-color: #0d6efd; /* Warna border focus primary Bootstrap */
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25); /* Efek shadow focus primary Bootstrap */
        }


        .input-group-text {
            background-color: #f8f9fa; /* Warna latar belakang input-group-text lebih terang */
            border-color: #ced4da;
            border-radius: 0.5rem; /* Input group text lebih rounded */
        }

        .form-check-input {
            cursor: pointer; /* Cursor pointer radio button */
        }
    </style>
@endsection