<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RankingHistory;
use App\Models\UserPointSummary; // atau model lain yang menyimpan total/current points

class StoreDailyRanking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Misalnya: php artisan ranking:daily
     */
    protected $signature = 'ranking:daily';

    /**
     * The console command description.
     */
    protected $description = 'Ambil Top 50 user berdasarkan current_points (atau total_points), lalu simpan snapshot ke ranking_histories untuk hari ini.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Tanggal yang akan di-snapshot adalah hari ini
        $today = Carbon::today()->toDateString();

        // 1. Ambil TOP 50 user berdasarkan current_points
        //    (atau total_points, silakan disesuaikan).
        //    Di sini saya contohkan ambil dari user_point_summaries
        $topUsers = UserPointSummary::orderByDesc('current_points')
            ->limit(50)
            ->get(['user_id', 'current_points']); // kolom yang dibutuhkan

        if ($topUsers->isEmpty()) {
            $this->info("Tidak ada user untuk di-snapshot.");
            return;
        }

        // 2. Loop dan simpan ke ranking_histories
        foreach ($topUsers as $index => $userSummary) {
            RankingHistory::create([
                'user_id'           => $userSummary->user_id,
                'rank'              => $index + 1,            // rank dimulai dari 1
                'points'            => $userSummary->current_points,
                'period_type'       => 'daily',
                'period_start_date' => $today,
                'period_end_date'   => $today,
            ]);
        }

        $this->info("Snapshot ranking harian untuk {$today} berhasil disimpan.");
    }
}
