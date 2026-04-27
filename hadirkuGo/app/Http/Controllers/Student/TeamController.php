<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Attendance;
use App\Models\UserPointSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeamController extends Controller
{
    /**
     * Display a list of teams joined by the student.
     */
    public function index(Request $request)
    {
        $student = Auth::user();
        $query = $student->teamsJoined()->with(['leader', 'members']);

        // Search feature
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query = $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('team_unique_id', 'like', $searchTerm);
            });
        }

        // Paginate results
        $teamsJoined = $query->paginate(10);

        return view('student.teams.index', compact('teamsJoined'));
    }

    /**
     * Display team details based on team_unique_id.
     */
    public function show($teamUniqueId, Request $request)
    {
        // Get the team by team_unique_id
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();

        // Get all members (including leader and managers)
        $members = $team->members;
        $leader = $team->leader;
        $managers = $team->managers;

        // Combine all users (members, leader, and managers)
        $allUsers = $members->merge([$leader])->merge($managers)->unique('id');

        // Calculate attendance durations for each user
        $userDurations = [];
        $totalTeamDuration = 0; // Total durasi semua anggota tim
        $totalTeamPoints = 0;   // Total poin semua anggota tim

        // Get the selected period from the request (default to 'weekly')
        $period = $request->input('period', 'weekly');
        $startDateParam = $request->input('start_date');
        $endDateParam = $request->input('end_date');

        foreach ($allUsers as $user) {
            $periodDuration = $this->getAttendanceDurations($user->id, $period, $startDateParam, $endDateParam);

            // Tambahkan durasi ke total tim
            $totalTeamDuration += $periodDuration;

            // Hitung total poin dari UserPointSummary
            $userPoints = UserPointSummary::where('user_id', $user->id)->first();
            $totalTeamPoints += $userPoints ? $userPoints->total_points : 0;

            $userDurations[] = [
                'user' => $user,
                'duration' => $periodDuration,
            ];
        }

        // Sort the userDurations array based on the selected period
        usort($userDurations, function ($a, $b) {
            return $b['duration'] <=> $a['duration']; // Sort in descending order
        });

        // Determine start and end dates for the view
        $now = Carbon::now();
        switch ($period) {
            case 'daily': $startDate = $now->copy()->startOfDay(); $endDate = $now->copy()->endOfDay(); break;
            case 'weekly': $startDate = $now->copy()->startOfWeek(); $endDate = $now->copy()->endOfWeek(); break;
            case 'monthly': $startDate = $now->copy()->startOfMonth(); $endDate = $now->copy()->endOfMonth(); break;
            case '60_days': $startDate = $now->copy()->subDays(60)->startOfDay(); $endDate = $now->copy()->endOfDay(); break;
            case '90_days': $startDate = $now->copy()->subDays(90)->startOfDay(); $endDate = $now->copy()->endOfDay(); break;
            case '6_months': $startDate = $now->copy()->subMonths(6)->startOfDay(); $endDate = $now->copy()->endOfDay(); break;
            case '1_year': $startDate = $now->copy()->subYear()->startOfDay(); $endDate = $now->copy()->endOfDay(); break;
            case 'custom':
                $startDate = $startDateParam ? Carbon::parse($startDateParam)->startOfDay() : $now->copy()->startOfMonth();
                $endDate = $endDateParam ? Carbon::parse($endDateParam)->endOfDay() : $now->copy()->endOfMonth();
                break;
            default: $startDate = $now->copy()->startOfDay(); $endDate = $now->copy()->endOfDay(); break;
        }

        // Return the view with the required data
        return view('student.teams.show', compact('team','userDurations','period','totalTeamDuration','totalTeamPoints','allUsers', 'startDateParam', 'endDateParam', 'startDate', 'endDate'));
    }

    /**
     * Calculate attendance durations for a specific user and period.
     *
     * @param int $userId The user ID to calculate durations for.
     * @param string $period The period to calculate: daily, weekly, monthly.
     * @return int Total duration in minutes.
     */
    private function getAttendanceDurations($userId, $period = 'daily', $startDateParam = null, $endDateParam = null)
    {
        $now = Carbon::now();

        // Determine the start and end dates based on the period
        switch ($period) {
            case 'daily':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'weekly':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;
            case 'monthly':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case '60_days':
                $startDate = $now->copy()->subDays(60)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '90_days':
                $startDate = $now->copy()->subDays(90)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '6_months':
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '1_year':
                $startDate = $now->copy()->subYear()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'custom':
                $startDate = $startDateParam ? Carbon::parse($startDateParam)->startOfDay() : $now->copy()->startOfMonth();
                $endDate = $endDateParam ? Carbon::parse($endDateParam)->endOfDay() : $now->copy()->endOfMonth();
                break;
            default:
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
        }

        // Get all attendances for the user within the specified period
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('checkin_time', [$startDate, $endDate])
            ->get();

        // Calculate the total duration in minutes
        $totalDuration = $attendances->sum('total_daily_duration');

        // Ensure the total duration is not null
        return $totalDuration ?? 0;
    }
}