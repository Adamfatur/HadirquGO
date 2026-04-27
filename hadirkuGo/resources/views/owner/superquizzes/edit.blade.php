@extends('layout.owner')

@section('title', 'Edit Super Quiz')
@section('page-title', 'Edit Super Quiz')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="card border-0 shadow-lg mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
        <div class="card-header bg-primary text-white py-3 rounded-top" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem 0.75rem 0 0;">
            <h5 class="mb-0" style="font-size: 1.25rem;">
                <i class="fas fa-puzzle-piece me-2"></i>
                Edit Super Quiz
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('superquizzes.update', [$business->business_unique_id, $superQuiz->unique_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="title" class="form-label">Quiz Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $superQuiz->title) }}" required>
                    @error('title')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="max_score" class="form-label">Maximum Score</label>
                    <input type="number" class="form-control" id="max_score" name="max_score" value="{{ old('max_score', $superQuiz->max_score) }}" required>
                    @error('max_score')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="question_limit" class="form-label">Question Limit</label>
                    <input type="number" class="form-control" id="question_limit" name="question_limit" value="{{ old('question_limit', $superQuiz->question_limit) }}" required>
                    @error('question_limit')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-warning rounded-pill shadow-sm px-4" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
                        <i class="fas fa-save me-2"></i> Save Changes
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

        .btn-warning.rounded-pill.shadow-sm:hover {
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
