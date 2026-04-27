<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckUserLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkuser:level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check which users have leveled up based on total points and log the changes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Mulai transaksi untuk memastikan konsistensi data
        DB::beginTransaction();

        try {
            // Ambil semua level dan urutkan berdasarkan minimum_points
            $levels = Level::orderBy('minimum_points', 'asc')->get();

            // Ambil total jumlah pengguna untuk progress bar
            $totalUsers = User::with('pointSummary.level', 'userLevel.level')->count();

            // Inisialisasi progress bar
            $bar = $this->output->createProgressBar($totalUsers);
            $bar->start();

            // Variabel untuk menyimpan jumlah pengguna yang naik level
            $leveledUpCount = 0;

            // Iterasi pengguna dalam chunk untuk menghemat memori
            User::with('pointSummary.level', 'userLevel.level')->chunk(100, function ($users) use ($levels, &$leveledUpCount, $bar) {
                foreach ($users as $user) {
                    // Abaikan pengguna yang tidak memiliki pointSummary
                    if (!$user->pointSummary) {
                        $bar->advance();
                        continue;
                    }

                    $totalPoints = $user->pointSummary->total_points;

                    // Cari level yang sesuai berdasarkan total poin
                    $newLevel = $levels->first(function ($level) use ($totalPoints) {
                        return $level->containsPoints($totalPoints);
                    });

                    if (!$newLevel) {
                        // Jika poin tidak sesuai dengan level manapun, abaikan
                        $bar->advance();
                        continue;
                    }

                    // Ambil level saat ini dari userLevel
                    $currentUserLevel = $user->userLevel;
                    $currentLevel = $currentUserLevel ? $currentUserLevel->level : null;

                    if ($currentLevel) {
                        if ($currentLevel->id !== $newLevel->id) {
                            // Update level pengguna
                            $currentUserLevel->level_id = $newLevel->id;
                            $currentUserLevel->save();

                            // Log perubahan level
                            Log::info("User ID {$user->id} ({$user->name}) naik ke level '{$newLevel->name}' dengan total poin {$totalPoints}.");

                            // Tampilkan di console
                            $this->info("User {$user->name} naik ke level '{$newLevel->name}' dengan total poin {$totalPoints}.");

                            // Tambahkan ke hitungan
                            $leveledUpCount++;
                        }
                    } else {
                        // Assign initial level tanpa mencatatnya sebagai peningkatan
                        $user->userLevel()->create([
                            'level_id' => $newLevel->id,
                        ]);

                        // Log penetapan level awal
                        Log::info("User ID {$user->id} ({$user->name}) ditetapkan ke level awal '{$newLevel->name}' dengan total poin {$totalPoints}.");

                        // Tampilkan di console
                        $this->info("User {$user->name} ditetapkan ke level awal '{$newLevel->name}' dengan total poin {$totalPoints}.");
                    }

                    // Advance progress bar
                    $bar->advance();
                }
            });

            // Selesaikan progress bar
            $bar->finish();
            $this->info("\nPemeriksaan level pengguna selesai.");
            Log::info('Pemeriksaan level pengguna selesai.');
            Log::info("Total pengguna yang naik level: {$leveledUpCount}.");

            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Terjadi kesalahan saat memeriksa level pengguna: ' . $e->getMessage());
            $this->error('Terjadi kesalahan. Periksa log untuk detail.');
            return 1;
        }
    }
}