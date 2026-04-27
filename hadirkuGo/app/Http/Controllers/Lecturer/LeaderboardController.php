<?php

namespace App\Http\Controllers\Lecturer;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Team;

class LeaderboardController extends Controller
{
    /**
     * Show the leaderboard for all roles (Students and Lecturers)
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the business IDs from the user's joined and led teams
        $businessIdsJoined = $user->teamsJoined()->pluck('business_id')->unique();
        $businessIdsLed = $user->teamsLed()->pluck('business_id')->unique();
        $businessId = $businessIdsJoined->merge($businessIdsLed)->first();

        // If no business is found for the user, return error
        if (!$businessId) {
            return back()->withErrors(['error' => 'No business found for the user.']);
        }

        // Determine the period for filtering (daily, weekly, monthly, etc.)
        $period = $request->input('period', 'weekly'); // Default to weekly ranking
        $dateRange = $this->getDateRange($period);

        // Fetch all users associated with the business (either in teamsLed or teamsJoined)
        $users = User::whereHas('teamsJoined', function ($query) use ($businessId) {
            $query->where('business_id', $businessId);
        })
            ->orWhereHas('teamsLed', function ($query) use ($businessId) {
                $query->where('business_id', $businessId);
            })
            ->with([
                'teamsJoined' => function ($query) use ($businessId) {
                    $query->where('business_id', $businessId);
                }, 
                'teamsLed' => function ($query) use ($businessId) {
                    $query->where('business_id', $businessId);
                },
                'leaderboards' => function ($query) {
                    $query->where('category', 'top_points');
                }
            ])
            ->get()

            ->map(function ($user) use ($dateRange) {
                // Determine the role of the user
                $role = $user->hasRole('lecturer') ? 'Lecturer' : 'Student';

                // Collect all teams (led and joined) for the user
                $allTeams = $user->teamsLed->merge($user->teamsJoined);

                // Initialize total points and other metrics
                $totalPoints = 0;
                $attendanceCount = 0;
                $totalDuration = 0;
                $teamPoints = []; // To store points per team

                foreach ($allTeams as $team) {
                    $achievement = $this->calculateTeamAchievement($team, $user, $dateRange);
                    $totalPoints += $achievement['total_points'];
                    $attendanceCount += $achievement['attendance_count'];
                    $totalDuration += $achievement['total_daily_duration'];

                    // Store points per team
                    $teamPoints[$team->name] = $achievement['total_points'];
                }

                // Find the team where the user got the highest points
                if (!empty($teamPoints)) {
                    arsort($teamPoints); // Sort teams by points descending
                    $teamName = array_key_first($teamPoints);
                } else {
                    $teamName = 'N/A';
                }

                return [
                    'user' => $user,
                    'role' => $role,
                    'team_name' => $teamName,
                    'total_points' => $totalPoints,
                    'attendance_count' => $attendanceCount,
                    'total_duration' => $totalDuration, // Keep as integer for sorting
                    'formatted_total_duration' => $this->formatDuration($totalDuration),
                ];
            })
            ->filter(function ($userData) {
                // Include users with total_points >= 0 to include users with zero points
                return $userData['total_points'] >= 0;
            })
            ->sort(function ($a, $b) {
                // Sort by total_points descending
                if ($a['total_points'] === $b['total_points']) {
                    // If total_points are equal, sort by attendance_count descending
                    if ($a['attendance_count'] === $b['attendance_count']) {
                        // If attendance_count is also equal, sort by total_duration descending
                        return $b['total_duration'] <=> $a['total_duration'];
                    }
                    return $b['attendance_count'] <=> $a['attendance_count'];
                }
                return $b['total_points'] <=> $a['total_points'];
            })
            ->values(); // Reindex the collection

        // Return the leaderboard view with the users data and selected period
        return view('lecturer.leaderboard.index', compact('users', 'period'));
    }

    /**
     * Show the team leaderboard for the student role
     */
    public function teamRanking(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the business ID from the user's joined and led teams
        $businessIdsJoined = $user->teamsJoined()->pluck('business_id')->unique();
        $businessIdsLed = $user->teamsLed()->pluck('business_id')->unique();
        $businessId = $businessIdsJoined->merge($businessIdsLed)->first();

        // If no business is found for the user, return error
        if (!$businessId) {
            return back()->withErrors(['error' => 'No business found for the user.']);
        }

        // Calculate the date for 3 months ago from today
        $threeMonthsAgo = now()->subMonths(3);

        // Fetch all teams in the same business, including their total points, attendance count, and total daily duration
        $teams = Team::where('business_id', $businessId)
            ->with(['attendances' => function ($query) use ($threeMonthsAgo) {
                // Filter attendance records for the last 3 months
                $query->where('created_at', '>=', $threeMonthsAgo);
            }])
            ->get()
            ->map(function ($team) {
                // Calculate total points for each team within the 3-month range
                $totalPoints = $team->attendances->sum('points');

                // Attendance count (total number of attendances for the team)
                $attendanceCount = $team->attendances->count();

                // Total duration for the team within the 3-month range in minutes
                $totalDurationInMinutes = $team->attendances->whereNotNull('total_daily_duration')->sum('total_daily_duration');

                return [
                    'team_name' => $team->name,
                    'total_points' => $totalPoints,
                    'attendance_count' => $attendanceCount,
                    'total_duration_in_minutes' => $totalDurationInMinutes,
                    'formatted_total_duration' => $this->formatDuration($totalDurationInMinutes),
                ];
            })
            ->filter(function ($team) {
                // Include teams with total_points >= 0
                return $team['total_points'] >= 0;
            })
            ->sort(function ($a, $b) {
                // Sort by total_points descending
                if ($a['total_points'] === $b['total_points']) {
                    // If total_points are equal, sort by attendance_count descending
                    if ($a['attendance_count'] === $b['attendance_count']) {
                        // If attendance_count is also equal, sort by total_duration_in_minutes descending
                        return $b['total_duration_in_minutes'] <=> $a['total_duration_in_minutes'];
                    }
                    return $b['attendance_count'] <=> $a['attendance_count'];
                }
                return $b['total_points'] <=> $a['total_points'];
            })
            ->values(); // Reindex the collection

        // Return the leaderboard view with the teams data
        return view('lecturer.leaderboard.team', compact('teams'));
    }

    /**
     * Calculate team achievement for a user within a specific team
     */
    private function calculateTeamAchievement(Team $team, User $user, array $dateRange)
    {
        // Calculate total points for the user in the team within the date range
        $totalPoints = $team->attendances()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', $dateRange)
            ->sum('points');

        // Calculate attendance count for the user in the team within the date range
        $attendanceCount = $team->attendances()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', $dateRange)
            ->count();

        // Calculate total daily duration for the user in the team within the date range
        $totalDailyDuration = $team->attendances()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', $dateRange)
            ->whereNotNull('total_daily_duration') // Ensure we don't count null values
            ->sum('total_daily_duration'); // Sum the total duration in minutes

        return [
            'total_points' => $totalPoints,
            'attendance_count' => $attendanceCount,
            'total_daily_duration' => $totalDailyDuration, // Total duration in minutes
        ];
    }

    /**
     * Format total duration (in minutes) into "X hours Y minutes"
     */
    private function formatDuration($totalDurationInMinutes)
    {
        // Calculate hours and minutes from the total duration in minutes
        $hours = intdiv($totalDurationInMinutes, 60);
        $minutes = $totalDurationInMinutes % 60;

        // Return the formatted duration string
        return sprintf('%d hours %d minutes', $hours, $minutes);
    }

    /**
     * Get the date range based on the selected period (daily, weekly, monthly, etc.)
     */
    private function getDateRange(string $period): array
    {
        $now = now();

        switch ($period) {
            case 'daily':
                // Daily range: Start and end of today
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];

            case 'weekly':
                // Weekly range: From Monday of this week to Sunday of this week
                return [$now->copy()->startOfWeek(Carbon::MONDAY), $now->copy()->endOfWeek(Carbon::SUNDAY)];

            case 'monthly':
                // Monthly range: From the start of this month to the end of this month
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];

            case 'yearly':
                // Yearly range: From the start of this year to the end of this year
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];

            default:
                // Default to weekly if period is unknown
                return [$now->copy()->startOfWeek(Carbon::MONDAY), $now->copy()->endOfWeek(Carbon::SUNDAY)];
        }
    }
}
