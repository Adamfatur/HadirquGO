@extends('layout.student') {{-- Layout diubah menjadi student --}}

@section('title', 'Detail Super Quiz')
@section('page-title', 'Detail Super Quiz')

@section('content')
    <div class="container py-5">
        <div class="card border-0 shadow-lg rounded" style="border-radius: 0.75rem;">
            <div class="card-header bg-primary text-white py-3 rounded-top" style="border-radius: 0.75rem 0.75rem 0 0;">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Detail Super Quiz
                </h5>
            </div>
            <div class="card-body p-4">
                <h2 class="card-title fw-bold text-primary mb-4" style="font-size: 1.75rem;">{{ $superQuiz->title }}</h2>

                <div class="mb-3">
                    <p class="card-text" style="font-size: 1rem;">
                        Selamat datang di Super Quiz <span class="fw-bold">{{ $superQuiz->title }}</span>!
                        Quiz ini terdiri dari 10 pertanyaan pilihan ganda. Setiap pertanyaan memiliki batas waktu 10 detik.
                        <br><br>
                        <b>Aturan Penting:</b>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-double text-success me-2"></i> Quiz hanya dapat diambil <span class="fw-bold">sekali sehari</span>.</li>
                        <li><i class="fas fa-stopwatch text-warning me-2"></i> Setiap soal memiliki batas waktu <span class="fw-bold">10 detik</span>.</li>
                        <li><i class="fas fa-star text-warning me-2"></i> Jawaban benar bernilai <span class="fw-bold">5 poin</span>.</li>
                        <li><i class="fas fa-times-circle text-danger me-2"></i> Jawaban salah akan <span class="fw-bold">menghanguskan poin</span> yang sudah dikumpulkan.</li>
                        <li><i class="fas fa-trophy text-success me-2"></i> Selesaikan semua soal dengan benar untuk bonus poin dan total <span class="fw-bold">100 poin</span>!</li>
                        <li><i class="fas fa-flag-checkered text-warning me-2"></i> Anda bisa <span class="fw-bold">menyerah</span> dan mendapatkan poin yang sudah dikumpulkan (jika ada).</li>
                    </ul>
                    <span class="text-muted fst-italic">Hadiah Utama: 100 Tesla Poin</span>
                    </p>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('student.superquiz.index') }}" class="btn btn-secondary me-2 rounded-pill px-4"> {{-- Route names diperbarui menjadi student.superquiz.* --}}
                        <i class="fas fa-arrow-circle-left me-1"></i> Kembali ke Daftar Quiz
                    </a>
                    <a href="{{ route('student.superquiz.question', [$superQuiz->unique_id, 1]) }}" class="btn btn-danger rounded-pill px-4"> {{-- Route names diperbarui menjadi student.superquiz.* --}}
                        <i class="fas fa-rocket me-1"></i> Mulai Quiz
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection