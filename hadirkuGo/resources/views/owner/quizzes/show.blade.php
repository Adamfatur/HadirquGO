@extends('layout.owner')

@section('title', 'Quiz Detail')
@section('page-title', 'Quiz Detail')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm rounded alert-dismissible fade show" role="alert" style="background-color: #e6f7ec; border-color: #c3e8cd; color: #2d4b3a;">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm rounded alert-dismissible fade show" role="alert" style="background-color: #f8d7da; border-color: #f1caca; color: #842029;">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out; border: none;">
        <div class="card-header bg-primary text-white rounded-top" style="background-color: #007bff !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem; border-bottom: none;">
            <h3 class="card-title mb-0" style="font-size: 1.5rem;">
                <i class="fas fa-file-alt me-2"></i>{{ $quiz->title }}
            </h3>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <div class="d-flex align-items-center">
                <i class="fas fa-fingerprint text-muted me-2"></i>
                <span class="badge bg-info fs-6 rounded-pill" style="background-color: rgba(13, 110, 253, 0.15); color: #f6faff; border-radius: 2rem; padding: 0.4em 0.8em;">ID: {{ $quiz->unique_id }}</span>
            </div>
        </div>
    </div>

    <div class="questions-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0" style="font-size: 1.3rem; font-weight: 500;">
                <i class="fas fa-question-circle text-primary me-2"></i> Questions Management
            </h4>
            <a href="{{ route('owner.questions.create', [$business->business_unique_id, $quiz->id]) }}"
               class="btn btn-primary rounded-pill shadow-sm px-3" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                <i class="fas fa-plus-circle me-2"></i> Add Question
            </a>
        </div>

        @if($questions->count() > 0)
            <div class="table-responsive shadow-sm rounded" style="border-radius: 0.75rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05);">
                <table class="table table-hover table-borderless align-middle mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                    <thead class="thead-light" style="background-color: #f8f9fa;">
                    <tr>
                        <th style="width: 70%; padding-left: 1.2rem;">Question Text</th>
                        <th style="width: 30%" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($questions as $index => $question)
                        <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                            <td style="padding-left: 1.2rem;">
                                <div class="d-flex align-items-center" style="padding: 0.75rem 0;">
                                    <div class="me-3 p-2 bg-light rounded-circle text-secondary text-center"
                                         style="width: 35px; height: 35px; background-color: #f8f9fa !important; color: #6c757d;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <span style="color: #333;">{{ $question->question_text }}</span>
                                </div>
                            </td>
                            <td class="text-center" style="padding: 0.75rem 0;">
                                <a href="{{ route('owner.questions.edit', [$business->business_unique_id, $quiz->id, $question->id]) }}"
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('owner.pertanyaan.destroy', [$business->business_unique_id, $quiz->id, $question->id]) }}"
                                      method="POST"
                                      style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="debug" value="1">
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                            onclick="return confirm('Are you sure want to delete this question?');" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mt-4 rounded shadow-sm" style="border-radius: 0.5rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05); background-color: #e3f2fd; border-color: #bbdefb; color: #0a589b;">
                <i class="fas fa-info-circle me-2"></i>
                No questions found. You can add a new question by clicking the button above.
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('owner.quizzes.index', $business->business_unique_id) }}" class="btn btn-outline-secondary rounded-pill shadow-sm" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
            <i class="fas fa-arrow-left me-2"></i> Back to Quizzes List
        </a>
    </div>
@endsection

@section('styles')
    <style>
        /* General Card Hover Effect */
        .card.shadow-sm.mb-4.rounded {
            transition: box-shadow 0.3s ease-in-out;
        }
        .card.shadow-sm.mb-4.rounded:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-primary.rounded-pill.shadow-sm:hover,
        .btn-outline-secondary.rounded-pill.shadow-sm:hover{
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-outline-primary.rounded-pill.px-3.me-2:hover,
        .btn-outline-danger.rounded-pill.px-3:hover{
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        /* Table Row Hover Effect */
        .table-responsive > .table > tbody > tr:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
            background-color: #f8fafa !important; /* or another light background */
        }
        .table-responsive > .table > tbody > tr {
            transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Button hover effects
            $('.btn-primary.rounded-pill.shadow-sm, .btn-outline-secondary.rounded-pill.shadow-sm').hover(function() {
                $(this).css({'opacity': '0.9', 'transform': 'scale(1.03)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });
            $('.btn-outline-primary.rounded-pill.px-3.me-2, .btn-outline-danger.rounded-pill.px-3').hover(function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1.05)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });


            // Table row hover effect
            $('.table-responsive > .table > tbody > tr').hover(function() {
                $(this).css({'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.05)', 'transform': 'translateY(-2px)', 'backgroundColor': '#f8fafa'});
            }, function() {
                $(this).css({'box-shadow': 'none', 'transform': 'translateY(0)', 'backgroundColor': 'white'});
            });
        });
    </script>
@endsection