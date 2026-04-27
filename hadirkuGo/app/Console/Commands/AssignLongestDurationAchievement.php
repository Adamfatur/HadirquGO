<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\UserAchievement;
use App\Models\Achievement;
use App\Models\UserPointSummary;
use App\Models\UserPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignLongestDurationAchievement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievement:assign-longest-duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the "Longest Duration" achievement to the user with the most total activity duration in a day at 23:59.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Starting AssignLongestDurationAchievement command.');

        // Get today's date
        $today = Carbon::now()->startOfDay();
        Log::info("Today's date: {$today}");

        // Get the "Longest Duration" achievement
        $longestDurationAchievement = Achievement::where('name', 'Longest Duration')->first();

        if (!$longestDurationAchievement) {
            $errorMessage = 'Achievement "Longest Duration" not found. Please create it first.';
            Log::error($errorMessage);
            $this->error($errorMessage);
            return;
        }

        Log::info('Achievement "Longest Duration" found.', ['achievement_id' => $longestDurationAchievement->id]);

        // Check if this achievement has already been awarded today
        $alreadyAssigned = UserAchievement::where('achievement_id', $longestDurationAchievement->id)
            ->whereDate('achieved_at', $today)
            ->exists();

        if ($alreadyAssigned) {
            $infoMessage = 'The "Longest Duration" achievement has already been assigned today.';
            Log::info($infoMessage);
            $this->info($infoMessage);
            return;
        }

        Log::info('No prior assignment of "Longest Duration" achievement found for today.');

        // Find the user with the longest total_daily_duration for today
        $longestDuration = Attendance::select('user_id', DB::raw('SUM(total_daily_duration) as total_duration'))
            ->whereDate('checkin_time', $today)
            ->groupBy('user_id')
            ->orderBy('total_duration', 'desc')
            ->first();

        if (!$longestDuration || $longestDuration->total_duration <= 0) {
            $infoMessage = 'No valid durations found for today.';
            Log::info($infoMessage);
            $this->info($infoMessage);
            return;
        }

        Log::info('User with the longest duration found.', [
            'user_id' => $longestDuration->user_id,
            'total_duration' => $longestDuration->total_duration,
        ]);

        // Assign the achievement to the user with the longest duration
        UserAchievement::create([
            'user_id' => $longestDuration->user_id,
            'achievement_id' => $longestDurationAchievement->id,
            'achieved_at' => Carbon::now(),
        ]);

        $successMessage = "Achievement 'Longest Duration' assigned to user ID: {$longestDuration->user_id}.";
        Log::info($successMessage);
        $this->info($successMessage);

        // Log the winner details
        Log::info('Winner of "Longest Duration" achievement:', [
            'user_id' => $longestDuration->user_id,
            'total_duration' => $longestDuration->total_duration,
            'achievement_id' => $longestDurationAchievement->id,
            'achieved_at' => Carbon::now(),
        ]);

        $this->info('Winner details logged successfully.');

        // Add 50 points to the user's total_points and current_points
        $userPointSummary = UserPointSummary::firstOrCreate(
            ['user_id' => $longestDuration->user_id],
            ['total_points' => 0, 'current_points' => 0]
        );

        $userPointSummary->increment('total_points', 50);
        $userPointSummary->increment('current_points', 50);

        Log::info('Points added to user.', [
            'user_id' => $longestDuration->user_id,
            'total_points' => $userPointSummary->total_points,
            'current_points' => $userPointSummary->current_points,
        ]);

        // Log the point addition in UserPoint
        UserPoint::create([
            'user_id' => $longestDuration->user_id,
            'points' => 50,
            'description' => 'Achievement: Longest Duration',
        ]);

        Log::info('UserPoint log created.', [
            'user_id' => $longestDuration->user_id,
            'points' => 50,
            'description' => 'Achievement: Longest Duration',
        ]);

        $this->info("Added 50 points to user ID: {$longestDuration->user_id}.");

        Log::info('AssignLongestDurationAchievement command completed successfully.');
    }
}