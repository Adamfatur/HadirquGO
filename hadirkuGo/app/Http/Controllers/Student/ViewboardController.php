<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLeaderboard;
use App\Models\LocationLeaderboard;
use App\Models\TeamLeaderboard;
use App\Models\Level;
use App\Models\User;
use App\Models\UserPointSummary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ViewboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $activeTab = $request->get('tab', 'sessions');

        $rankings = [
            'daily' => UserLeaderboard::with('user')->where('category', 'top_duration_daily')->orderBy('current_rank', 'asc')->get(),
            'weekly' => UserLeaderboard::with('user')->where('category', 'top_duration_weekly')->orderBy('current_rank', 'asc')->get(),
            'monthly' => UserLeaderboard::with('user')->where('category', 'top_duration_monthly')->orderBy('current_rank', 'asc')->get(),
            'yearly' => UserLeaderboard::with('user')->where('category', 'top_duration_yearly')->orderBy('current_rank', 'asc')->get(),
        ];

        return view('student.viewboard.index', compact('rankings', 'period', 'activeTab'));
    }

    public function topSessions(Request $request)
    {
        $period = $request->get('period', 'weekly');
        $rankings = UserLeaderboard::with('user')
            ->where('category', "top_sessions_$period")
            ->orderBy('current_rank', 'asc')
            ->get();
        return view('student.viewboard.top_sessions', compact('rankings', 'period'));
    }

    public function topDuration(Request $request)
    {
        $period = $request->get('period', 'weekly');
        $rankings = UserLeaderboard::with('user')
            ->where('category', "top_duration_$period")
            ->orderBy('current_rank', 'asc')
            ->get();
        return view('student.viewboard.top_duration', compact('rankings', 'period'));
    }

    public function topLocations(Request $request)
    {
        $period = $request->get('period', 'weekly');
        $topLocations = LocationLeaderboard::with('attendanceLocation')
            ->where('category', "top_locations_$period")
            ->orderBy('current_rank', 'asc')
            ->get();
        return view('student.viewboard.top_locations', compact('topLocations', 'period'));
    }

    public function topPoints()
    {
        $user = Auth::user();
        $rankings = UserLeaderboard::with('user')
            ->where('category', 'top_points')
            ->orderBy('current_rank', 'asc')
            ->get();

        // Check if user is in top 50
        $userRank = UserLeaderboard::where('category', 'top_points')
            ->where('user_id', $user->id)
            ->first();

        if (!$userRank) {
            $totalPts = $user->pointSummary->total_points ?? 0;
            $rank = UserPointSummary::where('total_points', '>', $totalPts)->count() + 1;
            $userRank = (object)[
                'current_rank' => $rank,
                'score' => $totalPts,
                'is_outside' => true
            ];
        }

        return view('student.viewboard.top_points', compact('rankings', 'userRank'));
    }

    public function topLevels()
    {
        $user = Auth::user();
        $rankings = UserLeaderboard::with('user')
            ->where('category', 'top_levels')
            ->orderBy('current_rank', 'asc')
            ->get();

        $rankings->each(function ($item) {
            $level = Level::where('minimum_points', '<=', $item->score)
                ->where('maximum_points', '>=', $item->score)
                ->first();
            $item->level = $level ? $level->name : 'No Level';
            $item->level_image = $level ? $level->image_url : null;
        });

        // Check if user is in top 50
        $userRank = UserLeaderboard::where('category', 'top_levels')
            ->where('user_id', $user->id)
            ->first();

        if (!$userRank) {
            $totalPts = $user->pointSummary->total_points ?? 0;
            $rank = UserPointSummary::where('total_points', '>', $totalPts)->count() + 1;
            $userRank = (object)[
                'current_rank' => $rank,
                'score' => $totalPts,
                'is_outside' => true
            ];
        }

        return view('student.viewboard.top_levels', compact('rankings', 'userRank'));
    }

    public function topTeams(Request $request)
    {
        $period = $request->get('period', 'weekly');
        $teamRankings = TeamLeaderboard::with('team')
            ->where('category', "top_teams_$period")
            ->orderBy('current_rank', 'asc')
            ->get();
        return view('student.viewboard.top_teams', compact('teamRankings', 'period'));
    }

    public function searchRanking(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', 'top_levels');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Whitelist allowed categories
        $allowed = ['top_levels', 'top_points', 'top_sessions_daily', 'top_sessions_weekly',
            'top_sessions_monthly', 'top_sessions_yearly', 'top_duration_daily', 'top_duration_weekly',
            'top_duration_monthly', 'top_duration_yearly'];
        if (!in_array($category, $allowed)) {
            return response()->json([]);
        }

        $isDuration = str_contains($category, 'duration');

        // Search users by name, then check their leaderboard entry
        $users = User::where('name', 'like', '%' . $query . '%')
            ->select('id', 'name', 'avatar', 'member_id')
            ->limit(10)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([]);
        }

        $userIds = $users->pluck('id');

        // Get leaderboard entries for these users in this category
        $leaderboardEntries = UserLeaderboard::where('category', $category)
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $results = $users->map(function ($user) use ($leaderboardEntries, $category, $isDuration) {
            $entry = $leaderboardEntries->get($user->id);

            if ($entry) {
                $rank = $entry->current_rank;
                $score = $entry->score;
            } else {
                // User not in leaderboard for this category — calculate approximate rank
                $totalPts = $user->pointSummary->total_points ?? 0;
                $rank = UserPointSummary::where('total_points', '>', $totalPts)->count() + 1;
                $score = $totalPts;
            }

            $scoreDisplay = null;
            if ($isDuration) {
                $h = intdiv((int)$score, 60);
                $m = (int)$score % 60;
                $scoreDisplay = ($h > 0 ? $h . 'h ' : '') . $m . 'm';
            }

            return [
                'name' => $user->name,
                'avatar' => $user->avatar,
                'member_id' => $user->member_id,
                'rank' => $rank,
                'score' => $score,
                'score_display' => $scoreDisplay,
                'in_leaderboard' => $entry !== null,
            ];
        })->sortBy('rank')->values();

        return response()->json($results);
    }
}
