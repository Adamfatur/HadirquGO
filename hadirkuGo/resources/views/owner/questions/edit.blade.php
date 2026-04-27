@extends('layout.owner')

@section('title', 'Edit Question')
@section('page-title', 'Edit Question for: ' . $quiz->title)

@section('content')
    <div class="container my-5">
        {{-- Alert Error dengan fitur dismissible --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Card Form Edit Question --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Question</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.questions.update', [$business->business_unique_id, $quiz->id, $question->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Question Text --}}
                    <div class="mb-4">
                        <label for="questionText" class="form-label fw-bold">Question Text</label>
                        <textarea
                                name="question_text"
                                id="questionText"
                                rows="4"
                                class="form-control"
                                placeholder="Type your question here..."
                                required
                        >{{ old('question_text', $question->question_text) }}</textarea>
                    </div>

                    <hr>
                    <fieldset class="mb-4">
                        <legend class="h5">Options (Multiple Choice)</legend>
                        @foreach($options as $index => $option)
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Option {{ $option->option_letter }}
                                </label>
                                <div class="input-group">
                                    <input
                                            type="text"
                                            name="options[{{ $index }}][option_text]"
                                            class="form-control"
                                            placeholder="Enter option {{ $option->option_letter }}"
                                            value="{{ old("options.$index.option_text", $option->option_text) }}"
                                    >
                                    {{-- Sertakan huruf option dalam input hidden --}}
                                    <input type="hidden" name="options[{{ $index }}][option_letter]" value="{{ $option->option_letter }}">
                                    <span class="input-group-text">
                                        <div class="form-check mb-0">
                                            <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="options[{{ $index }}][is_correct]"
                                                    id="option_{{ $option->option_letter }}_correct"
                                                    value="1"
                                                {{ old("options.$index.is_correct", $option->is_correct) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label ms-1" for="option_{{ $option->option_letter }}_correct">
                                                Correct?
                                            </label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </fieldset>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('owner.quizzes.show', [$business->business_unique_id, $quiz->id]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 0.5rem;
        }
        .card-header {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .input-group-text {
            background-color: transparent;
            border-left: 0;
        }
        .form-check-input {
            margin-top: 0.3rem;
        }
    </style>
@endsection