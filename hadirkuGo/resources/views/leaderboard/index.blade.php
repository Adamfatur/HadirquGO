@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary" style="font-size: 2.5rem; color: #1e3a8a;">
                🏆 Leaderboard - {{ ucfirst($period) }} 🏆
            </h2>
            <p class="text-muted">See how you stack up against your peers!</p>

            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Info:</strong> The leaderboard will be updated every 8 hours.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        </div>

        <!-- Back Button -->
        <div class="mb-3">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Filter Form with Custom Period Selection -->
        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('leaderboard.index') }}" class="d-inline-block">
                <select name="period" class="form-select w-auto d-inline-block me-2" onchange="this.form.submit()" style="border-radius: 10px;">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </form>
            @if($period == 'custom')
                <form method="GET" action="{{ route('leaderboard.index') }}" class="d-inline-block ms-3">
                    <div class="d-flex">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control w-auto d-inline-block" required>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control w-auto d-inline-block ms-2" required>
                        <button type="submit" class="btn btn-primary ms-2">Filter</button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Leaderboard Table -->
        <div class="card shadow-sm p-4 mb-5" style="border-radius: 20px; background-color: #f9f9fb;">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-white" style="background-color: #1e3a8a; border-radius: 10px;">
                <tr>
                    <th class="text-center" style="width: 10%;">#</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Total Points</th>
                    <th>Attendance Count</th>
                    <th>Total Duration</th> <!-- New column for Total Duration -->
                </tr>
                </thead>
                <tbody>
                @foreach($users as $index => $entry)
                    <tr class="border-bottom @if($index == 0) bg-warning @elseif($index == 1) bg-light @elseif($index == 2) bg-bronze @endif">
                        <td class="text-center fw-bold" style="font-size: 1.5rem;">
                            @if($index == 0) 🥇
                            @elseif($index == 1) 🥈
                            @elseif($index == 2) 🥉
                            @else {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="fw-bold" style="color: #333;">
                            <img src="{{ $entry['user']->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            {{ $entry['user']->name }}
                        </td>
                        <td style="color: #1e3a8a;">{{ $entry['team_name'] }}</td>
                        <td style="color: #f39c12;">{{ $entry['total_points'] }}</td>
                        <td>{{ $entry['attendance_count'] }}</td>
                        <td>{{ $entry['total_duration'] }}</td> <!-- Display the formatted total duration -->
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
