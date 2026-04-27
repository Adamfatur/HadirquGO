@php
$isLecturer = Auth::user()->hasRole('Lecturer');
$checkinRoute = $isLecturer ? 'lecturer.qrcode.checkin' : 'qrcode.checkIn';
$checkoutRoute = $isLecturer ? 'lecturer.qrcode.checkout' : 'qrcode.checkout';
@endphp

<div class="row g-2 mb-4">
    <div class="col-12 col-md-6">
        <a href="{{ route($checkinRoute) }}"
           class="text-decoration-none">
            <div class="btn d-flex align-items-center justify-content-center py-3"
                 style="background: linear-gradient(135deg, #48bb78, #3182ce);
                        color: white;
                        border-radius: 12px;
                        font-weight: 700;
                        font-size: 1.1rem;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                        width: 100%;
                        border: none;
                        transition: all 0.3s ease;">
                <i class="fas fa-sign-in-alt me-2"></i> {{ __('Check-In') }}
            </div>
        </a>
    </div>

    <div class="col-12 col-md-6">
        @if($activeAttendance)
            <a href="{{ route($checkoutRoute) }}"
               class="text-decoration-none">
                <div class="btn d-flex align-items-center justify-content-center py-3"
                     style="background: linear-gradient(135deg, #f56565, #c53030);
                            color: white;
                            border-radius: 12px;
                            font-weight: 700;
                            font-size: 1.1rem;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                            width: 100%;
                            border: none;
                            transition: all 0.3s ease;">
                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('Check-Out') }}
                </div>
            </a>
        @else
            <div class="btn d-flex align-items-center justify-content-center py-3 opacity-50"
                 style="background: linear-gradient(135deg, #f56565, #c53030);
                        color: white;
                        border-radius: 12px;
                        font-weight: 700;
                        font-size: 1.1rem;
                        width: 100%;
                        border: none;
                        cursor: not-allowed;"
                 data-bs-toggle="modal"
                 data-bs-target="#checkinRequiredModal">
                <i class="fas fa-sign-out-alt me-2"></i> {{ __('Check-Out') }}
            </div>
        @endif
    </div>
</div>
