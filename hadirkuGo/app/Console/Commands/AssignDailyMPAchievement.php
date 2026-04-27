<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\UserAchievement;
use App\Models\Achievement;
use Carbon\Carbon;

class AssignDailyMPAchievement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievement:assign-daily-mp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the "Daily MP" achievement to the first user who checks in each day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Assigning Daily MP Achievement...');

        // Get today's date
        $today = Carbon::now()->startOfDay();

        // Check if achievement already assigned today
        $dailyMPAchievement = Achievement::where('name', 'Daily MP')->first();

        if (!$dailyMPAchievement) {
            $this->error('Achievement "Daily MP" not found. Please create it first.');
            return;
        }

        // Check if this achievement has already been awarded today
        $alreadyAssigned = UserAchievement::where('achievement_id', $dailyMPAchievement->id)
            ->whereDate('achieved_at', $today)
            ->exists();

        if ($alreadyAssigned) {
            $this->info('The "Daily MP" achievement has already been assigned today.');
            return;
        }

        // Find the first check-in of the day
        $firstCheckIn = Attendance::whereDate('checkin_time', $today)
            ->orderBy('checkin_time', 'asc')
            ->first();

        if (!$firstCheckIn) {
            $this->info('No check-ins found for today.');
            return;
        }

        // Assign the achievement to the user
        UserAchievement::create([
            'user_id' => $firstCheckIn->user_id,
            'team_id' => $firstCheckIn->team_id,
            'achievement_id' => $dailyMPAchievement->id,
            'achieved_at' => Carbon::now(),
        ]);

        $this->info("Achievement 'Daily MP' assigned to user ID: {$firstCheckIn->user_id} (Team ID: {$firstCheckIn->team_id}).");
    }
}
