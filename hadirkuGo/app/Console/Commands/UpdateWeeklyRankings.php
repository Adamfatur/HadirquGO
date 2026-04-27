<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use App\Models\WeeklyRanking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateWeeklyRankings extends Command
{
    protected $signature = 'weekly_rankings:update';

    protected $description = 'Calculate and update weekly rankings based on attendance data every minute';

    public function handle()
    {
        $this->info('Starting to update weekly rankings...');

        // Tentukan minggu saat ini (Senin - Minggu)
        $currentDate = Carbon::now();
        $weekStartDate = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $weekEndDate = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);

        $this->info("Calculating rankings for the week: {$weekStartDate->toDateString()} to {$weekEndDate->toDateString()}");

        // Ambil semua data attendance yang relevan dalam sekali query
        $rankingsData = Attendance::select(
            'user_id',
            DB::raw('SUM(points) as total_points'),
            DB::raw('COUNT(*) as total_attendance_records'),
            DB::raw('SUM(duration_at_location) as total_minutes')
        )
            ->whereBetween('checkin_time', [$weekStartDate, $weekEndDate->endOfDay()])
            ->groupBy('user_id')
            ->get();

        foreach ($rankingsData as $data) {
            // Hitung total sesi (attendances dengan checkin dan checkout)
            $totalSessions = Attendance::where('user_id', $data->user_id)
                ->whereBetween('checkin_time', [$weekStartDate, $weekEndDate->endOfDay()])
                ->whereNotNull('checkin_time')
                ->whereNotNull('checkout_time')
                ->count();

            // Total Hours dalam menit sudah dihitung sebagai $data->total_minutes
            $totalHoursInMinutes = $data->total_minutes ?? 0;

            // Update atau buat weekly ranking
            WeeklyRanking::updateOrCreate(
                [
                    'user_id' => $data->user_id,
                    'week_start_date' => $weekStartDate->toDateString(),
                ],
                [
                    'total_points' => $data->total_points,
                    'total_sessions' => $totalSessions,
                    'total_hours' => $totalHoursInMinutes,
                    'week_end_date' => $weekEndDate->toDateString(),
                ]
            );

            $this->info("Updated ranking for user ID: {$data->user_id}");
        }

        $this->info('Weekly rankings updated successfully!');
    }
}