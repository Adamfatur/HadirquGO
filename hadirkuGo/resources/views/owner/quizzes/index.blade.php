@extends('layout.owner')

@section('title', 'Manage Quizzes')
@section('page-title', 'Manage Quizzes')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm rounded" role="alert" style="background-color: #e6f7ec; border-color: #c3e8cd; color: #2d4b3a;">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
        <div class="card-header bg-primary text-white py-3 rounded-top" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem 0.75rem 0 0;">
            <h5 class="mb-0" style="font-size: 1.25rem;">
                <i class="fas fa-puzzle-piece me-2"></i>
                Quiz Collection
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('owner.quizzes.create', $business->business_unique_id) }}"
                   class="btn btn-success rounded-pill shadow-sm px-4" style="padding: 0.6rem 1.2rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                    <i class="fas fa-plus-circle me-2"></i> Create New Quiz
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                    <thead class="thead-light" style="background-color: #f8f9fa;">
                    <tr>
                        <th class="py-3 ps-3" style="padding-left: 1.2rem;">Quiz Title</th>
                        <th class="py-3">Unique ID</th>
                        <th class="text-center py-3" style="width: 180px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($quizzes as $quiz)
                        <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                            <td class="ps-3 align-middle" style="padding-left: 1.2rem;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-primary rounded-circle me-3" style="background-color: #e0f7fa; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-file-alt text-primary" style="color: #007bff;"></i>
                                    </div>
                                    <span class="fw-semibold" style="color: #333;">{{ $quiz->title }}</span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-info bg-opacity-25 text-info fs-6 rounded-pill" style="background-color: rgba(13, 110, 253, 0.15); color: #0d6efd; border-radius: 2rem; padding: 0.4em 0.8em;">
                                    {{ $quiz->unique_id }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('owner.quizzes.show', [$business->business_unique_id, $quiz->id]) }}"
                                       class="btn btn-outline-primary btn-sm rounded-pill px-3" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-eye me-1"></i> Details
                                    </a>
                                    <a href="{{ route('owner.quizzes.edit', [$business->business_unique_id, $quiz->id]) }}"
                                       class="btn btn-outline-warning btn-sm rounded-pill px-3" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('owner.quizzes.destroy', [$business->business_unique_id, $quiz->id]) }}"
                                          method="POST"
                                          class="delete-form" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                                onclick="return confirm('Are you sure you want to delete this quiz?')" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                <div class="alert alert-info d-inline-flex align-items-center shadow-sm mb-0 rounded" role="alert" style="background-color: #e3f2fd; border-color: #bbdefb; color: #0a589b; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div class="fw-semibold">No quizzes found. Create your first quiz!</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
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

        .table-responsive > .table > tbody > tr:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
            background-color: #f8fafa !important;
        }

        .btn-outline-primary.rounded-pill:hover,
        .btn-outline-warning.rounded-pill:hover,
        .btn-outline-danger.rounded-pill:hover {
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Button hover effects for create button
            $('.btn-success.rounded-pill.shadow-sm').hover(function() {
                $(this).css({'opacity': '0.9', 'transform': 'scale(1.03)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });

            // Table row hover effect
            $('.table-responsive > .table > tbody > tr').hover(function() {
                $(this).css({'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.05)', 'transform': 'translateY(-2px)', 'backgroundColor': '#f8fafa'});
            }, function() {
                $(this).css({'box-shadow': 'none', 'transform': 'translateY(0)', 'backgroundColor': 'white'});
            });

            // Action button hover effects
            $('.btn-outline-primary.rounded-pill, .btn-outline-warning.rounded-pill, .btn-outline-danger.rounded-pill').hover(function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1.05)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });
        });
    </script>
@endsection