<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserPointSummary;
use Illuminate\Http\Request;

class UserPointController extends Controller
{
    public function syncAllUserPoints()
    {
        // Ambil semua user
        $users = User::all();

        // Iterasi melalui setiap user
        foreach ($users as $user) {
            // Ambil semua data UserPoint milik user tersebut
            $userPoints = UserPoint::where('user_id', $user->id)->get();

            // Kalkulasi total points dan current points
            $totalPoints = $userPoints->sum('points');
            $currentPoints = $totalPoints; // Jika current points adalah total points yang belum digunakan

            // Cari atau buat record UserPointSummary untuk user tersebut
            $userPointSummary = UserPointSummary::firstOrNew(['user_id' => $user->id]);

            // Update total_points dan current_points
            $userPointSummary->total_points = $totalPoints;
            $userPointSummary->current_points = $currentPoints;

            // Simpan perubahan
            $userPointSummary->save();
        }

        return response()->json([
            'message' => 'User points synced successfully for all users',
        ]);
    }
}