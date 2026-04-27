@if($activeAttendance)
    <div class="card shadow-sm mb-4 animate-card"
         style="border-radius: 20px; background: white; border: 1px solid #e2e8f0; overflow: hidden;">
        <div class="card-body p-4">
            <!-- Header Section -->
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-running fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Live Session</h6>
                        <p class="small text-muted mb-0">Active tracking in progress</p>
                    </div>
                </div>
                <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25 fw-bold" style="font-size: 0.75rem;">
                    <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span> ACTIVE
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="row g-3 text-center">
                <!-- {{ __('Check-In') }} Time -->
                <div class="col-4">
                    <p class="small text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Start Time</p>
                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;">
                        {{ $checkInTime ? $checkInTime->format('h:i A') : 'N/A' }}
                    </h5>
                </div>

                <!-- Vertical Divider -->
                <div class="col-auto d-none d-md-block border-end"></div>

                <!-- Elapsed Time -->
                <div class="col-4">
                    <p class="small text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Duration</p>
                    <h5 class="fw-bold mb-0 text-primary" style="font-size: 1.1rem;">
                        @if($elapsedMinutes !== null)
                            {{ $elapsedMinutes >= 60
                                ? floor($elapsedMinutes / 60) . 'H ' . ($elapsedMinutes % 60) . 'M'
                                : $elapsedMinutes . 'm' }}
                        @else
                            N/A
                        @endif
                    </h5>
                </div>

                <!-- Vertical Divider -->
                <div class="col-auto d-none d-md-block border-end"></div>

                <!-- Location -->
                <div class="col-4">
                    <p class="small text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ __('Location') }}</p>
                    <h5 class="fw-bold mb-0 text-danger text-truncate px-2" style="font-size: 1.1rem;" title="{{ $currentLocation ?? 'N/A' }}">
                        {{ $currentLocation ?? 'N/A' }}
                    </h5>
                </div>
            </div>
        </div>
    </div>
@endif
