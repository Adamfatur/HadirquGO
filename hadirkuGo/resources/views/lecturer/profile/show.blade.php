@extends('layout.lecturer')

@section('content')
    <div class="container py-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-center align-items-center flex-column">
            <div class="card p-4 shadow-sm w-100 animate__animated animate__fadeIn" style="max-width: 500px; border-radius: 20px;">
                <div class="text-center">
                    @php
                        $rankStyle = isset($profileData['rank']) ? \App\Helpers\RankHelper::getRankStyle($profileData['rank'], $profileData['frame_color'] ?? null) : null;
                    @endphp
                    <div class="position-relative d-inline-block">
                        <img src="{{ $user->avatar ?? asset('images/default.jpg') }}"
                             alt="User Avatar"
                             class="rounded-circle animate__animated animate__bounceIn {{ $rankStyle && $profileData['rank'] <= 50 ? $rankStyle['class'] : '' }}"
                             style="width: 100px; height: 100px; object-fit: cover; {{ $rankStyle && $profileData['rank'] <= 50 ? $rankStyle['glow'] : 'border: 2px solid #f0f0f0;' }}">
                        @if($rankStyle && $profileData['rank'] <= 3)
                            <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $rankStyle['iconColor'] }}" style="font-size: 1.2rem; transform: translate(-50%, -80%) !important;"></i>
                        @endif
                    </div>

                    <h3 class="mt-3 animate__animated animate__fadeIn">
                        {{ $user->display_name }}
                        @if($user->biodata && $user->biodata->verified)
                            <span class="text-primary ms-2" data-bs-toggle="tooltip" title="Verified"><i class="fas fa-check-circle"></i></span>
                        @endif
                    </h3>
                    @if($profileData['title'] ?? null)
                        <div class="mb-2">{!! \App\Helpers\RankHelper::getTitleBadge($profileData['title'], $profileData['rank'], $profileData['frame_color']) !!}</div>
                    @endif
                    <p class="text-muted animate__animated animate__fadeIn">{{ $user->email ?? __('No email provided') }}</p>

                    <p class="mt-2 text-muted font-italic animate__animated animate__fadeIn">
                        "{{ optional($user->biodata)->about ?? __('No bio available.') }}"
                    </p>
                </div>

                {{-- Stats Row --}}
                <div class="row g-2 mb-3 text-center">
                    <div class="col-3">
                        <div class="p-2 rounded-3" style="background: linear-gradient(135deg, #00f2fe, #4facfe); color: white;">
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ $profileData['level_number'] }}</div>
                            <div style="font-size: 0.65rem;">{{ __('Level') }}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded-3" style="background: linear-gradient(135deg, #f59e0b, #ef4444); color: white;">
                            <div class="fw-bold" style="font-size: 1.1rem;">#{{ $profileData['rank'] ? number_format($profileData['rank']) : '-' }}</div>
                            <div style="font-size: 0.65rem;">{{ __('Rank') }}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded-3" style="background: linear-gradient(135deg, #10b981, #34d399); color: white;">
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ $profileData['total_achievements'] }}</div>
                            <div style="font-size: 0.65rem;">{{ __('Badges') }}</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded-3" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: white;">
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ number_format($profileData['total_points']) }}</div>
                            <div style="font-size: 0.65rem;">{{ __('Points') }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <span class="badge rounded-pill px-3 py-1" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd; font-size: 0.8rem;">
                        {{ $profileData['level_name'] }} &middot; {{ $profileData['total_sessions'] }} {{ __('Sessions') }}
                    </span>
                </div>

                <hr>

                <!-- User Info -->
                <div class="px-3 animate__animated animate__fadeIn">
                    <p><strong>{{ __('Phone Number') }}:</strong> {{ optional($user->biodata)->phone_number ?? '-' }}</p>
                    <p><strong>{{ __('ID Number') }}:</strong> {{ optional($user->biodata)->id_number ?? $user->member_id ?? '-' }}</p>
                    <p><strong>{{ __('Other ID Number') }}:</strong> {{ optional($user->biodata)->other_id_number ?? '-' }}</p>
                    <p><strong>{{ __('Nickname') }}:</strong> {{ optional($user->biodata)->nickname ?? '-' }}</p>
                    <p><strong>{{ __('Birth Date') }}:</strong>
                        {{ optional($user->biodata)->birth_date ? \Carbon\Carbon::parse($user->biodata->birth_date)->format('d-m-Y') : 'Not set' }}
                    </p>
                </div>

                <div class="text-center">
                    <!-- {{ __('Edit') }} Button -->
                    <button class="btn btn-primary w-100 mb-2 animate__animated animate__fadeInUp" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit me-2"></i>{{ __('Edit') }} Profile
                    </button>

                    @if(!$user->name_changed)
                        <button class="btn btn-outline-info w-100 mb-2 animate__animated animate__fadeInUp" data-bs-toggle="modal" data-bs-target="#changeNameModal">
                            <i class="fas fa-signature me-2"></i>Change Name (1x only)
                        </button>
                    @endif

                    <!-- Update Birth Date Button (Hanya tampil jika belum diatur) -->
                    @if(!$user->biodata || !$user->biodata->birth_date)
                        <button class="btn btn-secondary w-100 mb-2 animate__animated animate__fadeInUp" data-bs-toggle="modal" data-bs-target="#updateBirthDateModal">
                            <i class="fas fa-calendar-alt me-2"></i>Set Birth Date
                        </button>
                    @endif

                    <!-- Logout Button -->
                    <button class="btn btn-danger w-100 animate__animated animate__fadeInUp" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('Edit') }} Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content animate__animated animate__fadeIn">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">{{ __('Edit') }} Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('lecturer.profile.update') }}" method="POST">
                        @csrf

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', optional($user->biodata)->phone_number ?? '') }}" placeholder="Enter your phone number">
                            <small class="text-muted">Optional. Provide your phone number if you wish.</small>
                        </div>

                        <!-- ID Number -->
                        <div class="mb-3">
                            <label for="id_number" class="form-label">ID Number</label>
                            <input type="text" name="id_number" class="form-control" value="{{ old('id_number', optional($user->biodata)->id_number ?? '') }}" placeholder="You can use your member ID (not your ID card number)">
                            <small class="text-muted">Optional. ID member can be replaced with your personal ID, but it is recommended not to use your official ID card number.</small>
                        </div>

                        <!-- Other ID Number -->
                        <div class="mb-3">
                            <label for="other_id_number" class="form-label">Other ID Number</label>
                            <input type="text" name="other_id_number" class="form-control" value="{{ old('other_id_number', optional($user->biodata)->other_id_number ?? '') }}" placeholder="Enter another ID number (optional)">
                            <small class="text-muted">Optional. If you have another ID number you would like to provide, you can enter it here.</small>
                        </div>

                        <!-- Nickname -->
                        <div class="mb-3">
                            <label for="nickname" class="form-label">Nickname</label>
                            <input type="text" name="nickname" class="form-control" value="{{ old('nickname', optional($user->biodata)->nickname ?? '') }}" placeholder="Enter your nickname" maxlength="50">
                            <small class="text-muted">Optional. Provide a nickname if you'd like.</small>
                        </div>

                        <!-- About -->
                        <div class="mb-3">
                            <label for="about" class="form-label">{{ __('About') }}</label>
                            <textarea name="about" class="form-control" placeholder="Tell us something about yourself">{{ old('about', optional($user->biodata)->about ?? '') }}</textarea>
                            <small class="text-muted">Optional. Share something about yourself.</small>
                        </div>

                        <!-- {{ __('Save Changes') }} Button -->
                        <button type="submit" class="btn btn-success w-100 animate__animated animate__fadeInUp">
                            <i class="fas fa-save me-2"></i>{{ __('Save Changes') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Birth Date Modal -->
    <div class="modal fade" id="updateBirthDateModal" tabindex="-1" aria-labelledby="updateBirthDateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content animate__animated animate__fadeIn">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateBirthDateModalLabel">Set Birth Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('lecturer.profile.updateBirthDate') }}" method="POST">
                        @csrf

                        <!-- Birth Date -->
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Date of Birth</label>
                            <input type="date" name="birth_date" class="form-control" id="birth_date" value="{{ old('birth_date', optional($user->biodata)->birth_date ?? '') }}" required>
                            <small class="text-muted">You can set your birth date once. After setting, it cannot be changed.</small>
                        </div>

                        <!-- {{ __('Save Changes') }} Button -->
                        <button type="submit" class="btn btn-success w-100 animate__animated animate__fadeInUp">
                            <i class="fas fa-calendar-check me-2"></i>Set Birth Date
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout {{ __('Confirm') }}ation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content animate__animated animate__fadeIn">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutModalLabel">{{ __('Confirm') }} Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to log out?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger animate__animated animate__fadeInUp">
                            <i class="fas fa-sign-out-alt me-2"></i>Yes, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 scripts for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script>
        // Initialize tooltip
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (element) {
            new bootstrap.Tooltip(element);
        });
    </script>

    <!-- Change Name Modal -->
    @if(!$user->name_changed)
    <div class="modal fade" id="changeNameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('lecturer.profile.updateName') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-signature me-2"></i>Change Your Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning small"><i class="fas fa-exclamation-triangle me-1"></i> You can only change your name once. Make sure it's correct.</div>
                        <div class="mb-3">
                            <label class="form-label">Current Name</label>
                            <input type="text" class="form-control" value="{{ $user->display_name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your real name" required minlength="3" maxlength="100" pattern="[a-zA-Z\s\.]+">
                            <div class="form-text">Letters, spaces, and dots only. Min 3 characters.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save Name</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection