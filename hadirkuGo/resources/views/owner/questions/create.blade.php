@extends('layout.owner')

@section('title', 'Create Question')
@section('page-title', 'Create Question for: ' . $quiz->title)

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

        {{-- Card Form Create Question --}}
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Create New Question
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.questions.store', [$business->business_unique_id, $quiz->id]) }}" method="POST">
                    @csrf

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
                        >{{ old('question_text') }}</textarea>
                    </div>

                    <hr>
                    <fieldset class="mb-4">
                        <legend class="h5">Options (Multiple Choice)</legend>

                        {{-- Option A --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Option A</label>
                            <div class="input-group">
                                <input
                                        type="text"
                                        name="options[0][option_text]"
                                        class="form-control"
                                        placeholder="Enter option A"
                                        value="{{ old('options.0.option_text') }}"
                                >
                                <input type="hidden" name="options[0][option_letter]" value="A">
                                <span class="input-group-text">
                                    <div class="form-check mb-0">
                                        <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="options[0][is_correct]"
                                                value="1"
                                                id="optionA_correct"
                                            {{ old('options.0.is_correct') ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label ms-1" for="optionA_correct">
                                            Correct?
                                        </label>
                                    </div>
                                </span>
                            </div>
                        </div>

                        {{-- Option B --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Option B</label>
                            <div class="input-group">
                                <input
                                        type="text"
                                        name="options[1][option_text]"
                                        class="form-control"
                                        placeholder="Enter option B"
                                        value="{{ old('options.1.option_text') }}"
                                >
                                <input type="hidden" name="options[1][option_letter]" value="B">
                                <span class="input-group-text">
                                    <div class="form-check mb-0">
                                        <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="options[1][is_correct]"
                                                value="1"
                                                id="optionB_correct"
                                            {{ old('options.1.is_correct') ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label ms-1" for="optionB_correct">
                                            Correct?
                                        </label>
                                    </div>
                                </span>
                            </div>
                        </div>

                        {{-- Option C --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Option C</label>
                            <div class="input-group">
                                <input
                                        type="text"
                                        name="options[2][option_text]"
                                        class="form-control"
                                        placeholder="Enter option C"
                                        value="{{ old('options.2.option_text') }}"
                                >
                                <input type="hidden" name="options[2][option_letter]" value="C">
                                <span class="input-group-text">
                                    <div class="form-check mb-0">
                                        <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="options[2][is_correct]"
                                                value="1"
                                                id="optionC_correct"
                                            {{ old('options.2.is_correct') ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label ms-1" for="optionC_correct">
                                            Correct?
                                        </label>
                                    </div>
                                </span>
                            </div>
                        </div>

                        {{-- Option D --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Option D</label>
                            <div class="input-group">
                                <input
                                        type="text"
                                        name="options[3][option_text]"
                                        class="form-control"
                                        placeholder="Enter option D"
                                        value="{{ old('options.3.option_text') }}"
                                >
                                <input type="hidden" name="options[3][option_letter]" value="D">
                                <span class="input-group-text">
                                    <div class="form-check mb-0">
                                        <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="options[3][is_correct]"
                                                value="1"
                                                id="optionD_correct"
                                            {{ old('options.3.is_correct') ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label ms-1" for="optionD_correct">
                                            Correct?
                                        </label>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('owner.quizzes.show', [$business->business_unique_id, $quiz->id]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Create Question
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