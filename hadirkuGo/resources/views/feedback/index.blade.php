@php
    $layout = 'layout.app';
    if (auth()->user()->hasRole('Lecturer')) {
        $layout = 'layout.lecturer';
    } elseif (auth()->user()->hasRole('Student')) {
        $layout = 'layout.student';
    } elseif (auth()->user()->hasRole('Owner')) {
        $layout = 'layout.owner';
    } elseif (auth()->user()->hasRole('Admin')) {
        $layout = 'layout.admin';
    }
@endphp

@extends($layout)

@section('title', 'Feedback')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-3 mt-md-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <!-- Header Section -->
            <div class="mb-4 text-center text-md-start">
                <h3 class="fw-bold text-white fs-2 mb-2">Feedback Sistem</h3>
                <p class="text-white" style="opacity: 0.8;">Bantu kami meningkatkan kualitas layanan dengan masukan Anda.</p>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger shadow-sm border-0" style="border-radius: 12px;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Input Form Card -->
            <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Tulis Masukan Anda</label>
                            <textarea name="content" class="form-control feedback-input" rows="3" placeholder="Ide, saran, atau keluhan? Sampaikan di sini..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto px-4 fw-bold btn-submit">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Feedback Feed Header & Filters -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                <h5 class="fw-bold text-white mb-3 mb-md-0">Masukan dari Komunitas</h5>
                
                <div class="nav nav-pills" style="background-color: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 4px;">
                    <a class="nav-link rounded-pill {{ (request('sort', 'latest') == 'latest') ? 'active bg-white text-primary fw-bold shadow-sm' : 'text-white' }}" 
                       href="{{ route('feedback.index', ['sort' => 'latest']) }}" 
                       style="padding: 6px 16px; font-size: 0.9rem;">
                        <i class="fas fa-clock me-1"></i> Terbaru
                    </a>
                    <a class="nav-link rounded-pill {{ (request('sort') == 'popular') ? 'active bg-white text-primary fw-bold shadow-sm' : 'text-white' }}" 
                       href="{{ route('feedback.index', ['sort' => 'popular']) }}" 
                       style="padding: 6px 16px; font-size: 0.9rem;">
                        <i class="fas fa-fire me-1"></i> Terpopuler
                    </a>
                </div>
            </div>
            
            <!-- Feedback Items -->
            <div class="feedback-feed">
                @forelse($feedbacks as $fb)
                    @php
                        $userRankData = \App\Models\UserLeaderboard::where('user_id', $fb->user_id)
                            ->where('category', 'top_points')
                            ->first();
                        $rank = $userRankData ? $userRankData->current_rank : 999;
                        $title = $userRankData ? $userRankData->title : 'Pioneer';

                        $rankClass = '';
                        $glowClass = '';
                        $titleBadgeStyle = '';
                        $iconColor = 'text-warning';
                        
                        if ($rank == 1) {
                            $rankClass = 'border-warning shadow-lg';
                            $glowClass = 'border: 3px solid #fbbf24; box-shadow: 0 0 15px #fbbf24, inset 0 0 10px #fbbf24; padding: 2px; background: linear-gradient(45deg, #fef3c7, #f59e0b);';
                            $titleBadgeStyle = 'background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; border: 1px solid #fbbf24; box-shadow: 0 2px 4px rgba(251,191,36,0.3); font-weight: 700;';
                            $iconColor = 'text-warning';
                        } elseif ($rank == 2) {
                            $rankClass = 'border-secondary shadow-lg';
                            $glowClass = 'border: 3px solid #9ca3af; box-shadow: 0 0 12px #9ca3af; padding: 2px; background: linear-gradient(45deg, #f3f4f6, #9ca3af);';
                            $titleBadgeStyle = 'background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #4b5563; border: 1px solid #9ca3af; font-weight: 700;';
                            $iconColor = 'text-secondary';
                        } elseif ($rank == 3) {
                            $rankClass = 'shadow-lg';
                            $glowClass = 'border: 3px solid #cd7f32; box-shadow: 0 0 10px #cd7f32; padding: 2px; background: linear-gradient(45deg, #fdf5e6, #cd7f32);';
                            $titleBadgeStyle = 'background: linear-gradient(135deg, #ffedd5, #fcd34d); color: #b45309; border: 1px solid #d97706; font-weight: 700;';
                            $iconColor = 'text-warning';
                        } elseif ($rank <= 5) {
                            $rankClass = 'border-danger shadow-sm';
                            $glowClass = 'border: 2px solid #ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.5); padding: 2px;';
                            $titleBadgeStyle = 'background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5;';
                            $iconColor = 'text-danger';
                        } elseif ($rank <= 10) {
                            $rankClass = 'border-success shadow-sm';
                            $glowClass = 'border: 2px solid #10b981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.4); padding: 2px;';
                            $titleBadgeStyle = 'background: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;';
                            $iconColor = 'text-success';
                        } else {
                            $rankClass = 'border-primary shadow-sm';
                            $glowClass = 'border: 2px solid #3b82f6; box-shadow: 0 0 4px rgba(59, 130, 246, 0.3); padding: 2px;';
                            $titleBadgeStyle = 'background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd;';
                            $iconColor = 'text-primary';
                        }
                    @endphp

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4 position-relative">
                            
                            <!-- Top Right Actions & Badges -->
                            <div class="position-absolute top-0 end-0 p-2 p-md-3 d-flex flex-column flex-md-row align-items-end align-items-md-center gap-1 gap-md-2" style="z-index: 10;">
                                @if($fb->likes_count >= 5)
                                    <span class="badge bg-danger rounded-pill shadow-sm py-2 px-3" style="font-size: 0.75rem;">
                                        <i class="fas fa-fire me-1"></i> Hot Issue
                                    </span>
                                @endif
                                @if($fb->status == 'done')
                                    <span class="badge bg-success rounded-pill shadow-sm py-2 px-3" style="font-size: 0.75rem;">
                                        <i class="fas fa-check-double me-1"></i> Selesai
                                    </span>
                                @endif
                                
                                <!-- Edit & Delete Actions (Owner Only) -->
                                @if(auth()->id() == $fb->user_id)
                                    <div class="dropdown ms-1">
                                        <button class="btn btn-sm btn-link text-secondary p-0 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none;">
                                            <i class="fas fa-ellipsis-v fa-lg"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius: 10px;">
                                            <!-- Edit (Only if within 1 hour) -->
                                            @php $minutesPassed = $fb->created_at->diffInMinutes(now()); @endphp
                                            @if($minutesPassed < 60)
                                                <li>
                                                    <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $fb->id }}">
                                                        <i class="fas fa-edit me-2"></i> Edit
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <span class="dropdown-item text-muted" data-bs-toggle="tooltip" title="Waktu edit habis (maks. 1 jam)">
                                                        <i class="fas fa-lock me-2"></i> Edit Terkunci
                                                    </span>
                                                </li>
                                            @endif
                                            
                                            <!-- Delete -->
                                            <li>
                                                <form action="{{ route('feedback.destroy', $fb) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus masukan ini secara permanen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- User Header -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center pe-0 pe-md-5" style="width: 100%;">
                                    <div class="position-relative me-3 flex-shrink-0">
                                        @if($fb->user && $fb->user->avatar)
                                            <img src="{{ $fb->user->avatar }}" 
                                                 alt="{{ $fb->user->name }}" class="rounded-circle {{ $rankClass }}" 
                                                 style="width: 45px; height: 45px; @media (min-width: 768px) { width: 55px; height: 55px; } object-fit: cover; {{ $glowClass }}">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center fw-bold text-white rounded-circle {{ $rankClass }}" 
                                                 style="width: 45px; height: 45px; @media (min-width: 768px) { width: 55px; height: 55px; } font-size: 1.2rem; background-color: #1e3a8a; {{ $glowClass }}">
                                                {{ strtoupper(substr($fb->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        
                                        @if($rank <= 3)
                                            <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $iconColor }}" style="font-size: 1.2rem; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3)); transform: translate(-50%, -40%) !important; z-index: 10;"></i>
                                        @endif
                                    </div>
                                    <div class="user-info-container"> <!-- Extra padding to ensure name doesn't hit the dropdown -->
                                        <div class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" class="fs-6 fs-md-5">
                                            {{ $fb->user->name }}
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center mt-1 gap-2">
                                            @if($title)
                                                <div class="small px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.7rem; display: inline-flex; align-items: center; {{ $titleBadgeStyle }}">
                                                    <i class="fas fa-award me-1" style="font-size: 0.75rem;"></i> {{ $title }}
                                                </div>
                                            @endif
                                            @if($rank < 999)
                                                <div class="small px-2 py-1 rounded-pill shadow-sm bg-light text-secondary border" style="font-size: 0.7rem;">
                                                    Rank #{{ number_format($rank) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-muted d-flex align-items-center flex-wrap mt-1" style="font-size: 0.8rem;">
                                            <i class="far fa-clock me-1"></i> {{ $fb->created_at->diffForHumans() }} 
                                            @if($fb->updated_at > $fb->created_at) 
                                                <span class="mx-1">&bull;</span> <span class="text-primary fw-semibold" style="font-size: 0.75rem;"><i class="fas fa-pencil-alt me-1"></i>Edit: {{ $fb->updated_at->diffForHumans() }}</span> 
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Feedback Content -->
                            <div class="text-dark mb-3" class="text-dark mb-3 mt-2 feedback-content">
                                {!! nl2br(e($fb->content)) !!}
                            </div>

                            <!-- Admin Note -->
                            @if($fb->admin_note)
                                <div class="mt-3 p-3 position-relative" style="background-color: #f1f5f9; border-radius: 12px; font-size: 0.95rem; border-left: 4px solid #3b82f6;">
                                    <span class="fw-bold text-primary d-block mb-1"><i class="fas fa-user-shield me-1"></i> Tanggapan Admin:</span>
                                    <span class="text-dark">{{ $fb->admin_note }}</span>
                                </div>
                            @endif

                            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                <!-- Like Button (AJAX) -->
                                @php $isLiked = $fb->likes->where('user_id', auth()->id())->first(); @endphp
                                <button type="button" class="btn btn-link p-0 text-decoration-none d-flex align-items-center {{ $isLiked ? 'text-danger' : 'text-secondary' }} action-btn like-btn" data-id="{{ $fb->id }}">
                                    <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart me-2 like-icon" style="font-size: 1.2rem;"></i>
                                    <span class="fw-bold like-count" style="font-size: 0.95rem;">{{ $fb->likes_count }} Dukungan</span>
                                </button>
                                
                                <div></div> <!-- Spacer for flex-between since menu moved -->
                            </div>

                            <!-- Edit Modal for this Feedback (Moved outside the dropdown) -->
                            @if(auth()->id() == $fb->user_id && isset($minutesPassed) && $minutesPassed < 60)
                            <div class="modal fade" id="editModal{{ $fb->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                                    <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Masukan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i> Anda memiliki waktu {{ 60 - $minutesPassed }} menit lagi untuk mengedit.</p>
                                            <form action="{{ route('feedback.update', $fb) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <textarea name="content" class="form-control feedback-input" rows="4" required>{{ $fb->content }}</textarea>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                                                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <div class="card-body p-5 text-center">
                            <i class="fas fa-comment-slash fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <p class="text-muted mb-0 fw-semibold">Belum ada masukan di kategori ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>


<style>
    body {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
        min-height: 100vh;
    }

    .avatar-responsive {
        width: 45px;
        height: 45px;
        font-size: 1rem;
    }
    
    .user-info-container {
        padding-right: 40px;
    }
    @media (min-width: 768px) {
        .user-info-container {
            padding-right: 60px;
        }
    }
    .nav-pills {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    @media (min-width: 768px) {
        .nav-pills {
            width: auto;
        }
    }
    .feedback-content {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    @media (min-width: 768px) {
        .avatar-responsive {
            width: 55px;
            height: 55px;
            font-size: 1.2rem;
        }
        
    .user-info-container {
        padding-right: 40px;
    }
    @media (min-width: 768px) {
        .user-info-container {
            padding-right: 60px;
        }
    }
    .nav-pills {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    @media (min-width: 768px) {
        .nav-pills {
            width: auto;
        }
    }
    .feedback-content {
            font-size: 1.05rem;
        }
    }

    .feedback-input {
        border: 2px solid #eef2f6;
        border-radius: 12px !important;
        padding: 15px;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }
    .feedback-input:focus {
        border-color: #3b82f6;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .btn-submit {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        border: none;
        border-radius: 12px;
        padding: 10px 24px;
        transition: transform 0.2s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .card {
        background-color: #ffffff;
    }
    .action-btn {
        transition: transform 0.1s;
    }
    .action-btn:active {
        transform: scale(0.9);
    }
    /* Nav Pills Styling */
    .nav-pills .nav-link {
        transition: all 0.3s ease;
    }
    /* Custom Dropdown Styling */
    .dropdown-menu {
        animation: fadeIn 0.2s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // AJAX Like Feature
        const likeBtns = document.querySelectorAll('.like-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        likeBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const feedbackId = this.getAttribute('data-id');
                const url = `/feedback/${feedbackId}/like`;
                
                // Animasi visual sementara
                this.style.transform = 'scale(1.2)';
                setTimeout(() => { this.style.transform = 'scale(1)'; }, 200);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const icon = this.querySelector('.like-icon');
                    const countSpan = this.querySelector('.like-count');

                    if (data.isLiked) {
                        this.classList.remove('text-secondary');
                        this.classList.add('text-danger');
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        this.classList.remove('text-danger');
                        this.classList.add('text-secondary');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                    
                    countSpan.textContent = data.likesCount + ' Dukungan';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>
@endsection
