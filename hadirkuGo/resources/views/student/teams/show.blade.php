{{-- resources/views/student/teams/show.blade.php --}}

@extends('layout.student')

@section('title', 'Team Details')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white" style="font-size: 1.5rem;">
                🏆 Team Details: {{ $team->name }} 🏆
            </h2>
            <p class="text-light" style="font-size: 1rem; margin-top: -5px;">
                Here is the detailed information and rankings for <strong>{{ $team->name }}</strong>.
            </p>
        </div>

        <!-- Filter Form -->
        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('student.teams.show', $team->team_unique_id) }}" class="d-inline-block p-3 bg-white shadow-sm rounded-4" style="border: 1px solid #e0e0e0;">
                <div class="d-flex flex-wrap align-items-end justify-content-center gap-2">
                    <div>
                        <label class="form-label text-muted small fw-bold mb-1">Time Period</label>
                        <select name="period" id="studentPeriodSelect" class="form-select shadow-sm" style="border-radius: 8px;">
                            <option value="daily"   {{ $period == 'daily'   ? 'selected' : '' }}>{{ __('Daily') }}</option>
                            <option value="weekly"  {{ $period == 'weekly'  ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                            <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                            <option value="60_days" {{ $period == '60_days' ? 'selected' : '' }}>Last 60 Days</option>
                            <option value="90_days" {{ $period == '90_days' ? 'selected' : '' }}>Last 90 Days</option>
                            <option value="6_months" {{ $period == '6_months' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="1_year" {{ $period == '1_year' ? 'selected' : '' }}>Last 1 Year</option>
                            <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Date</option>
                        </select>
                    </div>
                    
                    <div class="custom-date-inputs" style="{{ $period == 'custom' ? '' : 'display: none;' }}">
                        <label class="form-label text-muted small fw-bold mb-1">Start Date</label>
                        <input type="date" name="start_date" class="form-control shadow-sm" style="border-radius: 8px;" value="{{ request('start_date') }}">
                    </div>
                    
                    <div class="custom-date-inputs" style="{{ $period == 'custom' ? '' : 'display: none;' }}">
                        <label class="form-label text-muted small fw-bold mb-1">End Date</label>
                        <input type="date" name="end_date" class="form-control shadow-sm" style="border-radius: 8px;" value="{{ request('end_date') }}">
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 8px; background-color: #1e3a8a; border: none;">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cards Section -->
        <div class="row mb-4">
            <!-- Total Member Card -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #6a11cb, #2575fc); border: none;">
                    <div class="card-body text-center text-white">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <h5 class="card-title" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Total Members</h5>
                        <p class="card-text display-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $allUsers->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Points Card -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #00b09b, #96c93d); border: none;">
                    <div class="card-body text-center text-white">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-star fa-3x"></i>
                        </div>
                        <h5 class="card-title" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Total Points</h5>
                        <p class="card-text display-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $totalTeamPoints }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Duration Card -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #ff6f61, #ffcc00); border: none;">
                    <div class="card-body text-center text-white">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <h5 class="card-title" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Total Duration</h5>
                        @php
                            $totalHours = intdiv($totalTeamDuration, 60);
                            $totalMinutes = $totalTeamDuration % 60;
                        @endphp
                        <p class="card-text display-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                            {{ $totalHours }}h {{ $totalMinutes }}m
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Details Section -->
        <div class="card shadow-sm p-4 mb-5 animate__animated animate__fadeIn" style="border-radius: 20px; background-color: #f9f9fb;">
            <div class="card-header bg-transparent border-0">
                <h2 class="mb-0 text-secondary font-weight-bold" style="color: #1e3a8a;">
                    Team Details - {{ str_replace("_", " ", ucfirst($period)) }}
                </h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Team Unique ID:</strong> {{ $team->team_unique_id }}</p>
                        <p><strong>Leader:</strong> {{ $team->leader->name }}</p>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h4 class="mb-0">Member Rankings</h4>
                    <span class="badge bg-primary px-3 py-2 shadow-sm" style="border-radius: 20px; font-size: 0.9rem;">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    </span>
                </div>
                <p class="text-muted mb-4">
                    Below is the ranking of team members based on their attendance duration for the selected period.
                </p>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: #1e3a8a;">
                        <tr>
                            <th class="text-center" style="width: 10%;">#</th>
                            <th>{{ __('Name') }}</th>
                            <th>Role</th>
                            <th>{{ __('Duration') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($userDurations as $index => $userDuration)
                            <tr class="border-bottom animate__animated animate__fadeIn" @if($userDuration['user']->id === auth()->id()) style="background-color: #f0f4ff; border-left: 5px solid #1e3a8a;" @endif>
                                <!-- Ranking Column -->
                                <td class="text-center fw-bold" style="font-size: 1.25rem;">
                                    @if($index == 0)
                                        🥇
                                    @elseif($index == 1)
                                        🥈
                                    @elseif($index == 2)
                                        🥉
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>

                                <!-- User Info -->
                                <td class="fw-bold text-dark">
                                    <img src="{{ $userDuration['user']->avatar ?? asset('images/default-avatar.png') }}"
                                         alt="Avatar"
                                         class="rounded-circle me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    {{ $userDuration['user']->name }} @if($userDuration['user']->id === auth()->id()) <span class="badge bg-primary ms-1" style="font-size: 0.6rem;">YOU</span> @endif
                                </td>

                                <!-- Role -->
                                <td>
                                    @if ($userDuration['user']->id === $team->leader_id)
                                        <span class="badge bg-danger">Leader</span>
                                    @elseif ($team->managers->contains($userDuration['user']))
                                        <span class="badge bg-warning">Manager</span>
                                    @else
                                        <span class="badge bg-success">Member</span>
                                    @endif
                                </td>

                                <!-- Duration -->
                                <td>
                                    @php
                                        $totalMinutes = $userDuration["duration"];
                                        $hours = intdiv($totalMinutes, 60);
                                        $minutes = $totalMinutes % 60;
                                    @endphp

                                    @if($hours > 0)
                                        {{ $hours }} hours
                                        @if($minutes > 0)
                                            {{ $minutes }} minutes
                                        @endif
                                    @else
                                        {{ $minutes }} minutes
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.getElementById('studentPeriodSelect');
        if(select) {
            select.addEventListener('change', function() {
                var inputs = document.querySelectorAll('.custom-date-inputs');
                if (this.value === 'custom') {
                    inputs.forEach(el => el.style.display = '');
                } else {
                    inputs.forEach(el => el.style.display = 'none');
                }
            });
        }
    });
</script>
@endsection

@push('styles')
    <style>
        /* Animasi hover pada tombol */
        .btn-hover-animate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-hover-animate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Animasi pada select dropdown */
        .select-animate {
            transition: all 0.3s ease;
        }
        .select-animate:hover {
            transform: scale(1.02);
        }

        /* Animasi fadeIn untuk card dan tabel */
        .animate__animated.animate__fadeIn {
            animation-duration: 0.5s;
        }

        /* Responsif untuk tombol navigasi */
        @media (max-width: 768px) {
            .d-flex.flex-wrap {
                gap: 8px;
            }
            .btn {
                padding: 8px 12px;
                font-size: 14px;
            }
        }

        /* Efek hover pada baris tabel */
        .table-hover tbody tr:hover {
            background-color: #e6f0ff;
            transition: background-color 0.3s ease;
        }

        /* Gaya untuk tombol aktif */
        .btn-primary.active {
            background-color: #152c5b;
            border-color: #152c5b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Tambahkan animasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.animate__animated');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = 1;
                }, index * 100); // Delay animasi untuk setiap item
            });
        });
    </script>
@endpush