<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Carbon\Carbon;
use App\Models\Attendance;

class TeamMemberRankingController extends Controller
{
    /**
     * Menampilkan daftar tim dengan total member dan total jam.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mengambil semua tim dengan relasi leader, members, dan managers
        $teams = Team::with(['leader', 'members', 'managers'])->get();

        // Menghitung total member, total poin, dan total durasi untuk setiap tim
        $teamData = $teams->map(function ($team) {
            // Menggabungkan leader, members, dan managers
            $users = $team->members->merge([$team->leader])->merge($team->managers);

            // Menghitung total durasi dalam menit (all-time)
            $totalDuration = $users->sum(function ($user) {
                return $user->attendances()->sum('duration_at_location');
            });

            // Menghitung total poin dari UserPointSummary (all-time)
            $totalPoints = $users->sum(function ($user) {
                return $user->pointSummary->total_points ?? 0; // Ambil total_points dari UserPointSummary
            });

            // Konversi total durasi ke jam dan menit
            $hours = floor($totalDuration / 60);
            $minutes = $totalDuration % 60;

            return [
                'team' => $team,
                'total_members' => $users->count(),
                'total_duration' => $totalDuration,
                'total_duration_formatted' => "{$hours}h {$minutes}m",
                'total_points' => $totalPoints, // Total poin dari UserPointSummary
            ];
        });

        // Mengurutkan tim berdasarkan total poin (descending), kemudian total durasi (descending)
        $teamData = $teamData->sortByDesc('total_points')
            ->sortByDesc('total_duration')
            ->values();

        return view('lecturer.team_rank.index', compact('teamData'));
    }

    /**
     * Menampilkan daftar ranking member (termasuk leader dan managers) dalam sebuah tim.
     * Data yang ditampilkan hanya all-time.
     *
     * @param Request $request
     * @param string $teamUniqueId ID unik tim yang dipilih.
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $teamUniqueId)
    {
        // Mengambil tim berdasarkan team_unique_id
        $team = Team::with([
            'leader.leaderboards' => function($q) { $q->where('category', 'top_levels'); },
            'members.leaderboards' => function($q) { $q->where('category', 'top_levels'); },
            'managers.leaderboards' => function($q) { $q->where('category', 'top_levels'); }
        ])
            ->where('team_unique_id', $teamUniqueId)
            ->firstOrFail();

        // Menggabungkan leader, members, dan managers
        $users = $team->members->merge([$team->leader])->merge($team->managers);

        // Menghitung total durasi untuk setiap user (All-Time)
        $memberRankings = $users->map(function ($user) {
            $totalDuration = $user->attendances()
                ->sum('duration_at_location');

            // Get global rank info
            $globalEntry = $user->leaderboards->first();

            return [
                'user' => $user,
                'total_duration' => $totalDuration,
                'global_entry' => $globalEntry
            ];
        });

        // Mengurutkan member berdasarkan total durasi secara descending
        $memberRankings = $memberRankings->sortByDesc('total_duration')->values();

        // Menambahkan ranking
        $rank = 1;
        $memberRankings = $memberRankings->map(function ($ranking) use (&$rank) {
            $ranking['rank'] = $rank++;
            return $ranking;
        });

        // Menghitung total durasi tim keseluruhan (all-time)
        $totalDurationAllTime = $users->sum(function ($user) {
            return $user->attendances()->sum('duration_at_location');
        });

        // Menghitung total poin tim keseluruhan (all-time)
        $totalPointsAllTime = $users->sum(function ($user) {
            return $user->pointSummary->total_points ?? 0;
        });

        // Menghitung total member
        $totalMembers = $users->count();

        // Menghitung total durasi per hari
        $dailyDurations = $users->map(function ($user) {
            return $user->attendances()
                ->selectRaw('DATE(checkin_time) as date, SUM(duration_at_location) as total_duration')
                ->groupBy('date')
                ->get();
        })->flatten()->groupBy('date')->map(function ($group) {
            return $group->sum('total_duration');
        });

        // Mengurutkan dailyDurations berdasarkan tanggal secara ascending
        $dailyDurations = $dailyDurations->sortBy(function ($value, $key) {
            return $key; // Urutkan berdasarkan tanggal (key)
        });

        // Set period untuk template (all-time)
        $period = 'all-time';

        return view('lecturer.team_rank.details', compact(
            'team',
            'memberRankings',
            'period',
            'totalDurationAllTime',
            'totalPointsAllTime',
            'totalMembers',
            'dailyDurations'
        ));
    }
}
