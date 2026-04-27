@extends('layout.owner')

@section('title', 'Super Quiz Details')
@section('page-title', 'Super Quiz Details')

@section('content')

    <div class="container">
        <div class="card shadow-lg mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-puzzle-piece me-2"></i>
                    Super Quiz Details
                </h5>
                <div class="d-flex"> {{-- Container untuk tombol-tombol di header, supaya rapi --}}
                    <a href="{{ route('superquizzes.index', [$business->business_unique_id]) }}"
                       class="btn btn-secondary btn-sm rounded-pill shadow-sm me-2">
                        <i class="fas fa-arrow-left me-2"></i> Back {{-- Tombol Back DIPINDAHKAN ke header --}}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Title:</strong></p>
                        <p class="lead">{{ $superQuiz->title }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Unique ID:</strong></p>
                        <p class="lead">{{ $superQuiz->unique_id }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Max Score:</strong></p>
                        <p class="lead">{{ $superQuiz->max_score }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Question Limit:</strong></p>
                        <p class="lead">{{ $superQuiz->question_limit }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="mb-1"><strong>Status:</strong></p>
                    <p class="lead">{{ ucfirst($superQuiz->status) }}</p>
                </div>

                <hr class="mb-4"/>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold mb-0"><i class="fas fa-question-circle me-2"></i> Questions:</h5>
                        @if(!$superQuiz->questions->isEmpty())
                            <a href="{{ route('questions.create', [$business->business_unique_id, $superQuiz->unique_id]) }}"
                               class="btn btn-primary btn-sm rounded-pill shadow-sm">
                                <i class="fas fa-plus me-1"></i> Add New Question
                            </a>
                        @endif
                    </div>

                    @if($superQuiz->questions->isEmpty())
                        <p>No questions added yet.
                            <a href="{{ route('questions.create', [$business->business_unique_id, $superQuiz->unique_id]) }}"
                               class="btn btn-primary btn-sm ms-2 rounded-pill shadow-sm">
                                <i class="fas fa-plus me-1"></i> Add First Question
                            </a>
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th>No.</th> <th>Question Text</th>
                                    <th class="text-center" style="width: 150px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($superQuiz->questions as $question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <td>{{ $question->question_text }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('questions.edit', [$business->business_unique_id, $superQuiz->unique_id, $question->unique_id]) }}"
                                               class="btn btn-warning btn-sm rounded-pill"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('questions.destroy', [$business->business_unique_id, $superQuiz->unique_id, $question->unique_id]) }}"
                                                  method="POST" class="d-inline-block ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-pill"
                                                        onclick="return confirm('Are you sure you want to delete this question?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($superQuiz->questions->isNotEmpty()) {{-- Kondisi agar tombol tidak ganda --}}
                        <a href="{{ route('questions.create', [$business->business_unique_id, $superQuiz->unique_id]) }}" style="display: none;">
                            Add New Question {{-- Tombol Add New Question yang di bawah di sembunyikan --}}
                        </a>
                        @endif
                    @endif
                </div>

                <div class="d-flex justify-content-end mb-4"> {{-- Action buttons KEMBALI DI TEMPAT SEMULA --}}
                    <a href="{{ route('superquizzes.edit', [$business->business_unique_id, $superQuiz->unique_id]) }}"
                       class="btn btn-warning btn-sm rounded-pill shadow-sm me-2">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                    <form action="{{ route('superquizzes.destroy', [$business->business_unique_id, $superQuiz->unique_id]) }}"
                          method="POST" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill shadow-sm"
                                onclick="return confirm('Are you sure you want to delete this quiz?')">
                            <i class="fas fa-trash-alt me-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card.shadow-lg {
            border-radius: 0.5rem; /* Lebih kecil untuk kesan modern */
        }

        .card-header {
            border-radius: 0.5rem 0.5rem 0 0; /* Sesuaikan border-radius header */
            padding: 1rem 1.25rem; /* Padding lebih nyaman */
        }

        .card-title {
            font-size: 1.5rem; /* Ukuran font judul lebih besar */
        }

        .btn-sm {
            padding: 0.5rem 1rem; /* Padding tombol lebih kecil untuk kesan simple */
            font-size: 0.875rem; /* Ukuran font tombol lebih kecil */
        }

        .btn.rounded-pill {
            border-radius: 2rem; /* Lebih bulat untuk tombol */
        }

        .lead {
            font-size: 1.1rem; /* Ukuran font lead lebih besar untuk detail penting */
        }

        .table-responsive {
            overflow-x: auto; /* Membuat tabel responsif */
        }

        .table th,
        .table td {
            padding: 0.75rem 0.5rem; /* Padding sel tabel lebih kecil */
            vertical-align: middle;
        }

        .table thead th {
            border-bottom: 2px solid #e3e6f0; /* Garis bawah header tabel lebih jelas */
            font-weight: bold; /* Teks header tabel bold */
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fc; /* Warna hover baris tabel lebih lembut */
        }
    </style>
@endsection