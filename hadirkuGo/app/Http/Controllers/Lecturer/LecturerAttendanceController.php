<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\UserAchievement;
use Carbon\Carbon;

class LecturerAttendanceController extends Controller
{
    /**
     * Get attendance statistics by member_id.
     *
     * @param string $memberId
     * @return \Illuminate\View\View
     */
    public function getAttendanceStatsByMemberId($memberId)
    {
        $user = User::with(['pointSummary', 'userAchievements'])->where('member_id', $memberId)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
        }

        $attendances = $user->attendances()->orderBy('checkin_time', 'asc')->get();

        // Basic stats
        $totalDurationMinutes = $attendances->sum('total_daily_duration');
        $sessionCount = $attendances->count();
        $totalPoints = $user->pointSummary->total_points ?? 0;

        // Earliest & latest check-in by time-of-day
        $earliestRecord = $attendances->sortBy(fn($a) => Carbon::parse($a->checkin_time)->format('H:i:s'))->first();
        $latestRecord = $attendances->sortByDesc(fn($a) => Carbon::parse($a->checkin_time)->format('H:i:s'))->first();

        // Duration extremes
        $completedSessions = $attendances->where('total_daily_duration', '>', 0);
        $longestDurationMin = $completedSessions->max('total_daily_duration') ?? 0;
        $shortestDurationMin = $completedSessions->min('total_daily_duration') ?? 0;

        // Morning person count
        $morningPersonCount = $user->userAchievements()->where('achievement_id', 1)->count();

        // Favorite location
        $locationGroups = $attendances->groupBy('attendance_location_id');
        $maxLocation = null;
        foreach ($locationGroups as $locId => $locAtts) {
            $dur = $locAtts->sum('total_daily_duration');
            if (!$maxLocation || $dur > $maxLocation['duration']) {
                $loc = AttendanceLocation::find($locId);
                $maxLocation = ['name' => $loc?->name ?? 'Unknown', 'duration' => $dur];
            }
        }

        // Streak calculation
        $uniqueDates = $attendances->map(fn($a) => Carbon::parse($a->checkin_time)->toDateString())->unique()->sort()->values();
        $maxStreak = 0; $currentStreak = 1;
        for ($i = 1; $i < $uniqueDates->count(); $i++) {
            if (Carbon::parse($uniqueDates[$i])->diffInDays(Carbon::parse($uniqueDates[$i - 1])) === 1) {
                $currentStreak++;
            } else {
                $currentStreak = 1;
            }
            $maxStreak = max($maxStreak, $currentStreak);
        }
        if ($uniqueDates->count() === 1) $maxStreak = 1;

        // First ever attendance
        $firstAttendance = $attendances->first();
        $firstDate = $firstAttendance ? Carbon::parse($firstAttendance->checkin_time)->isoFormat('D MMMM YYYY') : null;

        // Level info
        $levels = \App\Models\Level::orderBy('minimum_points', 'asc')->get();
        $currentLevel = $levels->last(fn($l) => $totalPoints >= $l->minimum_points);
        $currentLevelIndex = $currentLevel ? $levels->search(fn($l) => $l->id === $currentLevel->id) : 0;
        $nextLevel = $levels->get($currentLevelIndex + 1);

        // All level images for slide backgrounds
        $levelImages = $levels->filter(fn($l) => $l->image_url)->pluck('image_url')->map(fn($img) => asset($img))->values()->toArray();

        // Extra stats
        $totalLocations = $locationGroups->count();
        $totalAchievements = $user->userAchievements()->count();
        $avgCheckinTime = $attendances->count() > 0
            ? gmdate('H:i', (int) $attendances->avg(fn($a) => Carbon::parse($a->checkin_time)->secondsSinceMidnight()))
            : null;

        $stats = [
            'user_name' => $user->name,
            'user_avatar' => $user->avatar,
            'total_duration' => $this->formatDuration($totalDurationMinutes),
            'total_duration_minutes' => $totalDurationMinutes,
            'session_count' => $sessionCount,
            'total_points' => $totalPoints,
            'earliest_checkin' => $earliestRecord ? Carbon::parse($earliestRecord->checkin_time)->isoFormat('dddd, D MMMM YYYY HH:mm') : null,
            'latest_checkin' => $latestRecord ? Carbon::parse($latestRecord->checkin_time)->isoFormat('dddd, D MMMM YYYY HH:mm') : null,
            'longest_duration' => $this->formatDuration($longestDurationMin),
            'shortest_duration' => $shortestDurationMin > 0 ? $this->formatDuration($shortestDurationMin) : null,
            'morning_person_count' => $morningPersonCount,
            'favorite_location' => $maxLocation ? ['name' => $maxLocation['name'], 'duration' => $this->formatDuration($maxLocation['duration'])] : null,
            'max_streak' => $maxStreak,
            'first_date' => $firstDate,
            'unique_days' => $uniqueDates->count(),
            'level_name' => $currentLevel?->name ?? 'Pioneer',
            'level_number' => $currentLevelIndex + 1,
            'level_image' => $currentLevel?->image_url ? asset($currentLevel->image_url) : null,
            'next_level_name' => $nextLevel?->name,
            'next_level_points' => $nextLevel?->minimum_points,
            'points_to_next' => $nextLevel ? max(0, $nextLevel->minimum_points - $totalPoints) : 0,
            'level_images' => $levelImages,
            'total_locations' => $totalLocations,
            'total_achievements' => $totalAchievements,
            'avg_checkin_time' => $avgCheckinTime,
        ];

        return view('journey', compact('stats'));
    }

    /**
     * Format duration in minutes to "X hours Y minutes" format.
     *
     * @param int $minutes
     * @return string
     */
    private function formatDuration($minutes)
    {
        if ($minutes < 60) {
            return "{$minutes} minutes";
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return "{$hours} hours";
        }

        return "{$hours} hours {$remainingMinutes} minutes";
    }

}