<!-- Notifications Card -->
<div class="card shadow-sm mb-4 p-3" style="border-radius: 15px; background-color: #ffffff;">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <div style="background-color: #1e3a8a;
                color: white;
                border-radius: 20px;
                padding: 8px 20px;
                font-size: 1rem;">
                <i class="fas fa-bell me-2"></i>
                <strong>Notifications</strong>
            </div>
        </div>
        <div id="notifications-timeline" class="mt-3">
            <p class="text-muted text-center">Loading notifications...</p>
        </div>
    </div>
</div>

<!-- Attendance Summary Card -->
<div class="card shadow-sm p-3 mb-4" style="border-radius: 15px; background-color: white; color: #1e3a8a;">
    <div class="card-body">
        <div class="d-flex align-items-center" style="background-color: #eef2ff; border-radius: 12px; padding: 5px;">
            <div class="flex-fill text-center" style="background-color: #1e3a8a; color: white; border-radius: 10px; padding: 8px 12px;">
                <span class="fw-bold">Summary</span>
            </div>
            <div class="flex-fill text-center" style="color: #1e3a8a; padding: 8px 12px;">
                <span><i class="fas fa-clock"></i> Weekly Stats</span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <h3 class="fw-bold" style="color: #1e3a8a;">{{ $totalSessions ?? 0 }}</h3>
                <p class="mb-1 text-muted small">Sessions</p>
            </div>
            <div>
                <h3 class="fw-bold" style="color: #1e3a8a;">
                    @if($totalWeeklyDuration > 60)
                        {{ floor($totalWeeklyDuration / 60) }} H {{ $totalWeeklyDuration % 60 }} M
                    @else
                        {{ $totalWeeklyDuration }} min
                    @endif
                </h3>
                <p class="mb-1 text-muted small">Weekly Duration</p>
            </div>
        </div>
    </div>
</div>

<!-- Insights Card -->
<div class="col-12 col-md-12">
    <div class="card shadow-sm mb-4 p-3" style="border-radius: 15px; background-color: #f1f4ff;">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between" style="background-color: #eef2ff; border-radius: 12px; padding: 5px;">
                <div class="flex-fill text-center" style="background-color: #1e3a8a; color: white; border-radius: 10px; padding: 8px 12px;">
                    <span class="fw-bold"><i class="fas fa-lightbulb me-2"></i> Insights</span>
                </div>
                <div class="flex-fill text-center" style="color: #1e3a8a; padding: 8px 12px;">
                    <span>Analysis</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="mb-1 small text-muted text-truncate">Most Visited</p>
                    <h5 class="fw-bold" style="color: #1e3a8a;">
                        {{ $mostFrequentLocation->attendanceLocation->name ?? 'N/A' }}
                    </h5>
                </div>
                <div>
                    <p class="mb-1 small text-muted text-truncate">Top Duration</p>
                    <h5 class="fw-bold" style="color: #1e3a8a;">
                        @if(isset($longestDuration->duration_at_location))
                            @if($longestDuration->duration_at_location > 60)
                                {{ floor($longestDuration->duration_at_location / 60) }} H {{ $longestDuration->duration_at_location % 60 }} M
                            @else
                                {{ $longestDuration->duration_at_location }} min
                            @endif
                        @else
                            N/A
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
