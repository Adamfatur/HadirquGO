@extends('layout.student')

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg animate__animated animate__fadeIn" style="border-radius: 20px; max-width: 600px; width: 100%; border: none;">
            <div class="card-body p-4">
                <!-- Icon dengan Animasi -->
                <div class="text-center mb-4">
                    <i class="fas fa-gift text-primary" style="font-size: 4rem; animation: bounce 2s infinite;"></i>
                </div>

                <!-- Judul dengan Efek Typing -->
                <h1 class="text-primary fw-bold text-center mb-3" style="font-size: 2rem;" id="typing-title"></h1>

                <!-- Pesan Error atau Hadiah -->
                <div class="text-center mb-4">
                    @if(isset($error))
                        <!-- Tampilkan pesan error dari controller -->
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            {{ $error }}
                        </div>
                    @elseif(isset($selectedReward))
                        <!-- Tampilkan pesan sukses jika hadiah diberikan -->
                        <p class="text-muted fs-6" style="line-height: 1.5;">
                            <strong>{{ Auth::user()->name }}</strong>, you have received a reward:
                            <strong class="text-success">{{ $selectedReward->name }}</strong>.
                        </p>
                        <div class="card mt-3 shadow-sm animate__animated animate__zoomIn">
                            <div class="card-body text-center">
                                <i class="fas fa-gift text-warning mb-2" style="font-size: 2rem;"></i>
                                <h5 class="card-title">{{ $selectedReward->name }}</h5>
                                <p class="card-text">{{ $selectedReward->description }}</p>
                            </div>
                        </div>
                    @else
                        <!-- Tampilkan pesan motivasi jika belum mendapatkan hadiah -->
                        <p class="text-muted fs-6" style="line-height: 1.5;">
                            <strong>{{ Auth::user()->name }}</strong>, keep up the good work! You're doing great.
                        </p>
                    @endif
                </div>

                <!-- Tombol Lihat Semua Hadiah -->
                <div class="text-center mb-4">
                    <button class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#allRewardsModal">
                        <i class="fas fa-list me-2"></i> {{ __('View All Rewards') }}
                    </button>
                </div>

                <!-- Testimoni Section -->
                @if($testimony)
                    <!-- Tampilkan testimoni user jika sudah ada -->
                    <div class="testimonial-section mt-4">
                        <h5 class="fw-bold mb-3 text-center">{{ __('Your Testimonial') }}</h5>
                        <div class="card testimonial-card animate__animated animate__fadeInUp">
                            <div class="card-body">
                                <!-- Header Testimoni (Avatar + Nama) -->
                                <div class="d-flex align-items-center mb-3">
                                    <!-- Avatar -->
                                    <div class="avatar me-3">
                                        <img src="{{ $testimony->user->avatar ?? 'https://via.placeholder.com/50' }}"
                                             alt="Avatar"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <!-- Nama User -->
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $testimony->user->name }}</h6>
                                        <small class="text-muted">{{ $testimony->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>

                                <!-- Rating Bintang -->
                                <div class="rating mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testimony->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                </div>

                                <!-- Teks Testimoni -->
                                <p class="card-text">{{ $testimony->testimony }}</p>

                                <!-- Tombol {{ __('Edit') }} -->
                                <div class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" onclick="show{{ __('Edit') }}Form()">
                                        <i class="fas fa-edit me-1"></i> {{ __('Edit') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Form {{ __('Edit') }} Testimoni (Awalnya Tersembunyi) -->
                        <div id="editTestimonialForm" class="mt-4" style="display: none;">
                            <h5 class="fw-bold mb-3 text-center">{{ __('Edit') }} {{ __('Your Testimonial') }}</h5>
                            <form action="{{ route('student.testimonies.update', $testimony->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <!-- Textarea untuk Testimoni -->
                                <div class="form-group mb-4">
                                    <label for="editTestimony" class="form-label fw-bold">{{ __('Your Testimonial') }}</label>
                                    <textarea name="testimony" id="editTestimony" class="form-control" rows="4" required>{{ $testimony->testimony }}</textarea>
                                </div>

                                <!-- Rating Bintang -->
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">Rating</label>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star edit-rating-star" data-value="{{ $i }}" style="font-size: 2rem; cursor: pointer; color: #ddd;"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="editRating" value="{{ $testimony->rating }}" required>
                                </div>

                                <!-- Tombol {{ __('Submit') }} dan Cancel -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="hide{{ __('Edit') }}Form()">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Form untuk menambahkan testimoni baru -->
                    <div class="testimonial-section mt-4">
                        <h5 class="fw-bold mb-3 text-center">Add {{ __('Your Testimonial') }}</h5>
                        <form action="{{ route('student.testimonies.store') }}" method="POST">
                            @csrf
                            <!-- Textarea untuk Testimoni -->
                            <div class="form-group mb-4">
                                <label for="testimony" class="form-label fw-bold">{{ __('Your Testimonial') }}</label>
                                <textarea name="testimony" id="testimony" class="form-control" rows="4" placeholder="Write your testimonial here..." required></textarea>
                            </div>

                            <!-- Rating Bintang -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">Rating</label>
                                <div class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="far fa-star rating-star" data-value="{{ $i }}" style="font-size: 2rem; cursor: pointer; color: #ddd;"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating" required>
                            </div>

                            <!-- Tombol {{ __('Submit') }} -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i> {{ __('Submit Testimonial') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Button Kembali ke Dashboard -->
                <div class="text-center mt-4">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Menampilkan Semua Hadiah -->
    <div class="modal fade" id="allRewardsModal" tabindex="-1" aria-labelledby="allRewardsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allRewardsModalLabel">{{ __('All Available Rewards') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($rewards as $reward)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 shadow-sm animate__animated animate__fadeIn">
                                    <div class="card-body text-center">
                                        <i class="fas fa-gift text-secondary mb-2" style="font-size: 2rem;"></i>
                                        <h5 class="card-title">{{ $reward->name }}</h5>
                                        <p class="card-text">{{ $reward->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Rating Bintang dan Form {{ __('Edit') }} -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Star Rating for Add Testimonial Form
            const stars = document.querySelectorAll('.rating-star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;

                    // Update star display
                    stars.forEach((s, index) => {
                        if (index < value) {
                            s.classList.remove('far');
                            s.classList.add('fas', 'text-warning');
                        } else {
                            s.classList.remove('fas', 'text-warning');
                            s.classList.add('far');
                        }
                    });
                });
            });

            // Star Rating for Edit Testimonial Form
            const editStars = document.querySelectorAll('.edit-rating-star');
            const editRatingInput = document.getElementById('editRating');

            editStars.forEach(star => {
                star.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    editRatingInput.value = value;

                    // Update star display
                    editStars.forEach((s, index) => {
                        if (index < value) {
                            s.classList.remove('far');
                            s.classList.add('fas', 'text-warning');
                        } else {
                            s.classList.remove('fas', 'text-warning');
                            s.classList.add('far');
                        }
                    });
                });
            });

            // Typing Effect for Title
            const title = @if(isset($selectedReward)) "Congratulations!" @else "Keep up the good work!" @endif;
            let index = 0;
            const typingTitle = document.getElementById('typing-title');

            function typeWriter() {
                if (index < title.length) {
                    typingTitle.innerHTML += title.charAt(index);
                    index++;
                    setTimeout(typeWriter, 100);
                }
            }

            typeWriter();
        });

        // Function to Show Edit Form
        function show{{ __('Edit') }}Form() {
            document.getElementById('editTestimonialForm').style.display = 'block';
        }

        // Function to Hide Edit Form
        function hide{{ __('Edit') }}Form() {
            document.getElementById('editTestimonialForm').style.display = 'none';
        }
    </script>

    <!-- Custom CSS for Testimonial Card -->
    <style>
        /* Bounce Animation for Icon */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        .testimonial-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .testimonial-card .card-body {
            padding: 20px;
        }

        .testimonial-card .avatar img {
            border: 2px solid #ddd;
        }

        .testimonial-card .rating {
            color: #ffc107; /* Yellow color for stars */
        }

        .testimonial-card .card-text {
            font-size: 1rem;
            color: #333;
        }

        /* Hover Effect for Cards */
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .card {
                max-width: 100%;
            }

            .modal-dialog {
                margin: 1rem;
            }

            .btn-lg {
                width: 100%;
            }
        }
    </style>
@endsection