<?php

namespace App\Http\Controllers;

use App\Models\UserPointSummary;
use App\Models\RankingHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RankingController extends Controller
{
    public function storeDailySnapshot()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        // Ambil top 50
        $topUsers = UserPointSummary::orderByDesc('current_points')
            ->limit(50)
            ->get(['user_id', 'current_points']);

        if ($topUsers->isEmpty()) {
            return response()->json(['message' => 'Tidak ada user untuk di-snapshot'], 200);
        }

        // Simpan data
        foreach ($topUsers as $index => $userSummary) {
            RankingHistory::create([
                'user_id'          => $userSummary->user_id,
                'rank'             => $index + 1,
                'points'           => $userSummary->current_points,
                'period_type'      => 'daily',
                'period_start_date'=> $yesterday,
                'period_end_date'  => $yesterday,
            ]);
        }

        return response()->json(['message' => 'Snapshot daily ranking berhasil disimpan']);
    }
}