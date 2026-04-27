<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;
//log
use Illuminate\Support\Facades\Log;

class DeactivateOldAttendances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:deactivate-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate attendances where check-in is not today';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Starting deactivation of old attendances.');

        // Mengatur zona waktu ke Asia/Jakarta
        $today = Carbon::today('Asia/Jakarta');

        // Memilih attendances yang aktif dan check-in sebelum hari ini
        $attendances = Attendance::where('is_active', true)
            ->whereDate('checkin_time', '<', $today)
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->is_active = false;
            $attendance->save();

            Log::info("Deactivated Attendance ID: {$attendance->id} for User ID: {$attendance->user_id}");

            // Opsional: Output informasi ke console
            $this->info("Deactivated Attendance ID: {$attendance->id} for User ID: {$attendance->user_id}");
        }

        Log::info('Old attendances have been deactivated successfully.');

        $this->info('Old attendances have been deactivated successfully.');

        return 0;
    }
}
