@extends('layout.owner')

@section('title', 'Create Super Quiz')
@section('page-title', 'Create New Super Quiz')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm rounded" role="alert" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
        <div class="card-header bg-primary text-white py-3 rounded-top" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem 0.75rem 0 0;">
            <h5 class="mb-0" style="font-size: 1.25rem;">
                <i class="fas fa-puzzle-piece me-2"></i>
                Create New Super Quiz
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('superquizzes.store', $business->business_unique_id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="form-label">Quiz Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="max_score" class="form-label">Maximum Score</label>
                    <input type="number" class="form-control" id="max_score" name="max_score" value="{{ old('max_score') }}" required>
                    @error('max_score')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="question_limit" class="form-label">Question Limit</label>
                    <input type="number" class="form-control" id="question_limit" name="question_limit" value="{{ old('question_limit') }}" required>
                    @error('question_limit')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4" style="padding: 0.6rem 1.2rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                        <i class="fas fa-save me-2"></i> Save Super Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card.shadow-lg.mb-4.rounded:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-success.rounded-pill.shadow-sm:hover {
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
