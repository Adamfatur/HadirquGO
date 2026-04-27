<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Attendance Report for') }} {{ $team->name }} | HadirkuGO</title>
    <meta name="description" content="View detailed attendance reports, rankings, and summaries for {{ $team->name }} on HadirkuGO. Track team performance and attendance trends for {{ \Carbon\Carbon::parse($month)->format('F Y') }}.">
    <meta name="keywords" content="attendance report, team attendance, monthly summary, {{ $team->name }} attendance, HadirkuGO, Raharja University">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="https://drive.pastibisa.app/1737344039_678dc427e611b.png">
    <meta property="og:title" content="{{ __('Attendance Report for') }} {{ $team->name }} | HadirkuGO">
    <meta property="og:description" content="View detailed attendance reports, rankings, and summaries for {{ $team->name }} on HadirkuGO. Track team performance and attendance trends for {{ \Carbon\Carbon::parse($month)->format('F Y') }}.">
    <meta property="og:image" content="https://hadirkugo.raharja.ac.id/images/og-image.png">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; color: #153e75; }
        .summary-card { background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        .badge { font-size: 1rem; padding: 0.5em; cursor: pointer; }
        .badge.empty-badge { color: #6c757d; cursor: default; }
        .badge.present-badge { color: #198754; }
        .badge.absent-badge { color: #dc3545; }
        .future-date { color: #6c757d; }
        .table-dark { background-color: #153e75 !important; color: white; }
        .card-header.bg-primary { background-color: #153e75 !important; }
        .clickable-date { cursor: pointer; transition: background-color 0.2s; }
        .clickable-date:hover { background-color: #1e4a8e; }
        .sticky-col { position: sticky; background-color: #112e64 !important; color: white !important; left: 0; z-index: 10; min-width: 150px; border-right: 2px solid rgba(255,255,255,0.1) !important; }
        .sticky-col .text-muted { color: white !important; }
        .checkout-badge { color: #6f42c1 !important; }
        @media (max-width: 768px) { body { font-size: 14px; } h1 { font-size: 1.5rem; } .sticky-col { min-width: 120px; } }
    </style>
</head>
<body>
@php use App\Helpers\RankHelper; @endphp
@php
    $role = auth()->user()->role;
    $routePrefix = ($role === 'Lecturer') ? 'lecturer' : 'student';
@endphp
@include('partials.lang_switcher')
<div class="container my-5">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <a href="{{ route($routePrefix . '.dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}</a>
        <a href="{{ route($routePrefix . '.attendance.custom', $team->team_unique_id) }}" class="btn btn-outline-info"><i class="fas fa-calendar-week"></i> {{ __('Custom Range Report') }}</a>
    </div>

    <form method="GET" action="{{ route($routePrefix . '.attendance.index', $team->team_unique_id) }}" class="row g-3 align-items-center mb-4">
        <div class="col-auto"><label for="month" class="col-form-label">Month & Year:</label></div>
        <div class="col-auto flex-grow-1"><input type="month" id="month" name="month" class="form-control" value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}"></div>
        <div class="col-auto">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="showLeadersManagers" name="show_leaders_managers" value="1" {{ request('show_leaders_managers') ? 'checked' : '' }}>
                <label class="form-check-label" for="showLeadersManagers">Leaders & Managers</label>
            </div>
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-primary">{{ __('Filter') }}</button></div>
        <div class="col-auto">
            <a href="{{ route($routePrefix . '.attendance.pdf', ['team_unique_id' => $team->team_unique_id, 'month' => request('month', \Carbon\Carbon::now()->format('Y-m')), 'show_leaders_managers' => request('show_leaders_managers', false)]) }}" target="_blank" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
            <a href="{{ route($routePrefix . '.attendance.csv', ['teamUniqueId' => $team->team_unique_id, 'month' => request('month', \Carbon\Carbon::now()->format('Y-m')), 'show_leaders_managers' => request('show_leaders_managers', false)]) }}" target="_blank" class="btn btn-outline-success btn-sm ms-1"><i class="fas fa-file-csv"></i> CSV</a>
        </div>
    </form>

    <div class="text-center mb-4">
        <h1 class="fw-bold">{{ __('Attendance Report for') }} {{ $team->name }}</h1>
        <p class="text-muted">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
    </div>

    <!-- Highlights Cards -->
    <div class="row mb-4 summary-section d-flex">
        <div class="col-md-6 mb-3">
            <div class="summary-card p-3" style="cursor: pointer; transition: all 0.3s;" data-bs-toggle="modal" data-bs-target="#morningPersonModal">
                <p class="mb-0"><strong><i class="fas fa-sun me-2" style="color: #ffc107;"></i>Top {{ __('Morning Person') }}(s):</strong>
                @if($morningPersonData->isNotEmpty())
                    @foreach($morningPersonData as $data) <span class="text-primary">{{ $data['user']->name }} ({{ $data['count'] }}x){{ !$loop->last ? ',' : '' }}</span> @endforeach
                @else <span class="text-muted">No data</span> @endif
                </p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="summary-card p-3" style="cursor: pointer; transition: all 0.3s;" data-bs-toggle="modal" data-bs-target="#lastPersonModal">
                <p class="mb-0"><strong><i class="fas fa-moon me-2" style="color: #6c757d;"></i>Top {{ __('Last Person') }}(s):</strong>
                @if($lastPersonData->isNotEmpty())
                    @foreach($lastPersonData as $data) <span class="text-warning">{{ $data['user']->name }} ({{ $data['count'] }}x){{ !$loop->last ? ',' : '' }}</span> @endforeach
                @else <span class="text-muted">No data</span> @endif
                </p>
            </div>
        </div>
    </div>

    <div class="row mb-4 summary-section d-flex">
        <div class="col-md-6 mb-3">
            <div class="summary-card p-3" style="cursor: pointer; transition: all 0.3s;" data-bs-toggle="modal" data-bs-target="#mostLatePersonModal">
                <p class="mb-0"><strong><i class="fas fa-clock me-2" style="color: #dc3545;"></i>{{ __('Most Late') }} Person(s):</strong>
                @if($mostLatePersonData->isNotEmpty())
                    @foreach($mostLatePersonData as $data) <span class="text-danger">{{ $data['user']->name }} ({{ $data['count'] }}x){{ !$loop->last ? ',' : '' }}</span> @endforeach
                @else <span class="text-success">No one is late!</span> @endif
                </p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="summary-card p-3" style="cursor: pointer; transition: all 0.3s;" data-bs-toggle="modal" data-bs-target="#mostAbsentPersonModal">
                <p class="mb-0"><strong><i class="fas fa-user-slash me-2" style="color: #ffc107;"></i>{{ __('Most Absent') }} Person(s):</strong>
                @if($mostAbsentPersonData->isNotEmpty() && $mostAbsentPersonData->first()['count'] > 0)
                    @foreach($mostAbsentPersonData as $data) <span class="text-warning">{{ $data['user']->name }} ({{ $data['count'] }}x){{ !$loop->last ? ',' : '' }}</span> @endforeach
                @else <span class="text-success">Perfect attendance!</span> @endif
                </p>
            </div>
        </div>
    </div>

    @php
        $currentMonth = \Carbon\Carbon::parse($month);
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
    @endphp

    <div class="table-responsive mb-4">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
            <tr>
                <th scope="col" class="sticky-col">{{ __('Members') }}</th>
                @for($dateObj = $startOfMonth->copy(); $dateObj->lte($endOfMonth); $dateObj->addDay())
                    <th class="{{ $dateObj->isPast() ? 'clickable-date' : '' }}" onclick="{{ $dateObj->isPast() ? "showDateSummary('{$dateObj->format('Y-m-d')}')" : 'return false' }}">{{ $dateObj->day }}</th>
                @endfor
            </tr>
            </thead>
            <tbody>
            @foreach($members as $member)
                @php
                    $totalPresence = 0;
                    $dateObj = $startOfMonth->copy();
                    while ($dateObj->lte($endOfMonth)) {
                        if ($attendanceByUserDate->has($member->id . '_' . $dateObj->format('Y-m-d'))) { $totalPresence++; }
                        $dateObj->addDay();
                    }
                    $globalEntry = $member->leaderboards->first();
                    $globalRank = $globalEntry ? $globalEntry->current_rank : 999;
                    $globalStyle = RankHelper::getRankStyle($globalRank, $globalEntry->frame_color ?? null);
                @endphp
                <tr>
                    <td scope="row" class="sticky-col">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-2">
                                <img src="{{ $member->avatar ? (str_starts_with($member->avatar, 'http') ? $member->avatar : asset($member->avatar)) : asset('images/default-avatar.png') }}" 
                                     class="rounded-circle {{ $globalRank <= 50 ? $globalStyle['class'] : '' }}" 
                                     style="width: 35px; height: 35px; object-fit: cover; {{ $globalRank <= 50 ? $globalStyle['glow'] : 'border: 1px solid rgba(255,255,255,0.2);' }}">
                                @if($globalRank <= 3) <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $globalStyle['iconColor'] }}" style="font-size: 0.7rem; transform: translate(-50%, -110%) !important;"></i> @endif
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-white" style="font-size: 0.85rem; line-height: 1.1;">{{ $member->name }}</div>
                                @if($globalEntry && $globalEntry->title) <div class="small opacity-75 text-light" style="font-size: 0.6rem;">{{ $globalEntry->title }}</div> @endif
                                <small class="opacity-50 text-light" style="font-size: 0.6rem;">({{ $totalPresence }}x)</small>
                            </div>
                        </div>
                    </td>
                    @for($date = 1; $date <= $currentMonth->daysInMonth; $date++)
                        @php
                            $currentDate = $currentMonth->day($date)->format('Y-m-d');
                            $attendanceKey = $member->id . '_' . $currentDate;
                            $isPastDate = \Carbon\Carbon::parse($currentDate)->isPast();
                        @endphp
                        <td>
                            @if(!$isPastDate) <span class="future-date">-</span>
                            @elseif($attendanceByUserDate->has($attendanceKey))
                                @php
                                    $attendance = $attendanceByUserDate->get($attendanceKey)[0];
                                    $firstCheckin = \Carbon\Carbon::parse($attendance['checkin_time'])->format('H.i');
                                    $lastCheckout = isset($attendanceByUserDate->get($attendanceKey)[count($attendanceByUserDate->get($attendanceKey)) - 1]['checkout_time']) 
                                        ? \Carbon\Carbon::parse($attendanceByUserDate->get($attendanceKey)[count($attendanceByUserDate->get($attendanceKey)) - 1]['checkout_time'])->format('H.i') : null;
                                @endphp
                                <span class="present-badge" style="font-size: 0.75rem;" onclick="showAttendanceDetails({{ $member->id }}, '{{ $currentDate }}')">{{ $firstCheckin }}</span><br>
                                <span class="checkout-badge" style="font-size: 0.75rem;" onclick="showAttendanceDetails({{ $member->id }}, '{{ $currentDate }}')">{{ $lastCheckout ?? '-' }}</span>
                            @else <span class="absent-badge"><i class="fas fa-times"></i></span> @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-primary text-white p-3"><h5 class="mb-0 fw-bold"><i class="fas fa-medal me-2 text-warning"></i>{{ __('Ranking by Total Duration') }}</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light"><tr><th class="text-center" style="width: 80px;">{{ __('Rank') }}</th><th>{{ __('Member Identity') }}</th><th class="text-center">{{ __('Total Duration') }}</th></tr></thead>
                    <tbody>
                    @foreach($sortedMemberDurations as $index => $data)
                        @php
                            $rankingUser = $data['user'];
                            $globalEntry = $rankingUser->leaderboards->first();
                            $globalRank = $globalEntry ? $globalEntry->current_rank : 999;
                            $globalStyle = RankHelper::getRankStyle($globalRank, $globalEntry->frame_color ?? null);
                        @endphp
                        <tr @if($rankingUser->id === auth()->id()) class="table-primary" @endif>
                            <td class="text-center fw-bold fs-5">@if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else {{ $index + 1 }} @endif</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $rankingUser->avatar ? (str_starts_with($rankingUser->avatar, 'http') ? $rankingUser->avatar : asset($rankingUser->avatar)) : asset('images/default-avatar.png') }}"
                                             class="rounded-circle {{ $globalRank <= 50 ? $globalStyle['class'] : '' }}" style="width: 48px; height: 48px; object-fit: cover; {{ $globalRank <= 50 ? $globalStyle['glow'] : 'border: 2px solid #e2e8f0;' }}">
                                        @if($globalRank <= 3) <i class="fas fa-crown position-absolute top-0 start-50 translate-middle {{ $globalStyle['iconColor'] }}" style="font-size: 1rem; transform: translate(-50%, -100%) !important;"></i> @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $rankingUser->name }}</div>
                                        @if($globalEntry && $globalEntry->title) {!! RankHelper::getTitleBadge($globalEntry->title, $globalRank, $globalEntry->frame_color) !!} @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><span class="badge bg-white text-primary border border-primary border-opacity-25 px-3 py-2 fw-bold fs-6" style="border-radius: 10px;">{{ floor($data['total_duration'] / 60) }}h {{ $data['total_duration'] % 60 }}m</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="attendanceModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content"><div class="modal-header bg-primary text-white"><h5 class="modal-title">{{ __('Attendance Details') }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div id="attendanceDetails" class="list-group"></div></div></div></div></div>

<div class="modal fade" id="dateSummaryModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content"><div class="modal-header bg-gold text-dark"><h5 class="modal-title fw-bold">Attendance Summary for <span id="summaryDate"></span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body bg-white p-4"><div id="summaryStatsContainer"></div><div id="attendanceSummaryContent"></div></div></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showAttendanceDetails(mId, date) {
        let url = "{{ route($routePrefix . '.attendance.details', ['memberId' => ':mId', 'date' => ':date']) }}";
        url = url.replace(':mId', mId).replace(':date', date);
        fetch(url).then(r => r.json()).then(data => {
            document.getElementById('attendanceDetails').innerHTML = data.attendances.map((att, i) => `<div class="list-group-item"><h6>Session ${i+1}</h6><p>Location: ${att.location}</p><p>{{ __('Check-In') }}: ${att.checkin_time}</p><p>{{ __('Check-Out') }}: ${att.checkout_time || '-'}</p></div>`).join('');
            new bootstrap.Modal(document.getElementById('attendanceModal')).show();
        });
    }
    function showDateSummary(date) {
        let url = "{{ route($routePrefix . '.attendance.by-date', ['teamUniqueId' => $team->team_unique_id, 'date' => ':date']) }}";
        url = url.replace(':date', date);
        fetch(url).then(r => r.json()).then(data => {
            document.getElementById('summaryDate').textContent = data.date;
            document.getElementById('attendanceSummaryContent').innerHTML = '<table class="table"><thead><tr><th>No</th><th>{{ __('Name') }}</th><th>{{ __('Status') }}</th><th>Check-in</th><th>Checkout</th></tr></thead><tbody>' + data.attendances.map((a, i) => `<tr><td>${i+1}</td><td>${a.user_name}</td><td>${a.status}</td><td>${a.first_checkin}</td><td>${a.last_checkout}</td></tr>`).join('') + '</tbody></table>';
            new bootstrap.Modal(document.getElementById('dateSummaryModal')).show();
        });
    }
</script>
@include('lecturer.attendance.partials.morning-person-modal')
@include('lecturer.attendance.partials.last-person-modal')
@include('lecturer.attendance.partials.most-late-person-modal')
@include('lecturer.attendance.partials.most-absent-person-modal')
<style>.bg-gold { background-color: #FFD54F !important; }</style>
</body>
</html>
