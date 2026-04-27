@extends('layout.owner')

@section('title', 'Manage Super Quizzes')
@section('page-title', 'Manage Super Quizzes')

@section('content')

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm rounded border-0" role="alert" style="background-color: #f0fdf4; color: #059669;">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg mb-4 rounded-xl" style="border-radius: 1rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: box-shadow 0.3s ease-in-out;">
        <div class="card-header bg-primary text-white py-4 rounded-top-xl" style="background: #0ea5e9; border-radius: 1rem 1rem 0 0;  padding-top: 1.5rem; padding-bottom: 1.5rem;">
            <h5 class="mb-0 fw-semibold" style="font-size: 1.35rem;">
                <i class="fas fa-puzzle-piece me-2"></i>
                Super Quiz Collection
            </h5>
        </div>
        <div class="card-body p-5">
            <div class="d-flex justify-content-end mb-3"> {{-- UBAH mb-5 menjadi mb-3 DI SINI --}}
                <a href="{{ route('superquizzes.create', $business->business_unique_id) }}"
                   class="btn btn-success rounded-pill shadow-sm px-5 fw-semibold" style="padding-top: 0.75rem; padding-bottom: 0.75rem; font-size: 1rem; transition: background-color 0.3s ease-in-out;">
                    <i class="fas fa-plus-circle me-2"></i> Create New Quiz
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0" style="border-collapse: separate; border-spacing: 0 12px;">
                    <thead class="thead-light" style="background-color: #f9fafb;">
                    <tr>
                        <th class="py-3 ps-4 fw-semibold text-secondary" style="padding-left: 1.5rem; color: #64748b; font-size: 0.9rem;">Quiz Title</th>
                        <th class="py-3 fw-semibold text-secondary" style="color: #64748b; font-size: 0.9rem;">Unique ID</th>
                        <th class="text-center py-3 fw-semibold text-secondary" style="width: 180px; color: #64748b; font-size: 0.9rem;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($superQuizzes as $quiz)
                        <tr style="background-color: white; border-radius: 0.75rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.04); transition: box-shadow 0.3s ease-in-out, transform 0.2s ease-in-out;">
                            <td class="ps-4 align-middle" style="padding-left: 1.5rem;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-primary rounded-circle me-4" style="background-color: #e0f2fe; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem;">
                                        <i class="fas fa-file-alt text-primary" style="color: #1e90ff; font-size: 1.2rem;"></i>
                                    </div>
                                    <span class="fw-medium text-gray-700" style="color: #4a5568; font-size: 1rem;">{{ $quiz->title }}</span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-info bg-opacity-25 text-info fs-6 rounded-pill" style="background-color: #e0f7fa; color: #06b6d4; border-radius: 2rem; padding: 0.5em 1em; font-size: 0.85rem;">
                                    {{ $quiz->unique_id }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <div class="d-inline-flex gap-3">
                                    <a href="{{ route('superquizzes.show', [$business->business_unique_id, $quiz->unique_id]) }}"
                                       class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-semibold" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-width: 1.5px; transition: background-color 0.3s ease-in-out; font-weight: 500;">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('superquizzes.edit', [$business->business_unique_id, $quiz->unique_id]) }}"
                                       class="btn btn-outline-warning btn-sm rounded-pill px-4 fw-semibold" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-width: 1.5px; transition: background-color 0.3s ease-in-out; font-weight: 500;">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('superquizzes.destroy', [$business->business_unique_id, $quiz->unique_id]) }}"
                                          method="POST"
                                          class="delete-form" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-semibold"
                                                onclick="return confirm('Are you sure you want to delete this quiz?')" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-width: 1.5px; transition: background-color 0.3s ease-in-out; font-weight: 500;">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="alert alert-info d-inline-flex align-items-center shadow-sm mb-0 rounded-xl border-0" role="alert" style="background-color: #e0f7fa; color: #087ea4; border-radius: 1rem; padding: 1.25rem 2rem;">
                                    <i class="fas fa-info-circle fa-2x me-4"></i>
                                    <div class="fw-semibold" style="font-size: 1.1rem;">No quizzes found. Click "Create New Quiz" to add your first quiz!</div>
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
        .card.shadow-lg.mb-4.rounded-xl:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .btn-success.rounded-pill.shadow-sm:hover {
            background-color: #15bb70;
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .table-responsive > .table > tbody > tr:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.06);
            transform: translateY(-3px);
            background-color: #f9fafb !important;
        }

        .btn-outline-primary.rounded-pill:hover,
        .btn-outline-warning.rounded-pill:hover,
        .btn-outline-danger.rounded-pill:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0);
            opacity: 1;
            transform: scale(1.08);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Button hover effects for create button
            $('.btn-success.rounded-pill.shadow-sm').hover(function() {
                $(this).css({'background-color': '#15bb70', 'opacity': '0.95', 'transform': 'scale(1.05)', 'box-shadow': '0 6px 12px rgba(0, 0, 0, 0.15)'});
            }, function() {
                $(this).css({'background-color': '', 'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });

            // Table row hover effect
            $('.table-responsive > .table > tbody > tr').hover(function() {
                $(this).css({'box-shadow': '0 6px 12px rgba(0, 0, 0, 0.06)', 'transform': 'translateY(-3px)', 'backgroundColor': '#f9fafb'});
            }, function() {
                $(this).css({'box-shadow': 'none', 'transform': 'translateY(0)', 'backgroundColor': 'white'});
            });

            // Action button hover effects
            $('.btn-outline-primary.rounded-pill, .btn-outline-warning.rounded-pill, .btn-outline-danger.rounded-pill').hover(function() {
                $(this).css({'backgroundColor': 'rgba(0, 0, 0, 0.05)', 'borderColor': 'rgba(0, 0, 0, 0)', 'opacity': '1', 'transform': 'scale(1.08)', 'box-shadow': '0 5px 10px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'backgroundColor': '', 'borderColor': '', 'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });
        });
    </script>
@endsection