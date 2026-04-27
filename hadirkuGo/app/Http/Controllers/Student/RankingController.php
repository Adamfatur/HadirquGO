<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RankingHistory;
use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function viewDailyRanking()
    {
        // Karena ranking diupdate setiap pukul 23.59,
        // maka ranking yang tampil adalah ranking kemarin (misal: Rabu jika hari ini Kamis)
        // dan dibandingkan dengan ranking dari sehari sebelumnya (misal: Selasa)
        $currentPeriodDate = Carbon::yesterday()->toDateString();
        $previousPeriodDate = Carbon::yesterday()->copy()->subDay()->toDateString();

        // Ambil data ranking kemarin (top 50) beserta relasi user
        $currentRankings = RankingHistory::with('user')
            ->where('period_start_date', $currentPeriodDate)
            ->where('period_type', 'daily')
            ->orderBy('rank')
            ->limit(50)
            ->get(['user_id', 'rank', 'points']);

        if ($currentRankings->isEmpty()) {
            // Bisa diarahkan ke blade dengan pesan khusus, misalnya:
            return view('student.viewboard.daily-ranking')->with('message', 'Tidak ada user untuk ditampilkan');
        }

        // Ambil data ranking dari periode sebelumnya untuk perbandingan (misal: Selasa)
        $previousRankings = RankingHistory::where('period_start_date', $previousPeriodDate)
            ->where('period_type', 'daily')
            ->pluck('rank', 'user_id')
            ->toArray();

        // Format hasil response untuk dikirim ke view
        $results = [];
        foreach ($currentRankings as $current) {
            $user = $current->user;

            // Ambil total poin user saat ini (dihitung melalui accessor getTotalPointsAttribute di model User)
            $totalPoints = $user->total_points;

            // Tentukan level user berdasarkan total poin (sesuai dengan range di tabel levels)
            $level = Level::where('minimum_points', '<=', $totalPoints)
                ->where('maximum_points', '>=', $totalPoints)
                ->first();
            $levelName = $level ? $level->name : 'No Level';

            $results[] = [
                'user_id'         => $user->id,
                'name'            => $user->name,
                'avatar'          => $user->avatar ?? asset('images/default-avatar.png'),
                'current_rank'    => $current->rank,
                // Poin pada saat snapshot ranking (bisa berbeda dengan total poin terkini)
                'points_snapshot' => $current->points,
                'total_points'    => $totalPoints,
                'level'           => $levelName,
                // Opsional: jika Anda memiliki gambar level tersendiri
                'level_image'     => $level->image_url ?? asset('images/default-level.png'),
                'rank_change'     => isset($previousRankings[$user->id])
                    ? $previousRankings[$user->id] - $current->rank // Hitung selisih ranking
                    : 0, // Jika data sebelumnya tidak ada, anggap tidak ada perubahan
            ];
        }

        // Kirim data ke blade view
        return view('student.viewboard.daily-ranking', ['rankings' => $results]);
    }
}
