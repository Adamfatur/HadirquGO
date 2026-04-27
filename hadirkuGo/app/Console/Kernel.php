<?php

namespace App\Console;

use App\Console\Commands\UpdateUserPointsSummary;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CalculateLeaderboard;
use App\Console\Commands\UpdateUserStatistics;
use App\Console\Commands\UpdateWeeklyRankings;
use App\Console\Commands\AssignDailyMp;
use App\Console\Commands\AssignLongestDuration;
use App\Console\Commands\AssignAdventureStudentAchievement;
use App\Console\Commands\DeactivateOldAttendances;


use App\Console\Commands\SyncLeaderboardFrames;


class Kernel extends ConsoleKernel
{
    protected $commands = [
        AssignAdventureStudentAchievement::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('leaderboard:update')->hourly()->withoutOverlapping();
        $schedule->command('leaderboard:sync-frames')->hourly()->withoutOverlapping(); // Sync frames/titles after leaderboard update
        $schedule->command('user_statistics:update')->hourly()->withoutOverlapping();
        $schedule->command('weekly_rankings:update')->hourly()->withoutOverlapping();
        $schedule->command('achievement:assign-daily-mp')->hourly()->withoutOverlapping();
        $schedule->command('achievement:assign-longest-duration')->dailyAt('23:59');
        $schedule->command('achievement:assign-adventure-student')->hourly()->withoutOverlapping();
        $schedule->command('attendance:deactivate-old')->dailyAt('00:05');
        $schedule->command('mvp:send-email')->dailyAt('23:59');
        $schedule->command('ranking:daily')->dailyAt('23:59');
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
