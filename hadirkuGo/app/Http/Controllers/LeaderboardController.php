<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Show the leaderboard with user rankings and details
     */
    public function index(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Get the business ID from the user's joined teams
        $businessId = $user->teamsJoined()->pluck('business_id')->first();

        // If no business is found for the user, return error
        if (!$businessId) {
            return back()->withErrors(['error' => 'No business found for the user.']);
        }

        // Determine the period for filtering (daily, weekly, monthly, etc.)
        $period = $request->input('period', 'weekly'); // Default to weekly ranking
        $dateRange = $this->getDateRange($period, $request);

        // Fetch all users in the same business, including their points, attendance count, and total daily duration
        $users = User::whereHas('teamsJoined', function ($query) use ($businessId) {
            $query->where('business_id', $businessId);
        })
            ->with(['teamsJoined' => function ($query) use ($businessId) {
                $query->where('business_id', $businessId);
            }])
            ->withCount(['attendances' => function ($query) use ($dateRange) {
                $query->whereBetween('created_at', $dateRange); // Apply date range filter for attendance count
            }])
            ->get()
            ->map(function ($user) use ($dateRange) {
                // Get highest achievement across all teams of the user
                $highestAchievement = $user->teamsJoined->map(function ($team) use ($user, $dateRange) {
                    // Calculate total points and attendance count for each team
                    $totalPoints = $team->attendances()->where('user_id', $user->id)->sum('points');
                    $attendanceCount = $team->attendances()->where('user_id', $user->id)->count();

                    // Sum the total_daily_duration across all attendances for the given team and user
                    $totalDailyDuration = $team->attendances()
                        ->where('user_id', $user->id)
                        ->whereNotNull('total_daily_duration')  // Ensure we don't count null values
                        ->sum('total_daily_duration');  // Sum the total duration

                    return [
                        'team_name' => $team->name,
                        'total_points' => $totalPoints,
                        'attendance_count' => $attendanceCount,
                        'total_daily_duration' => $totalDailyDuration,  // Store the total daily duration
                    ];
                })->sortByDesc('total_points')->first();  // Get the highest achievement (based on points)

                return [
                    'user' => $user,
                    'team_name' => $highestAchievement['team_name'] ?? 'N/A',  // Team name (if available)
                    'total_points' => $highestAchievement['total_points'] ?? 0,  // Total points
                    'attendance_count' => $highestAchievement['attendance_count'] ?? 0,  // Attendance count
                    'total_duration' => $this->formatDuration($highestAchievement['total_daily_duration'] ?? 0), // Format total duration
                ];
            })->sortByDesc('total_points'); // Sort users by total points in descending order

        // Return the leaderboard view with the users data and selected period
        return view('leaderboard.index', compact('users', 'period'));
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
    private function getDateRange($period, $request)
    {
        // Get today's date using Carbon
        $today = Carbon::today();

        // Determine the start and end dates based on the period
        switch ($period) {
            case 'daily':
                return [$today->startOfDay(), $today->endOfDay()];
            case 'weekly':
                return [$today->startOfWeek(), $today->endOfWeek()];
            case 'monthly':
                return [$today->startOfMonth(), $today->endOfMonth()];
            case 'yearly':
                return [$today->startOfYear(), $today->endOfYear()];
            case 'custom':
                // If custom period is selected, get the dates from the request
                $startDate = Carbon::parse($request->input('start_date'));
                $endDate = Carbon::parse($request->input('end_date'));
                return [$startDate, $endDate];
            default:
                // Default to a very old range (2000-01-01 to today) if period is not recognized
                return ['2000-01-01', Carbon::now()];
        }
    }
	
	public function index2025()
    {
        // 10 Besar Kehadiran (Hari Unik)
        $topAttendance = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(DISTINCT DATE(attendances.checkin_time)) as total_days'))
            ->whereYear('attendances.checkin_time', 2025)
            ->groupBy('attendances.user_id', 'users.name')
            ->orderBy('total_days', 'desc')
            ->limit(10)
            ->get();

        // 10 Besar Jam Kerja (Total Durasi)
        $topDuration = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(attendances.duration_at_location) as total_duration'))
            ->whereYear('attendances.checkin_time', 2025)
            ->where('attendances.type', 'checkout') // Biasanya durasi ada di record checkout
            ->groupBy('attendances.user_id', 'users.name')
            ->orderBy('total_duration', 'desc')
            ->limit(10)
            ->get();

        // Convert total_duration from minutes to hours
        $topDuration = $topDuration->map(function ($item) {
            $item->total_hours = round($item->total_duration / 60, 2);
            return $item;
        });

        return view('leaderboard-2025', compact('topAttendance', 'topDuration'));
    }
}
