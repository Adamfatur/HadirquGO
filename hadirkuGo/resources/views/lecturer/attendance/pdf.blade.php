<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Attendance Report for') }} {{ $team->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #153e75;
            font-size: 20px;
        }
        .summary {
            margin-top: 20px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #153e75;
            color: white;
            font-size: 12px;
        }
        td {
            font-size: 10px;
        }
        .present {
            color: #198754;
            font-weight: bold;
        } /* Green for presence */
        .absent {
            color: #dc3545;
            font-weight: bold;
        } /* Red for absence */
        .empty {
            color: #6c757d;
            font-weight: bold;
        } /* Grey for future dates */
    </style>
</head>
<body>
<h2>{{ __('Attendance Report for') }} {{ $team->name }}</h2>
<p>Month: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>

<div class="summary">
    <p><strong>Total Members:</strong> {{ $members->count() }}
        @if($showLeadersManagers)
            <em>(Including Leader & Managers)</em>
        @else
            <em>(Excluding Leader & Managers)</em>
        @endif
    </p>

    <!-- {{ __('Morning Person') }}(s) Section -->
    <p>
        <strong>Top {{ __('Morning Person') }}(s):</strong>
        @if($morningPersonData->isNotEmpty())
            @foreach($morningPersonData as $data)
                {{ $data['user']->name }} ({{ $data['count'] }}x){{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            Not available
        @endif
    </p>

    <!-- {{ __('Last Person') }}(s) Section -->
    <p>
        <strong>Top {{ __('Last Person') }}(s):</strong>
        @if($lastPersonData->isNotEmpty())
            @foreach($lastPersonData as $data)
                {{ $data['user']->name }} ({{ $data['count'] }}x){{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            Not available
        @endif
    </p>

    <!-- {{ __('Most Late') }} Person(s) Section -->
    <p>
        <strong>{{ __('Most Late') }} Person(s):</strong>
        @if($mostLatePersonData->isNotEmpty() && $mostLatePersonData->first()['count'] > 0)
            @foreach($mostLatePersonData as $data)
                {{ $data['user']->name }} ({{ $data['count'] }}x late){{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            No one is late!
        @endif
    </p>

    <!-- {{ __('Most Absent') }} Person(s) Section -->
    <p>
        <strong>{{ __('Most Absent') }} Person(s):</strong>
        @if($mostAbsentPersonData->isNotEmpty() && $mostAbsentPersonData->first()['count'] > 0)
            @foreach($mostAbsentPersonData as $data)
                {{ $data['user']->name }} ({{ $data['count'] }}x absent){{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            Perfect attendance!
        @endif
    </p>
</div>

<!-- Check-in/Checkout Times Table -->
<table>
    <thead>
    <tr>
        <th style="background-color: #153e75; color: white; font-size: 12px;">{{ __('Members') }}</th>
        @for($date = 1; $date <= \Carbon\Carbon::parse($month)->daysInMonth; $date++)
            <th style="background-color: #153e75; color: white; font-size: 12px;">{{ $date }}</th>
        @endfor
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td style="font-size: 10px;">
                {{ $member->name }}
            </td>
            @for($date = 1; $date <= \Carbon\Carbon::parse($month)->daysInMonth; $date++)
                @php
                    $currentDate = \Carbon\Carbon::parse($month)->day($date)->format('Y-m-d');
                    $attendanceKey = $member->id . '_' . $currentDate;
                    $attendanceRecords = $attendanceByUserDate->get($attendanceKey);
                    $isPastDate = \Carbon\Carbon::parse($currentDate)->isPast();

                    // Get the first check-in and last checkout time for the day
                    $earliestCheckIn = $attendanceRecords ? $attendanceRecords->first()->checkin_time : null;
                    $latestCheckOut = $attendanceRecords ? $attendanceRecords->last()->checkout_time : null;
                @endphp
                <td style="font-size: 8px; text-align: center;">
                    @if(!$isPastDate)
                        <span class="empty">-</span>
                    @elseif($attendanceRecords && $earliestCheckIn)
                        <span style="color: #28a745;">{{ $earliestCheckIn->format('H:i') }}</span>
                        @if($latestCheckOut)
                            <br>
                            <span style="color: #dc3545;">{{ $latestCheckOut->format('H:i') }}</span>
                        @endif
                    @else
                        <span class="absent">A</span>
                    @endif
                </td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>

<!-- {{ __('Ranking by Total Duration') }} -->
<h2 style="margin-top: 30px;">{{ __('Ranking by Total Duration') }}</h2>
<table>
    <thead>
    <tr>
        <th>{{ __('Rank') }}</th>
        <th>{{ __('Members') }}</th>
        <th>{{ __('Total Duration') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sortedMemberDurations as $index => $data)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $data['user']->name }}</td>
            <td>
                @php
                    $hours = floor($data['total_duration'] / 60);
                    $minutes = $data['total_duration'] % 60;
                @endphp
                {{ $hours }}h {{ $minutes }}m
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>