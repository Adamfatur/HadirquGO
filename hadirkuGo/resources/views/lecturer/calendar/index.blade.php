@extends('layout.lecturer')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-white">📅 {{ __('Calendar') }}</h2>
            <h4 id="currentMonthYear" class="fw-bold text-light">
                {{ \Carbon\Carbon::parse($year . '-' . $month . '-01')->format('F Y') }}
            </h4>
        </div>

        <div class="alert alert-info alert-dismissible fade show" role="alert" style="background-color: #e6f5ff; color: #1e3a8a; border-color: #80c3f7;">
            <strong>Info:</strong> {{ __('Tap a date to view attendance details. Holidays are marked in') }} <span class="badge bg-warning text-dark">Kuning</span>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="calendar-container mb-5">
            <div class="calendar-grid">
                @foreach ($dates as $date)
                    @php
                        $carbonDate = \Carbon\Carbon::parse($date);
                        $formattedDate = $carbonDate->format('Y-m-d');
                        $attendanceStatusForDay = $attendanceStatus[$formattedDate] ?? null;
                        $isFutureDate = $carbonDate->isFuture();
                        $dayName = $carbonDate->isoFormat('ddd');
                        $isWeekend = $carbonDate->isWeekend();
                        // Cek apakah tanggal ini adalah hari libur dari data yang dikirim controller
                        $isHoliday = isset($publicHolidays[$formattedDate]);
                    @endphp

                    {{-- Menambahkan kelas 'holiday' jika tanggal adalah hari libur --}}
                    <div class="calendar-day
                        @if($attendanceStatusForDay == '✔️') attended
                        @elseif($isHoliday) holiday
                        @elseif($isFutureDate) future
                        @elseif($isWeekend) weekend @endif"
                         data-date="{{ $formattedDate }}"
                         data-status="{{ $attendanceStatusForDay }}">
                        <div class="day-name {{ ($isWeekend || $isHoliday) ? 'text-danger' : 'text-muted' }}">{{ $dayName }}</div>
                        <div class="day-number fw-bold {{ ($isWeekend || $isHoliday) ? 'text-danger' : '' }}">
                            {{ $carbonDate->format('d') }}
                        </div>

                        {{-- Menampilkan ikon atau nama hari libur dengan prioritas --}}
                        @if($attendanceStatusForDay == '✔️')
                            <div class="status-icon mt-2">
                                <i class="fas fa-check-circle text-success" style="font-size: 20px;"></i>
                            </div>
                        @elseif($isHoliday)
                            {{-- Tampilkan nama hari libur jika tidak ada catatan kehadiran --}}
                            <div class="holiday-name mt-1">
                                {{ $publicHolidays[$formattedDate] }}
                            </div>
                        @elseif(!$isFutureDate && !$isWeekend)
                            {{-- Tampilkan 'X' hanya jika bukan hari libur, bukan weekend, dan bukan masa depan --}}
                            <div class="status-icon mt-2">
                                <i class="fas fa-times-circle text-danger" style="font-size: 20px;"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="modal fade" id="attendanceDetailsModal" tabindex="-1" aria-labelledby="attendanceDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #1e3a8a; color: white; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                        <h5 class="modal-title" id="attendanceDetailsModalLabel">{{ __('Attendance Details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white;"></button>
                    </div>
                    <div class="modal-body" id="attendanceDetailsBody" style="padding: 1.5rem; font-size: 1rem; color: #333;">
                        <div class="d-flex flex-column align-items-center">
                            <p class="text-muted" style="font-size: 1rem;">{{ __('Loading attendance details...') }}</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e9ecef; justify-content: center;">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" style="background-color: #1e3a8a; color: white;">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tidak ada perubahan pada script JavaScript
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.addEventListener('click', function () {
                const date = this.dataset.date;

                // Jangan buka modal untuk tanggal di masa depan
                if(this.classList.contains('future')) {
                    return;
                }

                const formattedDate = new Date(date + 'T00:00:00'); // Tambahkan T00:00:00 untuk menghindari masalah timezone
                const weekday = formattedDate.toLocaleDateString('en-US', { weekday: 'long' });
                const dayOfMonth = formattedDate.getDate();
                const month = formattedDate.toLocaleDateString('en-US', { month: 'long' });
                const year = formattedDate.getFullYear();

                const modal = new bootstrap.Modal(document.getElementById('attendanceDetailsModal'));
                const modalBody = document.getElementById('attendanceDetailsBody');
                const modalLabel = document.getElementById('attendanceDetailsModalLabel');

                // Set judul modal
                modalLabel.innerText = `${weekday}, ${month} ${dayOfMonth}, ${year}`;

                // Tampilkan loading spinner sementara fetch data
                modalBody.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                modal.show();

                fetch(`/lecturer/attendance/details/${date}`)
                    .then(response => response.json())
                    .then(data => {
                        let detailsHTML = '';

                        if (data.status === "success" && data.details.length > 0) {
                            detailsHTML += "<div>";
                            data.details.forEach((attendance, index) => {
                                detailsHTML += `
                                    <div class="card mb-3 shadow-sm" style="border-radius: 12px;">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold">Activity ${index + 1}</h5>
                                            <p class="mb-1"><strong><i class="fas fa-sign-in-alt me-2"></i>Check-in:</strong> ${attendance.checkin_time}</p>
                                            <p class="mb-1"><strong><i class="fas fa-sign-out-alt me-2"></i>Checkout:</strong> ${attendance.checkout_time || 'Not checked out'}</p>
                                            <p class="mb-0"><strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong> ${attendance.location_name}</p>
                                        </div>
                                    </div>
                                `;
                            });
                            detailsHTML += "</div>";
                        } else {
                            detailsHTML += "<p class='text-center text-muted'>No attendance recorded for this day.</p>";
                        }

                        modalBody.innerHTML = detailsHTML;
                    })
                    .catch(error => {
                        modalBody.innerHTML = "<p class='text-center text-danger'>Error loading attendance details. Please try again.</p>";
                        console.error('Error:', error);
                    });
            });
        });
    </script>

    <style>
        .calendar-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
            width: 100%;
            max-width: 900px; /* Lebarkan grid untuk nama hari libur */
        }

        .calendar-day {
            cursor: pointer;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
            transition: transform 0.2s ease, background-color 0.2s ease;
            text-align: center;
            display: flex;
            justify-content: space-between; /* Atur ruang untuk konten */
            flex-direction: column;
            align-items: center;
            min-height: 100px; /* Tinggikan kotak untuk memuat nama hari libur */
            position: relative;
        }

        .calendar-day:hover {
            transform: scale(1.05);
            z-index: 10;
        }

        .calendar-day.attended {
            background-color: #d1ffd1; /* Hijau untuk hadir */
            border: 1px solid #a3e9a4;
        }
        .calendar-day.future {
            background-color: #f0f0f0; /* Abu-abu untuk masa depan */
            cursor: not-allowed;
        }
        .calendar-day.weekend {
            background-color: #fff1f1; /* Merah muda untuk weekend */
        }
        .calendar-day.holiday {
            background-color: #fff8e1; /* Kuning muda untuk hari libur */
            border: 1px solid #ffecb3;
        }

        .day-name {
            font-size: 0.8rem;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .day-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .status-icon {
            margin-top: 10px;
        }

        .holiday-name {
            font-size: 0.75rem; /* Ukuran font nama hari libur */
            color: #c08b00;
            font-weight: 500;
            line-height: 1.2;
            padding: 0 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Batasi hingga 3 baris */
            -webkit-box-orient: vertical;
            margin-top: auto; /* Dorong ke bawah */
        }

        .modal-content {
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .modal-header, .modal-footer button {
            border: none;
        }

        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .calendar-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .day-name {
                font-size: 0.7rem;
            }
            .day-number {
                font-size: 1rem;
            }
            .holiday-name {
                font-size: 0.65rem;
            }
        }
    </style>
@endsection