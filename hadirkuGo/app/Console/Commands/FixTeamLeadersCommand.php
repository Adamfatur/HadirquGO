<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;

class FixTeamLeadersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan fix:teamleaders
     */
    protected $signature = 'fix:teamleaders';

    /**
     * The console command description.
     */
    protected $description = 'Pastikan semua leader tim juga terdaftar sebagai member di timnya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil semua tim
        $teams = Team::all();

        foreach ($teams as $team) {
            // Abaikan jika tim tidak punya leader_id (null)
            if (!$team->leader_id) {
                continue;
            }

            // Cek apakah leader sudah ada di relasi members
            if (!$team->members->contains($team->leader_id)) {
                $team->members()->attach($team->leader_id);

                $this->info("Menambahkan user (ID: {$team->leader_id}) ke team (ID: {$team->id}).");
            }
        }

        $this->info('Proses selesai. Semua leader yang belum jadi member kini telah ditambahkan sebagai member.');
    }
}