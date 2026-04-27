@extends('layout.owner')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="card-title mb-0">Edit Question</h1>
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

                <form action="{{ route('questions.update', ['business_unique_id' => $business_unique_id, 'unique_id' => $unique_id, 'question_id' => $question->unique_id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="question_text" class="form-label fw-bold">Question Text <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="4" required placeholder="Enter the question text here">{{ old('question_text', $question->question_text) }}</textarea>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3 fw-bold">Answer Options</h4>
                    <p class="text-muted">Select <span class="fw-bold text-primary">one</span> correct option by checking the "Correct" checkbox.</p>

                    @foreach (['A', 'B', 'C', 'D'] as $optionLetter)
                        @php
                            $option = $question->options->where('option_letter', $optionLetter)->first();
                            $optionText = $option ? $option->option_text : '';
                            $isCorrect = $option ? $option->is_correct : false;
                        @endphp
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="option_{{ $optionLetter }}" class="form-label fw-semibold">Option {{ $optionLetter }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="option_{{ $optionLetter }}" name="options[{{ $loop->index }}][option_text]" value="{{ old('options.' . $loop->index . '.option_text', $optionText) }}" required placeholder="Option Text {{ $optionLetter }}">
                                    <input type="hidden" name="options[{{ $loop->index }}][option_letter]" value="{{ $optionLetter }}">
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="options[{{ $loop->index }}][is_correct]" value="0">
                                    <input class="form-check-input correct-option-checkbox" type="checkbox" id="is_correct_{{ $optionLetter }}" name="options[{{ $loop->index }}][is_correct]" value="1" {{ old('options.' . $loop->index . '.is_correct', $isCorrect) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_correct_{{ $optionLetter }}">Correct</label>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('superquizzes.show', ['business_unique_id' => $business_unique_id, 'superQuiz' => $unique_id]) }}" class="btn btn-secondary me-2"> <i class="fas fa-arrow-circle-left me-1"></i> Back to Quiz</a> {{-- Tombol Back tetap ada --}}
                        <button type="submit" class="btn btn-primary me-2"> <i class="fas fa-check me-1"></i> Update Question</button>
                        {{-- Tombol CANCEL DIHAPUS --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const correctOptionCheckboxes = document.querySelectorAll('.correct-option-checkbox');

            correctOptionCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        correctOptionCheckboxes.forEach(function(otherCheckbox) {
                            if (otherCheckbox !== checkbox) {
                                otherCheckbox.checked = false;
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection