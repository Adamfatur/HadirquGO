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
use Illuminate\Support\Facades\Log; // Import Facade Logging

class AssignAdventureStudentAchievement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievement:assign-adventure-student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the "Adventure Student" achievement to users who visit more than 3 different locations with at least 1 hour per location in a day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Assigning Adventure Student Achievement...');
        Log::info('Assigning Adventure Student Achievement...');

        // Get today's date
        $today = Carbon::now()->startOfDay();
        Log::info('Today\'s date: ' . $today->toDateTimeString());

        // Get the "Adventure Student" achievement with ID 3
        $adventureStudentAchievement = Achievement::find(3);

        if (!$adventureStudentAchievement) {
            $this->error('Achievement "Adventure Student" not found. Please create it first.');
            Log::error('Achievement "Adventure Student" not found.');
            return;
        }

        Log::info('Achievement found: ' . $adventureStudentAchievement->name);

        // Check if this achievement has already been awarded today
        $alreadyAssignedUsers = UserAchievement::where('achievement_id', $adventureStudentAchievement->id)
            ->whereDate('achieved_at', $today)
            ->pluck('user_id');

        Log::info('Users who have already received the achievement today:', $alreadyAssignedUsers->toArray());

        // Get users who meet the criteria
        $eligibleUsers = Attendance::whereDate('checkin_time', $today)
            ->where('duration_at_location', '>=', 60) // At least 1 hour per location
            ->groupBy('user_id')
            ->havingRaw('COUNT(DISTINCT attendance_location_id) > ?', [3]) // More than 3 locations
            ->select('user_id', DB::raw('COUNT(DISTINCT attendance_location_id) as location_count'))
            ->get();

        Log::info('Users who meet the criteria:', $eligibleUsers->toArray());

        if ($eligibleUsers->isEmpty()) {
            $this->info('No users met the criteria for the "Adventure Student" achievement today.');
            Log::info('No users met the criteria today.');
            return;
        }

        foreach ($eligibleUsers as $user) {
            // Skip users who have already received the achievement today
            if ($alreadyAssignedUsers->contains($user->user_id)) {
                $this->info("User ID {$user->user_id} has already received the achievement today.");
                Log::info("User ID {$user->user_id} has already received the achievement today.");
                continue;
            }

            // Get the team_id from one of the user's attendance records
            $teamId = Attendance::where('user_id', $user->user_id)
                ->whereDate('checkin_time', $today)
                ->value('team_id');

            Log::info("Assigning achievement to User ID: {$user->user_id}, Team ID: {$teamId}");

            // Assign the achievement
            UserAchievement::create([
                'user_id' => $user->user_id,
                'team_id' => $teamId,
                'achievement_id' => $adventureStudentAchievement->id,
                'achieved_at' => Carbon::now(),
            ]);

            $this->info("Achievement 'Adventure Student' assigned to user ID: {$user->user_id} (Team ID: {$teamId}).");
            Log::info("Achievement 'Adventure Student' assigned to User ID: {$user->user_id} (Team ID: {$teamId}).");

            // Add 25 points if the user visited 5 or more locations
            if ($user->location_count >= 5) {
                $userPointSummary = UserPointSummary::firstOrCreate(
                    ['user_id' => $user->user_id],
                    ['total_points' => 0, 'current_points' => 0]
                );

                $userPointSummary->increment('total_points', 25);
                $userPointSummary->increment('current_points', 25);

                // Log the point addition in UserPoint
                UserPoint::create([
                    'user_id' => $user->user_id,
                    'points' => 25,
                    'description' => 'Achievement: Adventure Student (5+ locations)',
                ]);

                $this->info("Added 25 points to user ID: {$user->user_id} for visiting 5 or more locations.");
                Log::info("Added 25 points to user ID: {$user->user_id} for visiting 5 or more locations.");
            }
        }

        $this->info('Finished assigning Adventure Student Achievement.');
        Log::info('Finished assigning Adventure Student Achievement.');
    }
}