@extends('layout.lecturer')

@section('content')
    <div class="container mt-2" style="margin-top: 20px;">

        <h2 class="mb-4 text-center" style="color: #ffffff; font-size: 2rem; font-weight: bold; text-transform: uppercase; position: relative;">
            <i class="fas fa-calendar-check" style="color: #ebf0ff; margin-right: 10px;"></i>
            History
        </h2>

        <!-- Button to toggle filter form visibility -->
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary fw-bold" onclick="toggleFilter()">{{ __('Filter') }}</button>
        </div>

        <!-- Filter & Search Form -->
        <div id="filterForm" class="mb-4 p-4 rounded shadow-lg" style="background-color: #1e3a8a; color: #ffffff; display: none;">
            <form method="GET" action="{{ route('lecturer.attendance.history') }}">
                <!-- Include 'show_all' in the form -->
                <input type="hidden" name="show_all" value="true">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control border-0 shadow-sm" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control border-0 shadow-sm" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="location" name="location" placeholder="Enter location" value="{{ request('location') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="keyword" class="form-label">Keyword</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="keyword" name="keyword" placeholder="Search keyword" value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-end">
                        <button type="submit" class="btn btn-light fw-bold px-4 py-2 shadow-sm">Apply Filter</button>
                        <a href="{{ route('lecturer.attendance.history', ['show_all' => true]) }}" class="btn btn-outline-light fw-bold px-4 py-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        @if ($attendances->isNotEmpty())
            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-body p-4 text-center">
                    <h5 class="text-primary mb-3" style="font-size: 1.3rem; font-weight: 600;">Today's Attendance Summary</h5>
                    <div class="row justify-content-center">
                        <!-- Jumlah Record -->
                        <div class="col-12 col-sm-4 mb-3">
                            <div class="badge rounded-pill bg-light text-dark p-3 w-100" style="font-size: 1.1rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                                <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                                <strong>{{ $attendances->count() }}</strong> Records
                            </div>
                        </div>

                        <!-- Total Duration -->
                        <div class="col-12 col-sm-4 mb-3">
                            <div class="badge rounded-pill bg-light text-dark p-3 w-100" style="font-size: 1.1rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                                <i class="fas fa-clock me-2" style="color: #17a2b8;"></i>
                                <strong>
                                    @php
                                        $totalDuration = $attendances->sum('duration_at_location');
                                        $hours = floor($totalDuration / 60);
                                        $minutes = $totalDuration % 60;
                                    @endphp
                                    {{ $hours > 0 ? $hours . ' hrs ' : '' }}{{ $minutes > 0 ? $minutes . ' mins' : '' }}
                                </strong> Total Duration
                            </div>
                        </div>

                        <!-- Avg Duration -->
                        <div class="col-12 col-sm-4 mb-3">
                            <div class="badge rounded-pill bg-light text-dark p-3 w-100" style="font-size: 1.1rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                                <i class="fas fa-hourglass-half me-2" style="color: #ffc107;"></i>
                                <strong>
                                    @php
                                        $avgDuration = $attendances->avg('duration_at_location');
                                        $hours = $avgDuration ? floor($avgDuration / 60) : 0;
                                        $minutes = $avgDuration ? $avgDuration % 60 : 0;
                                    @endphp
                                    {{ $hours > 0 ? $hours . ' hrs ' : '' }}{{ $minutes > 0 ? round($minutes, 2) . ' mins' : 'N/A' }}
                                </strong> Avg Duration
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif





        <!-- Attendance Table -->
        @if ($attendances->isEmpty() && isset($lastAttendance))
            <!-- Display last attendance if no data for today -->
            <div class="alert alert-info text-center rounded shadow-sm py-4 px-5" style="background-color: #e9f7fd; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <i class="fas fa-calendar-alt" style="font-size: 2rem; color: #0d6efd;"></i>
                    <h5 class="ms-3" style="font-size: 1.25rem; color: #0d6efd; font-weight: 600;">No attendance records for today</h5>
                </div>
                <p class="text-center mb-3" style="font-size: 1rem; color: #495057;">Here's the most recent attendance:</p>

                <div class="card shadow-sm rounded p-4" style="background-color: #ffffff; border-radius: 12px;">
                    <div class="mb-3">
                        <p class="mb-1" style="font-weight: 500; color: #495057;">Date:</p>
                        <p class="mb-2" style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $lastAttendance->checkin_time->format('Y-m-d') }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1" style="font-weight: 500; color: #495057;">Location:</p>
                        <p class="mb-2" style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $lastAttendance->attendanceLocation->name }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1" style="font-weight: 500; color: #495057;">{{ __('Check-In') }} Time:</p>
                        <p class="mb-2" style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $lastAttendance->checkin_time->format('H:i:s') }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1" style="font-weight: 500; color: #495057;">{{ __('Check-Out') }} Time:</p>
                        <p class="mb-2" style="font-size: 1.1rem; font-weight: 600; color: #333;">
                            {{ $lastAttendance->checkout_time ? $lastAttendance->checkout_time->format('H:i:s') : 'Not Checked Out' }}
                        </p>
                    </div>

                    <div>
                        <p class="mb-1" style="font-weight: 500; color: #495057;">Duration:</p>
                        <p class="mb-2" style="font-size: 1.1rem; font-weight: 600; color: #333;">
                            @php
                                $duration = $lastAttendance->duration_at_location;
                                $hours = floor($duration / 60);
                                $minutes = $duration % 60;
                            @endphp
                            {{ $hours > 0 ? $hours . ' hrs ' : '' }}{{ $minutes > 0 ? $minutes . ' mins' : '0 mins' }}
                        </p>
                    </div>
                </div>
            </div>

        @elseif ($attendances->isEmpty())
            <!-- If no attendance at all for today, just display a default message -->
            <div class="alert alert-warning text-center">
                <p>No attendance records found for today.</p>
            </div>
        @else
            <div class="table-responsive rounded shadow-sm" style="background-color: #ffffff;">
                <table class="table table-hover text-center align-middle mb-0">
                    <thead style="background-color: #2c3e50; color: #ffffff;">
                    <tr>
                        <th class="p-3">Date</th>
                        <th class="p-3">Location</th>
                        <th class="p-3">{{ __('Check-In') }} Time</th>
                        <th class="p-3">{{ __('Check-Out') }} Time</th>
                        <th class="p-3">Duration at Location</th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 0.95em;">
                    @foreach ($attendances as $attendance)
                        <tr class="border-bottom">
                            <td class="p-3">{{ $attendance->checkin_time->format('Y-m-d') }}</td>
                            <td class="p-3">{{ $attendance->attendanceLocation->name }}</td>

                            <!-- {{ __('Check-In') }} Time with green label -->
                            <td class="p-3">
                    <span class="badge" style="background-color: #198754; color: #ffffff;">
                        {{ $attendance->checkin_time->format('H:i:s') }}
                    </span>
                            </td>

                            <!-- {{ __('Check-Out') }} Time with red label or 'Not Checked Out' if null -->
                            <td class="p-3">
                                @if ($attendance->checkout_time)
                                    <span class="badge" style="background-color: #dc3545; color: #ffffff;">
                            {{ $attendance->checkout_time->format('H:i:s') }}
                        </span>
                                @else
                                    <span class="badge" style="background-color: #dc3545; color: #ffffff;">
                            Not Checked Out
                        </span>
                                @endif
                            </td>

                            <!-- Duration with navy blue label -->
                            <td class="p-3">
                                @if ($attendance->duration_at_location || $attendance->duration_at_location === 0)
                                    @php
                                        $duration = $attendance->duration_at_location;
                                        $hours = floor($duration / 60);
                                        $minutes = $duration % 60;
                                    @endphp
                                    <span class="badge" style="background-color: #2c3e50; color: #ffffff;">
                            {{ $hours > 0 ? $hours . ' hrs ' : '' }}{{ $minutes > 0 ? $minutes . ' mins' : '0 mins' }}
                        </span>
                                @else
                                    <span class="badge" style="background-color: #2c3e50; color: #ffffff;">
                            In Progress
                        </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        @endif

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $attendances->links('vendor.pagination.bootstrap-4') }}
        </div>

    </div>
@endsection


<!-- Updated Styles -->
<style>

    body {
        background-color: #f4f7fa;
    }
    h2 {
        font-size: 2.2rem;
        font-weight: 600;
        color: #1e3a8a;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
    }
    .table th {
        background-color: #2c3e50;
        color: #ffffff;
    }
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

<script>
    function toggleFilter() {
        var filterForm = document.getElementById('filterForm');
        filterForm.style.display = (filterForm.style.display === 'none' || filterForm.style.display === '') ? 'block' : 'none';
    }
</script>
