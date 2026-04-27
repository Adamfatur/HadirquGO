<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Custom {{ __('Attendance Report for') }} {{ $team->name }} | HadirkuGO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="https://drive.pastibisa.app/1737344039_678dc427e611b.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }
        .summary-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s; height: 100%; }
        .summary-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .icon-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 15px; }
        .bg-gradient-primary { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; }
        .bg-gradient-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); color: white; }
        .bg-gradient-danger { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); color: white; }
        .bg-gradient-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
        .table-custom { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table-custom thead th { background-color: #1e3a8a; color: white; padding: 15px; }
    </style>
</head>
<body>
@php use App\Helpers\RankHelper; @endphp
@php
    $role = auth()->user()->role;
    $routePrefix = ($role === 'Lecturer') ? 'lecturer' : 'student';
    $allLevels = \App\Models\Level::orderBy('minimum_points', 'asc')->get();
@endphp
@include('partials.lang_switcher')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <a href="{{ route($routePrefix . '.attendance.index', $team->team_unique_id) }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i> {{ __('Back to Monthly View') }}</a>
        <h3 class="mb-0 fw-bold text-primary" style="color: #1e3a8a !important;">{{ __('Custom Report') }}</h3>
    </div>

    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark mb-2">{{ $team->name }}</h1>
        <div class="d-inline-flex align-items-center bg-white px-4 py-2 rounded-pill shadow-sm border">
            <i class="fas fa-calendar-alt text-primary me-2"></i>
            <span class="fw-medium text-muted">Period: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
        </div>
    </div>

    <div class="filter-card p-4 mb-5 bg-white shadow-sm rounded-4" style="border-left: 5px solid #1e3a8a;">
        <form method="GET" action="{{ route($routePrefix . '.attendance.custom', $team->team_unique_id) }}" class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label text-muted fw-semibold small">Time Range</label>
                <select name="range_type" class="form-select border-0 bg-light" id="rangeSelect">
                    <option value="60_days" {{ $rangeType == '60_days' ? 'selected' : '' }}>Last 60 Days</option>
                    <option value="90_days" {{ $rangeType == '90_days' ? 'selected' : '' }}>Last 90 Days</option>
                    <option value="6_months" {{ $rangeType == '6_months' ? 'selected' : '' }}>Last 6 Months</option>
                    <option value="1_year" {{ $rangeType == '1_year' ? 'selected' : '' }}>Last 1 Year</option>
                    <option value="custom" {{ $rangeType == 'custom' ? 'selected' : '' }}>Custom Date</option>
                </select>
            </div>
            <div class="col-md-2 custom-date-inputs" style="{{ $rangeType == 'custom' ? '' : 'display: none;' }}"><label class="form-label text-muted small">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"></div>
            <div class="col-md-2 custom-date-inputs" style="{{ $rangeType == 'custom' ? '' : 'display: none;' }}"><label class="form-label text-muted small">End Date</label><input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"></div>
            <div class="col-md-3"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="show_leaders_managers" value="1" {{ $showLeadersManagers ? 'checked' : '' }}><label class="form-check-label small">{{ __('Include Roles') }}</label></div></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #1e3a8a;">{{ __('Apply') }}</button></div>
        </form>
    </div>

    <!-- Highlights -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3"><div class="summary-card p-4 text-center" data-bs-toggle="modal" data-bs-target="#morningPersonModal"><div class="icon-circle bg-gradient-primary shadow-sm"><i class="fas fa-sun"></i></div><h6 class="text-muted small fw-bold">{{ __('Morning Person') }}</h6>@if($morningPersonData->isNotEmpty()) @foreach($morningPersonData as $d) <h5 class="fw-bold mb-0">{{ $d['user']->name }}</h5><span class="badge bg-light text-primary mt-2">{{ $d['count'] }}x</span> @endforeach @else <h5>-</h5> @endif</div></div>
        <div class="col-md-6 col-lg-3"><div class="summary-card p-4 text-center" data-bs-toggle="modal" data-bs-target="#lastPersonModal"><div class="icon-circle bg-gradient-warning shadow-sm"><i class="fas fa-moon"></i></div><h6 class="text-muted small fw-bold">{{ __('Last Person') }}</h6>@if($lastPersonData->isNotEmpty()) @foreach($lastPersonData as $d) <h5 class="fw-bold mb-0">{{ $d['user']->name }}</h5><span class="badge bg-light text-warning mt-2">{{ $d['count'] }}x</span> @endforeach @else <h5>-</h5> @endif</div></div>
        <div class="col-md-6 col-lg-3"><div class="summary-card p-4 text-center" data-bs-toggle="modal" data-bs-target="#mostLatePersonModal"><div class="icon-circle bg-gradient-danger shadow-sm"><i class="fas fa-clock"></i></div><h6 class="text-muted small fw-bold">{{ __('Most Late') }}</h6>@if($mostLatePersonData->isNotEmpty()) @foreach($mostLatePersonData as $d) <h5 class="fw-bold mb-0">{{ $d['user']->name }}</h5><span class="badge bg-light text-danger mt-2">{{ $d['count'] }}x</span> @endforeach @else <h5 class="text-success small">None</h5> @endif</div></div>
        <div class="col-md-6 col-lg-3"><div class="summary-card p-4 text-center" data-bs-toggle="modal" data-bs-target="#mostAbsentPersonModal"><div class="icon-circle bg-gradient-success shadow-sm"><i class="fas fa-user-slash"></i></div><h6 class="text-muted small fw-bold">{{ __('Most Absent') }}</h6>@if($mostAbsentPersonData->isNotEmpty() && $mostAbsentPersonData->first()['count'] > 0) @foreach($mostAbsentPersonData as $d) <h5 class="fw-bold mb-0">{{ $d['user']->name }}</h5><span class="badge bg-light text-success mt-2">{{ $d['count'] }}x</span> @endforeach @else <h5 class="text-success small">Perfect</h5> @endif</div></div>
    </div>

    <div class="table-custom mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead><tr><th style="width: 60px;">#</th><th class="text-start ps-4">{{ __('Member Identity') }}</th><th>{{ __('Level') }}</th><th>{{ __('Check-ins') }}</th><th>{{ __('Duration') }}</th><th>{{ __('Status') }}</th></tr></thead>
                <tbody class="bg-white">
                @foreach($members as $index => $member)
                    @php
                        $mAtt = $attendances->where('user_id', $member->id);
                        $cCount = $mAtt->count();
                        $dData = $sortedMemberDurations->firstWhere('user.id', $member->id);
                        $tDur = $dData ? $dData['total_duration'] : 0;
                        $gEntry = $member->leaderboards->first();
                        $gRank = $gEntry ? $gEntry->current_rank : 999;
                        $gStyle = RankHelper::getRankStyle($gRank, $gEntry->frame_color ?? null);
                        $levelNum = RankHelper::getLevelNumber($member);
                        $userPts = $member->pointSummary->total_points ?? 0;
                        $levelModel = $allLevels->first(fn($l) => $userPts >= $l->minimum_points && $userPts <= $l->maximum_points);
                        $levelName = $levelModel ? $levelModel->name : 'Pioneer';
                    @endphp
                    <tr @if($member->id === auth()->id()) style="background-color: #eef2ff;" @endif>
                        <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                        <td class="text-start ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <img src="{{ $member->avatar ? (str_starts_with($member->avatar, 'http') ? $member->avatar : asset($member->avatar)) : asset('images/default-avatar.png') }}" class="rounded-circle {{ $gRank <= 50 ? $gStyle['class'] : '' }}" style="width: 48px; height: 48px; object-fit: cover; {{ $gRank <= 50 ? $gStyle['glow'] : 'border: 2px solid #f4f7f6;' }}">
                                    @if($gRank <= 3) <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $gStyle['iconColor'] }}" style="font-size: 0.9rem; transform: translate(-50%, -100%) !important;"></i> @endif
                                </div>
                                <div><div class="fw-bold text-dark">{{ $member->name }}</div>@if($gEntry && $gEntry->title) {!! RankHelper::getTitleBadge($gEntry->title, $gRank, $gEntry->frame_color) !!} @endif</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column align-items-center">
                                @if($levelModel && $levelModel->image_url)
                                    <img src="{{ asset($levelModel->image_url) }}" alt="Level" class="rounded-circle mb-1" style="width: 24px; height: 24px; object-fit: cover;">
                                @endif
                                <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">Lv.{{ $levelNum }}</span>
                                <span class="text-muted" style="font-size: 0.65rem;">{{ $levelName }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-primary border rounded-pill px-3">{{ $cCount }}x</span></td>
                        <td><div class="fw-semibold">{{ floor($tDur / 60) }}h {{ $tDur % 60 }}m</div></td>
                        <td><span class="badge {{ $cCount > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2">{{ $cCount > 0 ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('rangeSelect').addEventListener('change', function() {
        document.querySelectorAll('.custom-date-inputs').forEach(el => el.style.display = (this.value === 'custom' ? '' : 'none'));
    });
</script>
@include('lecturer.attendance.partials.morning-person-modal')
@include('lecturer.attendance.partials.last-person-modal')
@include('lecturer.attendance.partials.most-late-person-modal')
@include('lecturer.attendance.partials.most-absent-person-modal')
</body>
</html>
