<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\UserPointSummary;
use App\Models\Level;
use App\Models\UserLeaderboard;
use App\Models\LocationLeaderboard;
use App\Models\TeamLeaderboard;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateLeaderboardsCommand extends Command
{
    protected $signature = 'leaderboard:update';
    protected $description = 'Update leaderboard cache tables and rank movements every hour';

    public function handle()
    {
        $this->info('Starting leaderboard update...');

        $this->updateTopPoints();
        $this->updateTopLevels();

        $periods = ['daily', 'weekly', 'monthly', 'yearly'];

        foreach ($periods as $period) {
            $this->info("Updating $period rankings...");
            $dateRange = $this->getDateRange($period);

            $this->updateTopSessions($period, $dateRange);
            $this->updateTopDuration($period, $dateRange);
            $this->updateTopLocations($period, $dateRange);
            $this->updateTopTeams($period, $dateRange);
        }

        $this->info('Leaderboard update completed successfully!');
        return 0;
    }

    private function updateTopPoints()
    {
        $data = UserPointSummary::whereHas('user')
            ->select('user_id', DB::raw('SUM(total_points) as total_points'))
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->limit(50)
            ->get();

        $this->syncUserLeaderboard('top_points', $data, 'user_id', 'total_points');
    }

    private function updateTopLevels()
    {
        $data = UserPointSummary::whereHas('user')
            ->select('user_id', DB::raw('SUM(total_points) as total_points'))
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->limit(50)
            ->get();

        $this->syncUserLeaderboard('top_levels', $data, 'user_id', 'total_points');
    }

    private function updateTopSessions($period, $dateRange)
    {
        $data = Attendance::whereBetween('checkin_time', $dateRange)
            ->whereHas('user')
            ->select('user_id', DB::raw('COUNT(*) as session_count'))
            ->groupBy('user_id')
            ->orderByDesc('session_count')
            ->limit(50)
            ->get();

        $this->syncUserLeaderboard("top_sessions_$period", $data, 'user_id', 'session_count');
    }

    private function updateTopDuration($period, $dateRange)
    {
        $data = Attendance::whereBetween('checkin_time', $dateRange)
            ->whereHas('user')
            ->select(
                'user_id',
                DB::raw('SUM(duration_at_location) as total_duration'),
                DB::raw('COUNT(*) as session_count'),
                DB::raw('COUNT(DISTINCT attendance_location_id) as unique_locations')
            )
            ->groupBy('user_id')
            ->orderByDesc('total_duration')
            ->limit(50)
            ->get();

        $this->syncUserLeaderboard("top_duration_$period", $data, 'user_id', 'total_duration', 'session_count', 'unique_locations');
    }

    private function updateTopLocations($period, $dateRange)
    {
        $data = Attendance::whereBetween('checkin_time', $dateRange)
            ->whereHas('attendanceLocation')
            ->select(
                'attendance_location_id',
                DB::raw('COUNT(*) as visit_count'),
                DB::raw('SUM(duration_at_location) as total_duration')
            )
            ->groupBy('attendance_location_id')
            ->orderByDesc('visit_count')
            ->limit(50)
            ->get();

        $this->syncLocationLeaderboard("top_locations_$period", $data, 'attendance_location_id', 'visit_count', 'total_duration');
    }

    private function updateTopTeams($period, $dateRange)
    {
        $teams = Team::with('members')->get();
        $teamData = $teams->map(function ($team) use ($dateRange) {
            $userIds = $team->members->pluck('id');
            $totalDuration = Attendance::whereIn('user_id', $userIds)
                ->whereBetween('checkin_time', $dateRange)
                ->sum('duration_at_location');
            
            return (object) [
                'team_id' => $team->id,
                'total_duration' => $totalDuration
            ];
        })->sortByDesc('total_duration')->values()->take(50);

        $this->syncTeamLeaderboard("top_teams_$period", $teamData, 'team_id', 'total_duration');
    }

    private function syncUserLeaderboard($category, $data, $idField, $scoreField, $secondaryField = null, $thirdField = null)
    {
        $existing = UserLeaderboard::where('category', $category)->pluck('current_rank', 'user_id')->toArray();
        $processedIds = [];

        foreach ($data as $index => $item) {
            $rank = $index + 1;
            $userId = $item->$idField;
            $processedIds[] = $userId;

            $previousRank = isset($existing[$userId]) ? $existing[$userId] : null;
            
            $title = null;
            if ($rank == 1) {
                $title = 'Supreme Champion';
            } elseif ($rank == 2) {
                $title = 'Elite Grandmaster';
            } elseif ($rank == 3) {
                $title = 'Grandmaster';
            } elseif ($rank >= 4 && $rank <= 5) {
                $title = 'Master Elite';
            } elseif ($rank >= 6 && $rank <= 10) {
                $title = 'Renowned Expert';
            } elseif ($rank >= 11 && $rank <= 20) {
                $title = 'Rising Star';
            } elseif ($rank >= 21 && $rank <= 50) {
                $title = 'Honored Contender';
            }

            UserLeaderboard::updateOrCreate(
                ['category' => $category, 'user_id' => $userId],
                [
                    'score' => $item->$scoreField,
                    'secondary_score' => $secondaryField ? $item->$secondaryField : null,
                    'third_score' => $thirdField ? $item->$thirdField : null,
                    'current_rank' => $rank,
                    'previous_rank' => $previousRank,
                    'title' => $title,
                ]
            );
        }

        UserLeaderboard::where('category', $category)->whereNotIn('user_id', $processedIds)->delete();
    }

    private function syncLocationLeaderboard($category, $data, $idField, $scoreField, $secondaryField = null)
    {
        $existing = LocationLeaderboard::where('category', $category)->pluck('current_rank', 'attendance_location_id')->toArray();
        $processedIds = [];

        foreach ($data as $index => $item) {
            $rank = $index + 1;
            $locId = $item->$idField;
            $processedIds[] = $locId;

            $previousRank = isset($existing[$locId]) ? $existing[$locId] : null;

            LocationLeaderboard::updateOrCreate(
                ['category' => $category, 'attendance_location_id' => $locId],
                [
                    'score' => $item->$scoreField,
                    'secondary_score' => $secondaryField ? $item->$secondaryField : null,
                    'current_rank' => $rank,
                    'previous_rank' => $previousRank,
                ]
            );
        }

        LocationLeaderboard::where('category', $category)->whereNotIn('attendance_location_id', $processedIds)->delete();
    }

    private function syncTeamLeaderboard($category, $data, $idField, $scoreField)
    {
        $existing = TeamLeaderboard::where('category', $category)->pluck('current_rank', 'team_id')->toArray();
        $processedIds = [];

        foreach ($data as $index => $item) {
            $rank = $index + 1;
            $teamId = $item->$idField;
            $processedIds[] = $teamId;

            $previousRank = isset($existing[$teamId]) ? $existing[$teamId] : null;

            TeamLeaderboard::updateOrCreate(
                ['category' => $category, 'team_id' => $teamId],
                [
                    'score' => $item->$scoreField,
                    'current_rank' => $rank,
                    'previous_rank' => $previousRank,
                ]
            );
        }

        TeamLeaderboard::where('category', $category)->whereNotIn('team_id', $processedIds)->delete();
    }

    protected function getDateRange($period)
    {
        $now = Carbon::now();
        switch ($period) {
            case 'weekly': return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
            case 'monthly': return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'yearly': return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            default: return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
        }
    }
}
