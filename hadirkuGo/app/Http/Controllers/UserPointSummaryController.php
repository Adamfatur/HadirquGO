<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use App\Models\UserPointSummary;
use App\Models\UserTeamPointSummary;
use Illuminate\Support\Facades\Log;

class UserPointSummaryController extends Controller
{
    /**
     * Update user points summary.
     *
     * @return void
     */
    public function updateUserPoints()
    {
        // Ambil semua data user points
        $userPoints = UserPoint::all();

        Log::info("Updating user points summaries...");

        // Grup poin berdasarkan user_id
        $groupedByUser = $userPoints->groupBy('user_id');

        foreach ($groupedByUser as $user_id => $points) {
            // Total poin hanya menghitung poin yang bertambah (>= 0)
            $totalPoints = $points->where('points', '>', 0)->sum('points');

            // Hitung total poin yang sudah dikurangi
            $pointsUsed = abs($points->where('points', '<', 0)->sum('points'));

            // Current points adalah total poin dikurangi poin yang digunakan
            $currentPoints = $totalPoints - $pointsUsed;

            // Update atau buat ringkasan poin untuk user
            UserPointSummary::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'current_points' => $currentPoints,
                    'total_points'   => $totalPoints,
                ]
            );
        }

        Log::info("User points summaries updated.");
    }

    /**
     * Update points for a user by adding or subtracting points.
     *
     * @param int $user_id
     * @param int $points
     * @param bool $add
     * @return void
     */
    public function updateUserPointsById($user_id, $points, $add = true)
    {
        $summary = UserPointSummary::firstOrCreate(
            ['user_id' => $user_id],
            ['current_points' => 0, 'total_points' => 0]
        );

        if ($add) {
            // Tambahkan poin ke total dan current
            $summary->increment('total_points', $points);
            $summary->increment('current_points', $points);
        } else {
            // Kurangi poin dari current
            $summary->decrement('current_points', $points);
        }

        return response()->json(['status' => 'success', 'message' => 'User points updated']);
    }

    /**
     * Update points for a user in a specific team.
     *
     * @param int $user_id
     * @param int $team_id
     * @param int $points
     * @param bool $add
     * @return void
     */
    public function updateUserPointsInTeam($user_id, $team_id, $points, $add = true)
    {
        $teamSummary = UserTeamPointSummary::firstOrCreate(
            ['user_id' => $user_id, 'team_id' => $team_id],
            ['total_team_points' => 0, 'current_team_points' => 0]
        );

        if ($add) {
            // Tambahkan poin ke total dan current
            $teamSummary->increment('total_team_points', $points);
            $teamSummary->increment('current_team_points', $points);
        } else {
            // Kurangi poin dari current
            $teamSummary->decrement('current_team_points', $points);
        }

        return response()->json(['status' => 'success', 'message' => 'User team points updated']);
    }
}
