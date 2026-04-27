<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Challenge;
use App\Http\Controllers\Student\ChallengeController;

class CompleteExpiredChallenges extends Command
{
    /**
     * Perintah artisan yang akan dipanggil: php artisan challenges:complete-expired
     */
    protected $signature = 'challenges:complete-expired';

    /**
     * Deskripsi singkat perintah.
     */
    protected $description = 'Complete ongoing challenges that have exceeded their duration.';

    /**
     * Menangani logic utama.
     */
    public function handle()
    {
        // Ambil waktu saat ini
        $now = Carbon::now();

        // Ambil semua challenge yang statusnya 'ongoing'
        $ongoingChallenges = Challenge::where('status', 'ongoing')->get();

        $completedCount = 0;

        foreach ($ongoingChallenges as $challenge) {
            // Hitung deadline challenge: started_at + duration_days
            $deadline = Carbon::parse($challenge->started_at)
                ->addDays($challenge->duration_days);

            // Jika sekarang >= deadline, berarti challenge sudah lewat waktunya
            if ($now->gte($deadline)) {
                // Panggil method completeChallenge di ChallengeController
                // Pastikan method ini tidak hanya mengembalikan JSON response saja,
                // agar bisa dipanggil secara lancar di sisi CLI.
                // (Jika saat ini hanya return JSON, tetap akan dipanggil,
                // tapi CLI tidak butuh respon JSON.)
                $controller = app(ChallengeController::class);
                $controller->completeChallenge($challenge->id);

                $completedCount++;
            }
        }

        // Tampilkan pesan di CLI
        $this->info("{$completedCount} challenge(s) have been completed automatically.");

        return 0; // Beri tanda berhasil
    }
}