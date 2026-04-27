@extends('layout.student')

@section('content')
    <div class="container mt-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-white">📅 {{ __('Calendar') }}</h2>
            <h4 id="currentMonthYear" class="fw-bold text-light">
                {{ \Carbon\Carbon::parse($year . '-' . $month . '-01')->format('F Y') }}
            </h4>
        </div>

        <!-- Alert Box -->
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="background-color: #e6f5ff; color: #1e3a8a; border-color: #80c3f7;">
            <strong>Info:</strong> Tap any day to view attendance details.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-container mb-5">
            <div class="calendar-grid">
                @foreach ($dates as $date)
                    @php
                        $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
                        $attendanceStatusForDay = $attendanceStatus[$formattedDate] ?? null;
                        $isFutureDate = \Carbon\Carbon::parse($date)->isFuture();
                    @endphp

                    <div class="calendar-day @if($attendanceStatusForDay == '✔️') attended @elseif($isFutureDate) future @endif"
                         data-date="{{ $formattedDate }}"
                         data-status="{{ $attendanceStatusForDay }}">
                        <div class="day-number fw-bold">{{ \Carbon\Carbon::parse($date)->format('d') }}</div>

                        @if($attendanceStatusForDay == '✔️')
                            <div class="status-icon mt-2">
                                <i class="fas fa-check-circle text-success" style="font-size: 20px;"></i>
                            </div>
                        @elseif(!$isFutureDate)
                            <div class="status-icon mt-2">
                                <i class="fas fa-times-circle text-danger" style="font-size: 20px;"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- {{ __('Attendance Details') }} Modal -->
        <div class="modal fade" id="attendanceDetailsModal" tabindex="-1" aria-labelledby="attendanceDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: #1e3a8a; color: white; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                        <h5 class="modal-title" id="attendanceDetailsModalLabel">{{ __('Attendance Details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white;"></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body" id="attendanceDetailsBody" style="padding: 1.5rem; font-size: 1rem; color: #333;">
                        <div class="d-flex flex-column align-items-center">
                            <p class="text-muted" style="font-size: 1rem;">{{ __('Loading attendance details...') }}</p>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer" style="border-top: 1px solid #e9ecef; justify-content: center;">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" style="background-color: #1e3a8a; color: white;">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show modal with attendance details
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.addEventListener('click', function () {
                const date = this.dataset.date;
                const formattedDate = new Date(date);
                const weekday = formattedDate.toLocaleDateString('en-US', { weekday: 'long' });
                const dayOfMonth = formattedDate.getDate();
                const month = formattedDate.toLocaleDateString('en-US', { month: 'long' });
                const year = formattedDate.getFullYear();

                // Fetch attendance details from the backend
                fetch(`/student/attendance/details/${date}`)
                    .then(response => response.json())
                    .then(data => {
                        const dateDetails = `<h5 class="mb-3">${weekday}, ${month} ${dayOfMonth}, ${year}</h5>`;
                        document.getElementById('attendanceDetailsBody').innerHTML = dateDetails;

                        if (data.status === "success" && data.details.length > 0) {
                            let detailsHTML = "<div>";
                            data.details.forEach((attendance, index) => {
                                detailsHTML += `
                                    <div class="card mb-3 shadow-sm" style="border-radius: 12px;">
                                        <div class="card-body">
                                            <h5 class="card-title">Activity ${index + 1}</h5>
                                            <p><strong>Check-in Time:</strong> ${attendance.checkin_time}</p>
                                            <p><strong>Location:</strong> ${attendance.location_name}</p>
                                            <p><strong>Checkout Time:</strong> ${attendance.checkout_time}</p>
                                        </div>
                                    </div>
                                `;
                            });
                            detailsHTML += "</div>";
                            document.getElementById('attendanceDetailsBody').innerHTML += detailsHTML;
                        } else {
                            document.getElementById('attendanceDetailsBody').innerHTML += "<p>No attendance recorded for this day.</p>";
                        }

                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById('attendanceDetailsModal'));
                        modal.show();
                    })
                    .catch(error => {
                        document.getElementById('attendanceDetailsBody').innerHTML = "<p>Error loading attendance details.</p>";
                    });
            });
        });
    </script>

    <style>
        /* Calendar Container */
        .calendar-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        /* Grid system for the calendar */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
            width: 100%;
            max-width: 400px;
        }

        /* Day styling */
        .calendar-day {
            cursor: pointer;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            transition: transform 0.2s ease, background-color 0.2s ease;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .calendar-day:hover {
            transform: scale(1.05);
            background-color: #e0f7fa;
        }

        /* Status colors */
        .calendar-day.attended {
            background-color: #d1ffd1;
        }
        .calendar-day.future {
            background-color: #e0e0e0;
        }

        .day-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .status-icon {
            margin-top: 10px;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 16px;
        }

        .modal-footer button {
            background-color: #1e3a8a;
            color: white;
            border: none;
        }

        /* Mobile responsiveness */
        @media (max-width: 600px) {
            .calendar-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }

            .day-number {
                font-size: 1rem;
            }
        }
    </style>
@endsection
