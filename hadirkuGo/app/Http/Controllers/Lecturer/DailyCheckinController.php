<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\DailyCheckin;
use App\Models\UserPointSummary;
use App\Models\UserPoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyCheckinController extends Controller
{
    /**
     * Check all users and award points based on daily login consistency.
     */
    public function checkAllUsers(Request $request)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Get all users
            $users = User::all();

            // Get the start and end date of the current week
            $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

            // Array to store users who received points
            $usersWithPoints = [];

            // Loop through each user
            foreach ($users as $user) {
                // Get the user's check-ins for the current week
                $checkinsThisWeek = Attendance::where('user_id', $user->id)
                    ->whereBetween('checkin_time', [$weekStartDate, $weekEndDate])
                    ->orderBy('checkin_time', 'asc')
                    ->get();

                // Save daily check-ins to DailyCheckin table
                foreach ($checkinsThisWeek as $checkin) {
                    $checkinDate = Carbon::parse($checkin->checkin_time)->format('Y-m-d');

                    // Check if the user has already logged in on this date
                    $existingCheckin = DailyCheckin::where('user_id', $user->id)
                        ->where('checkin_date', $checkinDate)
                        ->first();

                    if (!$existingCheckin) {
                        // Save daily check-in to DailyCheckin table
                        DailyCheckin::create([
                            'user_id' => $user->id,
                            'checkin_date' => $checkinDate,
                            'week_start_date' => $weekStartDate,
                            'points_earned' => 1, // Poin harian untuk check-in
                        ]);
                    }
                }

                // Get all daily check-ins for the current week from DailyCheckin table
                $dailyCheckinsThisWeek = DailyCheckin::where('user_id', $user->id)
                    ->where('week_start_date', $weekStartDate)
                    ->orderBy('checkin_date', 'asc')
                    ->get();

                // Calculate the number of consecutive check-in days
                $consecutiveDays = $this->calculateConsecutiveDays($dailyCheckinsThisWeek);

                // Check if the user has already received points this week
                $hasReceivedPoints = UserPoint::where('user_id', $user->id)
                    ->whereBetween('created_at', [$weekStartDate, $weekEndDate])
                    ->whereIn('description', [
                        'Bonus 10 points for the first daily login this week.',
                        'Bonus 50 points for 3 consecutive daily logins.',
                        'Bonus 100 points for 5 consecutive daily logins.',
                    ])
                    ->exists();

                // If the user has not received points this week, award points
                if (!$hasReceivedPoints) {
                    // Award points based on consistency
                    $pointsToAdd = 0;
                    $description = '';

                    if ($consecutiveDays >= 5) {
                        $pointsToAdd = 160; // Total poin untuk 5 hari berturut-turut (10 + 50 + 100)
                        $description = 'Bonus 160 points for 5 consecutive daily logins (10 + 50 + 100).';
                    } elseif ($consecutiveDays >= 3) {
                        $pointsToAdd = 60; // Total poin untuk 3 hari berturut-turut (10 + 50)
                        $description = 'Bonus 60 points for 3 consecutive daily logins (10 + 50).';
                    } elseif ($consecutiveDays >= 1) {
                        $pointsToAdd = 10; // Poin untuk check-in pertama
                        $description = 'Bonus 10 points for the first daily login this week.';
                    }

                    // Save points to UserPointSummary and notification to UserPoint if the user is eligible
                    if ($pointsToAdd > 0) {
                        // Update UserPointSummary
                        $userPointSummary = UserPointSummary::firstOrCreate(
                            ['user_id' => $user->id],
                            ['total_points' => 0, 'current_points' => 0]
                        );

                        $userPointSummary->increment('total_points', $pointsToAdd);
                        $userPointSummary->increment('current_points', $pointsToAdd);

                        // Save notification to UserPoint
                        UserPoint::create([
                            'user_id' => $user->id,
                            'points' => $pointsToAdd,
                            'description' => $description,
                        ]);

                        // Add user to the list of users who received points
                        $usersWithPoints[] = [
                            'user_id' => $user->id,
                            'name' => $user->name, // Asumsi kolom 'name' ada di tabel users
                            'points' => $pointsToAdd,
                            'description' => $description,
                        ];
                    }
                }
            }

            // Commit transaksi database
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All users have been checked and points have been awarded.',
                'users_with_points' => $usersWithPoints, // Daftar user yang mendapatkan poin
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate the number of consecutive daily logins.
     */
    private function calculateConsecutiveDays($dailyCheckins)
    {
        $consecutiveDays = 0;
        $previousDay = null;

        foreach ($dailyCheckins as $checkin) {
            $currentDay = Carbon::parse($checkin->checkin_date);

            // If there is a previous day and the difference is 1 day, increment consecutiveDays
            if ($previousDay && $currentDay->diffInDays($previousDay) === 1) {
                $consecutiveDays++;
            } else {
                // Reset if not consecutive
                $consecutiveDays = 1;
            }

            $previousDay = $currentDay;
        }

        return $consecutiveDays;
    }
}