@extends('layout.lecturer')

@section('title', 'Super Quiz Collection')
@section('page-title', 'Super Quiz Collection')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer"/>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm rounded" role="alert"
             style="background-color: #e6f7ec; border-color: #c3e8cd; color: #2d4b3a;">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm rounded" role="alert">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg mb-4 rounded"
         style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
        <div class="card-header bg-primary text-white py-3 rounded-top"
             style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 0.75rem 0.75rem 0 0;">
            <h5 class="mb-0" style="font-size: 1.25rem;">
                <i class="fas fa-puzzle-piece me-2"></i>
                Super Quiz Collection
            </h5>
        </div>
        <div class="card-body p-4">

            {{-- Language Selection Tautan Teks DIHAPUS --}}
            {{-- Dropdown Bahasa DIHAPUS --}}
            {{-- Tombol Bahasa DIHAPUS --}}

            {{-- Super Quiz Rules Section - English Version (Default, Hanya ini yang ditampilkan) --}}
            <div id="rules-english" class="mb-4 p-3 rounded rules-block" style="background-color: #f0faff; border: 1px solid #b3e0ff; border-radius: 0.5rem;">
                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> Understand Super Quiz Rules
                </h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-square text-secondary me-2"></i>
                        <span class="fw-semibold">Daily Challenge (1x Daily):</span> Super Quiz can only be taken <span class="fw-bold">once per day</span>. Chance resets every day at 00:00 WIB. <span class="fst-italic">Use your chance wisely every day!</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-star text-warning me-2"></i>
                        <span class="fw-semibold"><span class="fw-bold">+5 Points Per Correct Answer, Wrong Answer = 0 Points!</span></span> Each correct answer adds 5 points. <span class="fst-italic fw-bold">Be careful! One wrong answer will reset all accumulated points to 0.</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-stopwatch text-danger me-2"></i>
                        <span class="fw-semibold">Time Limit (10 Seconds/Question):</span> Each question has a <span class="fw-bold">10-second time limit</span>. <span class="fst-italic">Answer quickly and accurately!</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-list-ol text-primary me-2"></i>
                        <span class="fw-semibold">Total 10 Questions:</span> Super Quiz consists of <span class="fw-bold">10 multiple-choice questions</span>. <span class="fst-italic">Complete all for bonus points!</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-flag-checkered text-warning me-2"></i>
                        <span class="fw-semibold">Surrender Feature: Secure Your Points!</span> Before proceeding to the next question, you can <span class="fw-bold">choose to 'Surrender'</span>. <span class="fst-italic">By surrendering, your accumulated points will be safe, although not the maximum 100 points.</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-gift text-success me-2"></i>
                        <span class="fw-semibold">Perfect Score Bonus (100 Points)!</span> If you successfully answer <span class="fw-bold">all 10 questions correctly</span>, you will get a <span class="fw-bold">bonus points up to a total of 100 points</span>! <span class="fst-italic">Achieve the highest score!</span>
                    </li>
                </ul>
            </div>

            {{-- Super Quiz Rules Section - Indonesian Version (DIHAPUS) --}}


            {{-- Create New Quiz Button DELETED for lecturer --}}

            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0"
                       style="border-collapse: separate; border-spacing: 0 8px;">
                    <thead class="thead-light" style="background-color: #f8f9fa;">
                    <tr>
                        <th class="py-3 ps-3" style="padding-left: 1.2rem;">Quiz Title</th>
                        <th class="text-center py-3" style="width: 180px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($superQuizzes as $quiz)
                        <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                            <td class="ps-3 align-middle" style="padding-left: 1.2rem;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light-primary rounded-circle me-3"
                                         style="background-color: #e0f7fa; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-puzzle-piece text-primary" style="color: #007bff;"></i>
                                    </div>
                                    <span class="fw-semibold" style="color: #333;">{{ $quiz->title }}</span>
                                </div>
                            </td>
                            <td class="align-middle text-center">
                                <div class="d-inline-flex gap-2">
                                    @if($quiz->hasTakenToday)
                                        <a href="{{ route('lecturer.superquiz.viewResult', $quiz->unique_id) }}"
                                           class="btn btn-outline-success btn-sm rounded-pill px-3">
                                            <i class="fas fa-chart-bar me-1"></i> View Result
                                        </a>
                                    @else
                                        <a href="{{ route('lecturer.superquiz.show', $quiz->unique_id) }}"
                                           class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="fas fa-rocket me-1"></i> Take Quiz
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                <div class="alert alert-info d-inline-flex align-items-center shadow-sm mb-0 rounded"
                                     role="alert"
                                     style="background-color: #e3f2fd; border-color: #bbdefb; color: #0a589b; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div class="fw-semibold">No active Super Quizzes available.</div>
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
        .btn-outline-danger.rounded-pill:hover,
        .btn-outline-success.rounded-pill:hover,
        .btn-outline-secondary.rounded-pill:hover { /* Tambahkan hover effect untuk secondary button */
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Style untuk Tautan Teks DIHAPUS */
        /* Style untuk Dropdown DIHAPUS */


    </style>
@endsection

@section('scripts')
    {{-- Javascript untuk language switch DIHAPUS --}}
    <script>
        $(document).ready(function () {
            console.log("Document ready!"); // Konfirmasi: dokumen sudah siap

            // Hover effect untuk tombol-tombol (tetap ada karena masih ada tombol lain di halaman)
            $('.btn-success.rounded-pill.shadow-sm, .btn-outline-primary.rounded-pill, .btn-outline-warning.rounded-pill, .btn-outline-danger.rounded-pill, .btn-outline-success.rounded-pill, .btn-outline-secondary.rounded-pill').hover(function () {
                $(this).css({'opacity': '1', 'transform': 'scale(1.05)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function () {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });

            // Hover effect untuk baris tabel (tetap ada)
            $('.table-responsive > .table > tbody > tr').hover(function () {
                $(this).css({'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.05)', 'transform': 'translateY(-2px)', 'backgroundColor': '#f8fafa'});
            }, function () {
                $(this).css({'box-shadow': 'none', 'transform': 'translateY(0)', 'backgroundColor': 'white'});
            });


        });
    </script>
@endsection