@php
    // Derived stats using correct weekly variables from controller
    $weeklyGoal = 5; // Set to minimum 5 sessions per week (1 session/day for 5 working days)
    $sessionsCount = $totalWeeklySessions ?? 0;
    $sessionProgress = min(100, ($sessionsCount / $weeklyGoal) * 100);
    
    // Use weekly average duration if available, otherwise fallback
    $avgDuration = $averageWeeklySessionDuration ?? 0;
    
    // Productivity level logic based on weekly sessions
    $productivityLabel = 'In Progress';
    $productivityColor = '#64748b';
    if ($sessionsCount >= 10) { $productivityLabel = 'Elite Performer'; $productivityColor = '#10b981'; }
    elseif ($sessionsCount >= 5) { $productivityLabel = 'Goal Achieved'; $productivityColor = '#3b82f6'; }
    elseif ($sessionsCount > 0) { $productivityLabel = 'Active'; $productivityColor = '#f59e0b'; }
@endphp

<style>
    .analytics-card { background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; }
    .stat-box { padding: 16px; border-radius: 20px; background: #f8fafc; border: 1px solid #f1f5f9; transition: all 0.3s ease; }
    .stat-box:hover { transform: translateY(-3px); border-color: #dbeafe; background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.03); }
    .stat-label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .stat-value { font-size: 1.25rem; font-weight: 800; color: #1e293b; line-height: 1; }
    
    .insight-pill { background: #eff6ff; border-radius: 12px; padding: 12px; border-left: 4px solid #3b82f6; height: 100%; }
    .insight-title { font-size: 0.75rem; font-weight: 700; color: #1e3a8a; margin-bottom: 2px; }
    .insight-desc { font-size: 0.85rem; color: #475569; font-weight: 600; }
    
    .goal-progress { height: 8px; border-radius: 10px; background: #f1f5f9; }
    .goal-progress-bar { border-radius: 10px; background: linear-gradient(90deg, #3b82f6, #60a5fa); transition: width 1s ease; }
</style>

<div class="analytics-card shadow-sm mb-4">
    <div class="card-body p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-chart-line me-2 text-primary"></i>Weekly Performance Analytics</h6>
                <p class="small text-muted mb-0">Attendance insights for your 5-day work cycle.</p>
            </div>
            <div class="badge px-3 py-2 rounded-pill" style="background: {{ $productivityColor }}15; color: {{ $productivityColor }}; border: 1px solid {{ $productivityColor }}30; font-weight: 700;">
                <i class="fas fa-bolt me-1"></i> {{ $productivityLabel }}
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-box text-center">
                    <p class="stat-label">Sessions</p>
                    <div class="stat-value">{{ number_format($sessionsCount) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box text-center">
                    <p class="stat-label">Total Time</p>
                    <div class="stat-value">
                        @if($totalWeeklyDuration > 60)
                            {{ floor($totalWeeklyDuration / 60) }}<small style="font-size: 0.7rem;">H</small> {{ $totalWeeklyDuration % 60 }}<small style="font-size: 0.7rem;">m</small>
                        @else
                            {{ $totalWeeklyDuration }}<small style="font-size: 0.7rem;">m</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box text-center">
                    <p class="stat-label">Avg. Focus</p>
                    <div class="stat-value">{{ round($avgDuration) }}<small style="font-size: 0.6rem; margin-left: 2px;">m/ses</small></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box text-center">
                    <p class="stat-label">Intensity</p>
                    <div class="stat-value">{{ round(($totalWeeklyDuration / 60) / 5, 1) }}<small style="font-size: 0.6rem; margin-left: 2px;">h/workday</small></div>
                </div>
            </div>
        </div>

        <!-- Deep Insights Row -->
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="insight-pill">
                    <div class="insight-title"><i class="fas fa-map-marker-alt me-1"></i> MOST VISITED LOCATION</div>
                    <div class="insight-desc">{{ $mostFrequentLocation->attendanceLocation->name ?? 'None' }}</div>
                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">Your primary hub during business hours.</div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="insight-pill" style="border-left-color: #8b5cf6; background: #f5f3ff;">
                    <div class="insight-title" style="color: #6d28d9;"><i class="fas fa-fire me-1"></i> TOP FOCUS SESSION</div>
                    <div class="insight-desc">
                        @if(isset($longestDuration->duration_at_location))
                            {{ floor($longestDuration->duration_at_location / 60) }}H {{ $longestDuration->duration_at_location % 60 }}M
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">Longest single session this week.</div>
                </div>
            </div>
        </div>

        <!-- Weekly Progress -->
        <div class="mt-4 pt-4 border-top">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-bold text-dark">Weekly Target ({{ $weeklyGoal }} Sessions)</span>
                <span class="small fw-bold text-primary">{{ round($sessionProgress) }}%</span>
            </div>
            <div class="progress goal-progress">
                <div class="progress-bar goal-progress-bar" role="progressbar" style="width: {{ $sessionProgress }}%" aria-valuenow="{{ $sessionProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="small text-muted mt-2 mb-0" style="font-size: 0.7rem;">
                @if($sessionProgress >= 100)
                    🏆 Goal Achieved! You've met your attendance target for this week.
                @else
                    🎯 Aim for <strong>{{ max(0, $weeklyGoal - $sessionsCount) }}</strong> more sessions to reach your baseline goal.
                @endif
            </p>
        </div>
    </div>
</div>

<div class="analytics-card shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-bell me-2 text-danger"></i>Recent Activity Timeline</h6>
        <div id="notifications-timeline" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
            <p class="text-muted text-center small py-4">Loading real-time activity...</p>
        </div>
    </div>
</div>
