<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\User;
use App\Models\UserReward;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * Display the testing page for rewards and give a random reward
     *
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function testing($userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            return $this->showTestingViewWithError($user, 'User not found.');
        }

        // Get all completed sessions (checkin-checkout) for the user today
        $sessionsToday = $user->attendances()
            ->whereDate('checkin_time', today()) // Filter by today
            ->whereNotNull('checkout_time') // Only completed sessions
            ->get();

        // If there are no completed sessions today
        if ($sessionsToday->isEmpty()) {
            return $this->showTestingViewWithError($user, 'No completed sessions found today.');
        }

        // Check each session and count eligible sessions
        $eligibleSessions = [];
        foreach ($sessionsToday as $session) {
            // Calculate session duration in minutes
            $sessionDuration = $session->checkin_time->diffInMinutes($session->checkout_time);

            // Check if session duration meets the requirement (6 hours = 360 minutes)
            if ($sessionDuration >= 360) {
                $eligibleSessions[] = $session;
            }
        }

        // If no sessions meet the duration requirement
        if (empty($eligibleSessions)) {
            return $this->showTestingViewWithError($user, 'No sessions meet the required duration of 6 hours.');
        }

        // Start database transaction
        DB::beginTransaction();

        try {
            // Get all rewards for fair probability calculation
            $allRewards = Reward::all();

            if ($allRewards->isEmpty()) {
                return $this->showTestingViewWithError($user, 'No rewards available.');
            }

            // Array to store selected rewards
            $selectedRewards = [];

            // Give rewards for each eligible session
            foreach ($eligibleSessions as $session) {
                // Select a reward based on probability and fairness
                $selectedReward = $this->selectReward($user, $allRewards);

                // If no reward is selected
                if (!$selectedReward) {
                    DB::rollBack();
                    return $this->showTestingViewWithError($user, 'Failed to select a reward.');
                }

                // Decrease the reward stock if available
                if ($selectedReward->quantity > 0) {
                    $selectedReward->decrement('quantity');
                }

                // Save the reward received by the user
                UserReward::create([
                    'user_id'    => $user->id,
                    'reward_id'  => $selectedReward->id,
                    'received_at' => now(),
                    'attendance_id' => $session->id, // Link reward to the session
                ]);

                // Add selected reward to the array
                $selectedRewards[] = $selectedReward;
            }

            // Commit the database transaction
            DB::commit();

            // Get all reward data to display in the view
            $rewards = Reward::all();

            // Get the user's latest testimony (if any)
            $testimony = $user->testimonies()->latest()->first();

            // Send reward data, selected rewards, and testimony to the view
            return view('lecturer.attendance.testing', compact('rewards', 'selectedRewards', 'testimony'));

        } catch (\Exception $e) {
            // Rollback the database transaction in case of an error
            DB::rollBack();

            return $this->showTestingViewWithError($user, 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Select a reward based on probability with fairness logic
     */
    private function selectReward($user, $allRewards)
    {
        // 1. Check for recent big rewards (>= 100 points) in the last 7 days
        $hasRecentBigReward = UserReward::where('user_id', $user->id)
            ->whereHas('reward', function($q) {
                $q->where('points', '>=', 100);
            })
            ->where('received_at', '>=', now()->subDays(7))
            ->exists();

        // 2. Use a fixed probability scale (100%)
        $totalScale = 100;
        $randomNumber = mt_rand(0, $totalScale * 100) / 100;
        $cumulative = 0;
        $pickedReward = null;

        $sortedRewards = $allRewards->sortBy('probability');

        foreach ($sortedRewards as $reward) {
            $prob = $reward->probability;

            // If user recently got a big reward, slash chances for another one
            if ($hasRecentBigReward && $reward->points >= 100) {
                $prob = $prob * 0.05;
            }

            $cumulative += $prob;
            if ($randomNumber <= $cumulative) {
                $pickedReward = $reward;
                break;
            }
        }

        // 3. Fallback & Stock Check
        $consolationReward = $allRewards->where('points', '>', 0)->sortBy('points')->first();

        if (!$pickedReward || $pickedReward->quantity <= 0) {
            return $consolationReward;
        }

        return $pickedReward;
    }

    /**
     * Display the list of rewards received by the user
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRewards($userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Get the list of rewards received by the user
        $rewards = $user->rewards()->with('reward')->get();

        return response()->json([
            'success' => true,
            'data' => $rewards,
        ]);
    }

    /**
     * Display the result of the random reward distribution
     *
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function showRewardResult($userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            return $this->showTestingViewWithError($user, 'User not found.');
        }

        // Get the latest reward received by the user
        $lastReward = $user->rewards()->with('reward')->latest()->first();

        // Get the user's latest testimony (if any)
        $testimony = $user->testimonies()->latest()->first();

        // Send data to the view
        return view('lecturer.attendance.testing', [
            'rewards' => Reward::all(),
            'lastReward' => $lastReward,
            'testimony' => $testimony,
        ]);
    }

    /**
     * Display the testing page with an error message
     *
     * @param User|null $user
     * @param string $errorMessage
     * @return \Illuminate\View\View
     */
    private function showTestingViewWithError($user, $errorMessage)
    {
        // Get all reward data to display in the view
        $rewards = Reward::all();

        // Get the user's latest testimony (if any)
        $testimony = $user ? $user->testimonies()->latest()->first() : null;

        // Send reward data, testimony, and error message to the view
        return view('lecturer.attendance.testing', [
            'rewards' => $rewards,
            'testimony' => $testimony,
            'error' => $errorMessage,
        ]);
    }
}