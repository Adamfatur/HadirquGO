<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\User;
use App\Mail\MvpCongratulations;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendMvpEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvp:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send congratulatory email to the MVP of the day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Mendapatkan rentang waktu untuk hari ini
        $dateRange = [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()];

        // Mendapatkan top 1 berdasarkan total durasi hari ini
        $mvpUser = Attendance::whereBetween('checkin_time', $dateRange)
            ->whereHas('user')
            ->select(
                'user_id',
                DB::raw('SUM(duration_at_location) as total_duration')
            )
            ->groupBy('user_id')
            ->orderByDesc('total_duration')
            ->with('user')
            ->first();

        if ($mvpUser && $mvpUser->user) {
            // Kirim email selamat
            Mail::to($mvpUser->user->email)->send(new MvpCongratulations($mvpUser->user, 'total_duration', 'daily'));
            $this->info('MVP email sent to ' . $mvpUser->user->email);
        } else {
            $this->info('No MVP found for today.');
        }

        return 0;
    }
}