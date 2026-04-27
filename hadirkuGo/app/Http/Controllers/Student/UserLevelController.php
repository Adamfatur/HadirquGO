<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserLevel;
use App\Models\Level;
use App\Models\UserPointSummary; // Import model UserPointSummary
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLevelController extends Controller
{
    /**
     * Menampilkan level dan poin pengguna saat ini.
     */
    public function show()
    {
        $user = Auth::user();

        // Ambil data level pengguna
        $userLevel = $user->userLevel;
        $level = $userLevel->level;

        // Ambil total_points dari tabel user_point_summaries
        $userPointSummary = UserPointSummary::where('user_id', $user->id)->first();
        $totalPoints = $userPointSummary ? $userPointSummary->total_points : 0;

        return view('user.level.show', compact('userLevel', 'level', 'totalPoints'));
    }

    /**
     * Update poin pengguna dan periksa apakah levelnya berubah.
     */
    public function updatePoints(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $pointsToAdd = $request->input('points');

        // Update total_points di tabel user_point_summaries
        $userPointSummary = UserPointSummary::where('user_id', $user->id)->first();

        if ($userPointSummary) {
            $userPointSummary->total_points += $pointsToAdd;
            $userPointSummary->save();
        } else {
            // Jika tidak ada record, buat baru
            $userPointSummary = UserPointSummary::create([
                'user_id' => $user->id,
                'total_points' => $pointsToAdd,
                'current_points' => 0, // current_points tidak digunakan di sini
            ]);
        }

        // Cari level baru berdasarkan total_points
        $newLevel = Level::where('minimum_points', '<=', $userPointSummary->total_points)
            ->where('maximum_points', '>=', $userPointSummary->total_points)
            ->first();

        // Jika level berubah, perbarui level_id di tabel user_levels
        if ($newLevel) {
            $userLevel = $user->userLevel;
            if ($userLevel->level_id !== $newLevel->id) {
                $userLevel->level_id = $newLevel->id;
                $userLevel->save();
            }
        }

        return redirect()->route('user.level.show')->with('success', 'Points updated successfully!');
    }

    /**
     * Reset poin pengguna (opsional, misalnya untuk debugging atau admin).
     */
    public function resetPoints()
    {
        $user = Auth::user();

        // Reset total_points di tabel user_point_summaries
        $userPointSummary = UserPointSummary::where('user_id', $user->id)->first();

        if ($userPointSummary) {
            $userPointSummary->total_points = 0;
            $userPointSummary->save();
        }

        // Reset ke level paling rendah
        $lowestLevel = Level::orderBy('minimum_points', 'asc')->first();
        if ($lowestLevel) {
            $userLevel = $user->userLevel;
            $userLevel->level_id = $lowestLevel->id;
            $userLevel->save();
        }

        return redirect()->route('user.level.show')->with('success', 'Points reset successfully!');
    }

    /**
     * Menampilkan semua level yang ada.
     */
    public function showAllLevels()
    {
        $user = Auth::user();

        // Ambil semua level
        $allLevels = Level::orderBy('minimum_points', 'asc')->get();

        // Ambil level pengguna saat ini
        $userLevel = $user->userLevel;
        $currentLevel = $userLevel->level;

        // Ambil total_points dari tabel user_point_summaries
        $userPointSummary = UserPointSummary::where('user_id', $user->id)->first();
        $totalPoints = $userPointSummary ? $userPointSummary->total_points : 0;

        return view('student.points.show_all_levels', compact('allLevels', 'currentLevel', 'totalPoints'));
    }
}