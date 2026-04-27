<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserStatistic;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateUserStatistics extends Command
{
    protected $signature = 'user_statistics:update';

    protected $description = 'Update user statistics';

    public function handle()
    {
        $this->info('Starting to update user statistics...');

        // Calculate all morning and late persons in advance to avoid 365,000 queries
        $morningPersonCounts = $this->getMorningPersonCounts();
        $latePersonCounts = $this->getLatePersonCounts();

        // Process users in chunks to avoid memory limit issues and avoid N+1 queries
        User::chunk(100, function ($users) use ($morningPersonCounts, $latePersonCounts) {
            $userIds = $users->pluck('id');
            // Fetch attendances for these users and group by user_id
            $attendancesByUser = Attendance::whereIn('user_id', $userIds)->get()->groupBy('user_id');

            foreach ($users as $user) {
                $this->info("Processing statistics for user: {$user->id} - {$user->name}");

                $attendances = $attendancesByUser->get($user->id);

                if (!$attendances || $attendances->isEmpty()) {
                    continue;
                }

                // 1. Average Check-in Time
                $checkinTimes = $attendances->whereNotNull('checkin_time')->pluck('checkin_time');
                if ($checkinTimes->isNotEmpty()) {
                    $averageCheckinTimestamp = $checkinTimes->avg(function ($time) {
                        return Carbon::parse($time)->timestamp;
                    });
                    $averageCheckinTime = Carbon::createFromTimestamp($averageCheckinTimestamp)->format('H:i:s');
                } else {
                    $averageCheckinTime = null;
                }

                // 2. Most Frequently Visited Location
                $mostFrequentLocationId = $attendances->whereNotNull('attendance_location_id')
                    ->groupBy('attendance_location_id')
                    ->sortByDesc(function ($group) {
                        return $group->count();
                    })
                    ->keys()
                    ->first();

                // 3. All Visited Locations
                $allVisitedLocationIds = $attendances->pluck('attendance_location_id')
                    ->unique()
                    ->filter()
                    ->values()
                    ->all();

                // 4. Average Check-out Time
                $checkoutTimes = $attendances->whereNotNull('checkout_time')->pluck('checkout_time');
                if ($checkoutTimes->isNotEmpty()) {
                    $averageCheckoutTimestamp = $checkoutTimes->avg(function ($time) {
                        return Carbon::parse($time)->timestamp;
                    });
                    $averageCheckoutTime = Carbon::createFromTimestamp($averageCheckoutTimestamp)->format('H:i:s');
                } else {
                    $averageCheckoutTime = null;
                }

                // 5. Total Check-ins
                $totalCheckins = $attendances->whereNotNull('checkin_time')->count();

                // 6. Total Check-outs
                $totalCheckouts = $attendances->whereNotNull('checkout_time')->count();

                // 7. Longest Consecutive Attendance Streak
                $longestStreak = $this->calculateLongestStreak($attendances);

                // 8. Max Check-ins in One Day
                $maxCheckinsInOneDay = $attendances->whereNotNull('checkin_time')
                    ->groupBy(function ($attendance) {
                        return Carbon::parse($attendance->checkin_time)->toDateString();
                    })
                    ->map
                    ->count()
                    ->max() ?? 0;

                // 9. Total Attendance Sessions
                $totalSessions = $attendances->whereNotNull('checkin_time')->whereNotNull('checkout_time')->count();

                // 10. Least Visited Location
                $leastFrequentLocationId = $attendances->whereNotNull('attendance_location_id')
                    ->groupBy('attendance_location_id')
                    ->sortBy(function ($group) {
                        return $group->count();
                    })
                    ->keys()
                    ->first();

                // 11. Morning Person and Late Person Counts
                $morningPersonCount = $morningPersonCounts[$user->id] ?? 0;
                $latePersonCount = $latePersonCounts[$user->id] ?? 0;

                // Update or create user statistics
                UserStatistic::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'average_checkin_time' => $averageCheckinTime,
                        'most_frequent_location_id' => $mostFrequentLocationId,
                        'all_visited_locations' => $allVisitedLocationIds,
                        'average_checkout_time' => $averageCheckoutTime,
                        'total_checkins' => $totalCheckins,
                        'total_checkouts' => $totalCheckouts,
                        'longest_consecutive_attendance_streak' => $longestStreak,
                        'max_checkins_in_one_day' => $maxCheckinsInOneDay,
                        'total_attendance_sessions' => $totalSessions,
                        'least_frequent_location_id' => $leastFrequentLocationId,
                        'morning_person_count' => $morningPersonCount,
                        'late_person_count' => $latePersonCount,
                    ]
                );
            }
        });

        $this->info('User statistics updated successfully!');
    }

    private function calculateLongestStreak($attendances)
    {
        $dates = $attendances->whereNotNull('checkin_time')
            ->sortBy('checkin_time')
            ->pluck('checkin_time')
            ->map(function ($date) {
                return Carbon::parse($date)->toDateString();
            })
            ->unique()
            ->values();

        $longestStreak = 0;
        $currentStreak = 0;
        $previousDate = null;

        foreach ($dates as $date) {
            if ($previousDate) {
                $diff = Carbon::parse($previousDate)->diffInDays(Carbon::parse($date));
                if ($diff == 1) {
                    $currentStreak++;
                } else {
                    $currentStreak = 1;
                }
            } else {
                $currentStreak = 1;
            }
            $previousDate = $date;
            if ($currentStreak > $longestStreak) {
                $longestStreak = $currentStreak;
            }
        }

        return $longestStreak;
    }

    private function getMorningPersonCounts()
    {
        $counts = [];
        
        $firstCheckins = DB::table('attendances')
            ->whereNotNull('checkin_time')
            ->selectRaw('DATE(checkin_time) as date, MIN(checkin_time) as first_checkin_time')
            ->groupBy('date')
            ->get();

        foreach ($firstCheckins as $record) {
            $userId = DB::table('attendances')
                ->where('checkin_time', $record->first_checkin_time)
                ->value('user_id');
                
            if ($userId) {
                $counts[$userId] = ($counts[$userId] ?? 0) + 1;
            }
        }

        return $counts;
    }

    private function getLatePersonCounts()
    {
        $counts = [];
        
        $lastCheckouts = DB::table('attendances')
            ->whereNotNull('checkout_time')
            ->selectRaw('DATE(checkout_time) as date, MAX(checkout_time) as last_checkout_time')
            ->groupBy('date')
            ->get();

        foreach ($lastCheckouts as $record) {
            $userId = DB::table('attendances')
                ->where('checkout_time', $record->last_checkout_time)
                ->value('user_id');
                
            if ($userId) {
                $counts[$userId] = ($counts[$userId] ?? 0) + 1;
            }
        }

        return $counts;
    }
}