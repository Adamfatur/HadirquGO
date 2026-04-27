<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLeaderboard;
use App\Models\Team;
use App\Models\User;
use App\Models\Attendance;
use App\Models\UserAchievement;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class AttendanceController extends Controller
{
    /**
     * Calculate Morning Person rankings with tiebreaker
     */
    private function calculateMorningPersonRankings($attendancesByDate, $limit = 10, $allUsers = null)
    {
        $morningPersonCounts = [];
        $morningPersonTimes = [];
        $morningPersonDates = [];
        
        // Build user lookup map to avoid N+1 queries
        $userMap = $allUsers ? $allUsers->keyBy('id') : collect();
        
        // Initialize all users with 0 count if provided
        if ($allUsers) {
            foreach ($allUsers as $user) {
                $morningPersonCounts[$user->id] = 0;
                $morningPersonTimes[$user->id] = [];
                $morningPersonDates[$user->id] = [];
            }
        }
        
        foreach ($attendancesByDate as $date => $dailyAttendances) {
            $earliestAttendance = $dailyAttendances->sortBy('checkin_time')->first();
            
            if ($earliestAttendance) {
                $userId = $earliestAttendance->user_id;
                if (!isset($morningPersonCounts[$userId])) {
                    $morningPersonCounts[$userId] = 0;
                    $morningPersonTimes[$userId] = [];
                    $morningPersonDates[$userId] = [];
                }
                $morningPersonCounts[$userId]++;
                $morningPersonTimes[$userId][] = $earliestAttendance->checkin_time;
                $morningPersonDates[$userId][] = [
                    'date' => $date,
                    'time' => $earliestAttendance->checkin_time
                ];
            }
        }

        return collect($morningPersonCounts)->map(function ($count, $userId) use ($morningPersonTimes, $morningPersonDates, $userMap) {
            $times = $morningPersonTimes[$userId] ?? [];
            $avgTimestamp = !empty($times) ? collect($times)->average(function ($time) {
                return $time->timestamp;
            }) : 0;
            
            return [
                'user'  => $userMap->get($userId) ?? User::find($userId),
                'count' => $count,
                'avg_time' => $avgTimestamp,
                'dates' => $morningPersonDates[$userId] ?? [],
            ];
        })->sortBy([
            ['count', 'desc'],
            ['avg_time', 'asc'],
        ])->take($limit)->values();
    }

    /**
     * Calculate Last Person rankings — user who checked OUT last each day
     */
    private function calculateLastPersonRankings($attendancesByDate, $limit = 10, $allUsers = null)
    {
        $lastPersonCounts = [];
        $lastPersonTimes = [];
        $lastPersonDates = [];
        
        $userMap = $allUsers ? $allUsers->keyBy('id') : collect();
        
        if ($allUsers) {
            foreach ($allUsers as $user) {
                $lastPersonCounts[$user->id] = 0;
                $lastPersonTimes[$user->id] = [];
                $lastPersonDates[$user->id] = [];
            }
        }
        
        foreach ($attendancesByDate as $date => $dailyAttendances) {
            // Last person = latest checkout_time (not checkin)
            $withCheckout = $dailyAttendances->filter(fn($a) => $a->checkout_time !== null);
            $lastCheckout = $withCheckout->sortByDesc('checkout_time')->first();
            
            if ($lastCheckout) {
                $lpUserId = $lastCheckout->user_id;
                if (!isset($lastPersonCounts[$lpUserId])) {
                    $lastPersonCounts[$lpUserId] = 0;
                    $lastPersonTimes[$lpUserId] = [];
                    $lastPersonDates[$lpUserId] = [];
                }
                $lastPersonCounts[$lpUserId]++;
                $lastPersonTimes[$lpUserId][] = $lastCheckout->checkout_time;
                $lastPersonDates[$lpUserId][] = [
                    'date' => $date,
                    'time' => $lastCheckout->checkout_time
                ];
            }
        }

        return collect($lastPersonCounts)->map(function ($count, $userId) use ($lastPersonTimes, $lastPersonDates, $userMap) {
            $times = $lastPersonTimes[$userId] ?? [];
            $avgTimestamp = !empty($times) ? collect($times)->average(function ($time) {
                return $time->timestamp;
            }) : 0;
            
            return [
                'user'  => $userMap->get($userId) ?? User::find($userId),
                'count' => $count,
                'avg_time' => $avgTimestamp,
                'dates' => $lastPersonDates[$userId] ?? [],
            ];
        })->sortBy([
            ['count', 'desc'],
            ['avg_time', 'desc'],
        ])->take($limit)->values();
    }

    /**
     * Calculate Most Late Person rankings — user who checked IN last each day (arrived latest)
     */
    private function calculateMostLatePersonRankings($attendancesByDate, $limit = 10, $allUsers = null)
    {
        $latePersonCounts = [];
        $latePersonDates = [];
        
        $userMap = $allUsers ? $allUsers->keyBy('id') : collect();
        
        if ($allUsers) {
            foreach ($allUsers as $user) {
                $latePersonCounts[$user->id] = 0;
                $latePersonDates[$user->id] = [];
            }
        }
        
        foreach ($attendancesByDate as $date => $dailyAttendances) {
            // Most late = last checkin of the day
            $lastCheckin = $dailyAttendances->sortByDesc('checkin_time')->first();
            
            if ($lastCheckin) {
                $userId = $lastCheckin->user_id;
                if (!isset($latePersonCounts[$userId])) {
                    $latePersonCounts[$userId] = 0;
                    $latePersonDates[$userId] = [];
                }
                $latePersonCounts[$userId]++;
                $latePersonDates[$userId][] = [
                    'date' => $date,
                    'time' => $lastCheckin->checkin_time
                ];
            }
        }

        return collect($latePersonCounts)->map(function ($count, $userId) use ($latePersonDates, $userMap) {
            return [
                'user'  => $userMap->get($userId) ?? User::find($userId),
                'count' => $count,
                'dates' => $latePersonDates[$userId] ?? [],
            ];
        })->sortByDesc('count')->take($limit)->values();
    }

    /**
     * Calculate Most Absent Person rankings
     */
    private function calculateMostAbsentPersonRankings($attendancesByDate, $allUsers, $startOfMonth, $endOfMonth, $limit = 10)
    {
        $absentCounts = [];
        $absentDates = [];
        
        // Build user lookup map to avoid N+1 queries
        $userMap = $allUsers->keyBy('id');
        
        // Get public holidays for this month
        $publicHolidays = $this->getPublicHolidays($startOfMonth->year, $startOfMonth->month);
        
        // Get current date (today)
        $today = Carbon::now()->startOfDay();
        
        // Get all working days in the month (exclude weekends, holidays, and future dates)
        $workingDays = [];
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $dateString = $currentDate->toDateString();
            
            // Skip future dates (only count today and past dates)
            if ($currentDate->greaterThan($today)) {
                $currentDate->addDay();
                continue;
            }
            
            // Skip weekends (Saturday=6, Sunday=0)
            $isWeekend = $currentDate->dayOfWeek == 0 || $currentDate->dayOfWeek == 6;
            
            // Skip public holidays
            $isHoliday = isset($publicHolidays[$dateString]);
            
            if (!$isWeekend && !$isHoliday) {
                $workingDays[] = $dateString;
            }
            $currentDate->addDay();
        }
        
        // Initialize all users with 0 count
        foreach ($allUsers as $user) {
            $absentCounts[$user->id] = 0;
            $absentDates[$user->id] = [];
        }
        
        // Build a set of user_ids per date for O(1) lookup instead of ->contains()
        $attendanceUsersByDate = [];
        foreach ($attendancesByDate as $date => $dailyAttendances) {
            $attendanceUsersByDate[$date] = $dailyAttendances->pluck('user_id')->flip()->all();
        }
        
        // Count absent days for each user
        foreach ($allUsers as $user) {
            foreach ($workingDays as $date) {
                // O(1) lookup instead of ->contains()
                if (!isset($attendanceUsersByDate[$date][$user->id])) {
                    $absentCounts[$user->id]++;
                    $absentDates[$user->id][] = [
                        'date' => $date,
                    ];
                }
            }
        }

        return collect($absentCounts)->map(function ($count, $userId) use ($absentDates, $userMap) {
            return [
                'user'  => $userMap->get($userId) ?? User::find($userId),
                'count' => $count,
                'dates' => $absentDates[$userId] ?? [],
            ];
        })->sortByDesc('count')->take($limit)->values();
    }

    public function index($teamUniqueId)
    {
        // 1) Get the Team, Leader, Managers
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();
        $leader = User::with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])->find($team->leader_id);
        $managers = $team->managers()->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])->get();

        // 2) By default, exclude the leader and managers from the member list
        //    so they will NOT appear when show_leaders_managers is false/off.
        $members = $team->members()
            ->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])
            ->whereNotIn('users.id', array_merge([$team->leader_id], $managers->pluck('id')->toArray()))
            ->get();

        // 3) Check if the filter to show leaders & managers is enabled
        $showLeadersManagers = request('show_leaders_managers', false);

        // 4) All team members (for rankings — always includes leader & managers)
        $allTeamMembers = $members->merge([$leader])->merge($managers);

        // 5) Members for table display
        $allMembers = $showLeadersManagers ? $allTeamMembers : $members;

        // 6) Grab the month filter or use the current month
        $month = request('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth   = Carbon::parse($month)->endOfMonth();

        // 7) Single attendance query for ALL team members (superset)
        $allTeamAttendances = Attendance::with('user', 'attendanceLocation')
            ->whereIn('user_id', $allTeamMembers->pluck('id'))
            ->whereBetween('checkin_time', [$startOfMonth, $endOfMonth])
            ->orderBy('checkin_time', 'asc')
            ->get();

        // 8) Filter for table display members
        $displayMemberIds = $allMembers->pluck('id')->flip();
        $attendances = $allTeamAttendances->filter(function ($att) use ($displayMemberIds) {
            return isset($displayMemberIds[$att->user_id]);
        })->values();

        // 9) Group attendance by date (for rankings - all members)
        $allTeamAttendancesByDate = $allTeamAttendances->groupBy(function ($attendance) {
            return $attendance->checkin_time->toDateString();
        });

        // 10) Calculate rankings (using ALL team members)
        $morningPersonRankings = $this->calculateMorningPersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $morningPersonData = $morningPersonRankings->take(1);

        $lastPersonRankings = $this->calculateLastPersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $lastPersonData = $lastPersonRankings->take(1);

        $mostLatePersonRankings = $this->calculateMostLatePersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $mostLatePersonData = $mostLatePersonRankings->take(1);

        $mostAbsentPersonRankings = $this->calculateMostAbsentPersonRankings($allTeamAttendancesByDate, $allTeamMembers, $startOfMonth, $endOfMonth, 10);
        $mostAbsentPersonData = $mostAbsentPersonRankings->take(1);

        // 11) Group attendances by user_id & date for the calendar
        $attendanceByUserDate = $attendances->groupBy(function ($attendance) {
            return $attendance->user_id . '_' . $attendance->checkin_time->toDateString();
        });

        // 12) Identify members who did not attend at all
        $attendedUserIds = $attendances->pluck('user_id')->unique()->flip();
        $absentMembers = $allMembers->filter(function ($member) use ($attendedUserIds) {
            return !isset($attendedUserIds[$member->id]);
        });

        // 13) Pre-compute duration & count per member for sorting (avoid repeated collection scans)
        $attendancesByUser = $attendances->groupBy('user_id');
        $memberStats = [];
        foreach ($allMembers as $member) {
            $userAttendances = $attendancesByUser->get($member->id, collect());
            $memberStats[$member->id] = [
                'duration' => $userAttendances->sum('duration_at_location'),
                'count' => $userAttendances->count(),
            ];
        }

        $memberDurations = $allMembers->map(function ($member) use ($memberStats) {
            return [
                'user'           => $member,
                'total_duration' => $memberStats[$member->id]['duration'] ?? 0,
            ];
        });
        $sortedMemberDurations = $memberDurations->sortByDesc('total_duration')->values();

        // Sort members using pre-computed stats
        $sortedMembers = $allMembers->sort(function ($a, $b) use ($memberStats) {
            $durA = $memberStats[$a->id]['duration'] ?? 0;
            $durB = $memberStats[$b->id]['duration'] ?? 0;

            if ($durA !== $durB) {
                return $durB <=> $durA;
            }

            $countA = $memberStats[$a->id]['count'] ?? 0;
            $countB = $memberStats[$b->id]['count'] ?? 0;
            
            if ($countA !== $countB) {
                return $countB <=> $countA;
            }

            return strcmp($a->name, $b->name);
        })->values();

        return view('lecturer.attendance.index', [
            'team'                   => $team,
            'leader'                 => $leader,
            'managers'               => $managers,
            'members'                => $sortedMembers,
            'attendances'            => $attendances,
            'attendanceByUserDate'   => $attendanceByUserDate,
            'morningPersonData'      => $morningPersonData,
            'morningPersonRankings'  => $morningPersonRankings,
            'lastPersonData'         => $lastPersonData,
            'lastPersonRankings'     => $lastPersonRankings,
            'mostLatePersonData'     => $mostLatePersonData,
            'mostLatePersonRankings' => $mostLatePersonRankings,
            'mostAbsentPersonData'   => $mostAbsentPersonData,
            'mostAbsentPersonRankings' => $mostAbsentPersonRankings,
            'absentMembers'          => $absentMembers,
            'sortedMemberDurations'  => $sortedMemberDurations,
            'month'                  => $month,
            'showLeadersManagers'    => $showLeadersManagers,
        ]);
    }

    public function mobileindex($teamUniqueId)
    {
        // 1) Get the Team, Leader, Managers
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();
        $leader = User::with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])->find($team->leader_id);
        $managers = $team->managers()->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])->get();

        // 2) By default, exclude the leader and managers from the member list
        $members = $team->members()
            ->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])
            ->whereNotIn('users.id', array_merge([$team->leader_id], $managers->pluck('id')->toArray()))
            ->get();

        // 3) Check if the filter to show leaders & managers is enabled
        $showLeadersManagers = request('show_leaders_managers', false);

        // 4) All team members (for rankings — always includes leader & managers)
        $allTeamMembers = $members->merge([$leader])->merge($managers);

        // 5) Members for table display
        $allMembers = $showLeadersManagers ? $allTeamMembers : $members;

        // 6) Grab the month filter or use the current month
        $month = request('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth   = Carbon::parse($month)->endOfMonth();

        // 7) Single attendance query for ALL team members (superset)
        $allTeamAttendances = Attendance::with('user', 'attendanceLocation')
            ->whereIn('user_id', $allTeamMembers->pluck('id'))
            ->whereBetween('checkin_time', [$startOfMonth, $endOfMonth])
            ->orderBy('checkin_time', 'asc')
            ->get();

        // 8) Filter for table display members
        $displayMemberIds = $allMembers->pluck('id')->flip();
        $attendances = $allTeamAttendances->filter(function ($att) use ($displayMemberIds) {
            return isset($displayMemberIds[$att->user_id]);
        })->values();

        // 9) Group attendance by date (for rankings - all members)
        $allTeamAttendancesByDate = $allTeamAttendances->groupBy(function ($attendance) {
            return $attendance->checkin_time->toDateString();
        });

        // 10) Calculate rankings (using ALL team members)
        $morningPersonRankings = $this->calculateMorningPersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $morningPersonData = $morningPersonRankings->take(1);

        $lastPersonRankings = $this->calculateLastPersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $lastPersonData = $lastPersonRankings->take(1);

        $mostLatePersonRankings = $this->calculateMostLatePersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $mostLatePersonData = $mostLatePersonRankings->take(1);

        $mostAbsentPersonRankings = $this->calculateMostAbsentPersonRankings($allTeamAttendancesByDate, $allTeamMembers, $startOfMonth, $endOfMonth, 10);
        $mostAbsentPersonData = $mostAbsentPersonRankings->take(1);

        // 11) Group attendances by user_id & date for the calendar
        $attendanceByUserDate = $attendances->groupBy(function ($attendance) {
            return $attendance->user_id . '_' . $attendance->checkin_time->toDateString();
        });

        // 12) Identify members who did not attend at all
        $attendedUserIds = $attendances->pluck('user_id')->unique()->flip();
        $absentMembers = $allMembers->filter(function ($member) use ($attendedUserIds) {
            return !isset($attendedUserIds[$member->id]);
        });

        // 13) Pre-compute duration & count per member for sorting
        $attendancesByUser = $attendances->groupBy('user_id');
        $memberStats = [];
        foreach ($allMembers as $member) {
            $userAttendances = $attendancesByUser->get($member->id, collect());
            $memberStats[$member->id] = [
                'duration' => $userAttendances->sum('duration_at_location'),
                'count' => $userAttendances->count(),
            ];
        }

        $memberDurations = $allMembers->map(function ($member) use ($memberStats) {
            return [
                'user'           => $member,
                'total_duration' => $memberStats[$member->id]['duration'] ?? 0,
            ];
        });
        $sortedMemberDurations = $memberDurations->sortByDesc('total_duration')->values();

        $sortedMembers = $allMembers->sort(function ($a, $b) use ($memberStats) {
            $durA = $memberStats[$a->id]['duration'] ?? 0;
            $durB = $memberStats[$b->id]['duration'] ?? 0;

            if ($durA !== $durB) {
                return $durB <=> $durA;
            }

            $countA = $memberStats[$a->id]['count'] ?? 0;
            $countB = $memberStats[$b->id]['count'] ?? 0;
            
            if ($countA !== $countB) {
                return $countB <=> $countA;
            }

            return strcmp($a->name, $b->name);
        })->values();

        return view('lecturer.attendance.mobileindex', [
            'team'                   => $team,
            'leader'                 => $leader,
            'managers'               => $managers,
            'members'                => $sortedMembers,
            'attendances'            => $attendances,
            'attendanceByUserDate'   => $attendanceByUserDate,
            'morningPersonData'      => $morningPersonData,
            'morningPersonRankings'  => $morningPersonRankings,
            'lastPersonData'         => $lastPersonData,
            'lastPersonRankings'     => $lastPersonRankings,
            'mostLatePersonData'     => $mostLatePersonData,
            'mostLatePersonRankings' => $mostLatePersonRankings,
            'mostAbsentPersonData'   => $mostAbsentPersonData,
            'mostAbsentPersonRankings' => $mostAbsentPersonRankings,
            'absentMembers'          => $absentMembers,
            'sortedMemberDurations'  => $sortedMemberDurations,
            'month'                  => $month,
            'showLeadersManagers'    => $showLeadersManagers,
        ]);
    }

    public function getAttendanceDetails($memberId, $date)
    {
        // Fetch attendance data for the member on the specified date
        $attendanceDetails = Attendance::where('user_id', $memberId)
            ->whereDate('checkin_time', $date)
            ->with('user', 'attendanceLocation')
            ->get();

        // Check if there is attendance data
        if ($attendanceDetails->isEmpty()) {
            return response()->json([
                'name' => 'No data available',
                'date' => $date,
                'attendances' => [],
            ]);
        }

        // Return data with location, check-in, and check-out times
        return response()->json([
            'name' => $attendanceDetails->first()->user->name,
            'date' => $date,
            'attendances' => $attendanceDetails->map(function ($attendance) {
                return [
                    'location' => $attendance->attendanceLocation->name ?? 'Not recorded',
                    'checkin_time' => $attendance->checkin_time->format('H:i'),
                    'checkout_time' => $attendance->checkout_time ? $attendance->checkout_time->format('H:i') : 'Not recorded',
                ];
            }),
        ]);
    }

    public function downloadPdf(Request $request, $teamUniqueId)
    {
        // Get the team by unique team ID
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();

        // Get the leader
        $leader = $team->leader ? User::find($team->leader->id) : null;

        // Get the managers
        $managers = $team->managers()->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }])->get();

        // Get the members from the team_members pivot table (EXCLUDING leader and managers)
        $members = $team->members()
            ->whereNotIn('users.id', array_merge([$team->leader_id], $managers->pluck('id')->toArray()))
            ->get();

        // Check if the filter to show leaders & managers is enabled
        $showLeadersManagers = $request->get('show_leaders_managers', false);

        // Start with just the default members
        $allMembers = $members;

        // If the user has chosen to include them, merge Leader + Managers into $allMembers
        if ($showLeadersManagers) {
            $allMembers = $allMembers->merge([$leader])->merge($managers);
        }

        // For rankings calculation, always use ALL team members (create separate collection)
        $allTeamMembers = collect($members)->merge([$leader])->merge($managers);

        // Get the selected month or current month if no filter is provided
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        // Parse the start and end of the month
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        // Get attendance data for the selected month (for display in table)
        $attendances = Attendance::with('user', 'attendanceLocation')
            ->whereIn('user_id', $allMembers->pluck('id'))
            ->whereBetween('checkin_time', [$startOfMonth, $endOfMonth])
            ->orderBy('checkin_time', 'asc')
            ->get();

        // Group attendances by date (for rankings - based on filter)
        $attendancesByDate = $attendances->groupBy(function ($attendance) {
            return $attendance->checkin_time->toDateString();
        });

        // Calculate rankings based on filtered members ($allMembers)
        $morningPersonRankings = $this->calculateMorningPersonRankings($attendancesByDate, 10, $allMembers);
        $morningPersonData = $morningPersonRankings->take(1);

        $lastPersonRankings = $this->calculateLastPersonRankings($attendancesByDate, 10, $allMembers);
        $lastPersonData = $lastPersonRankings->take(1);

        $mostLatePersonRankings = $this->calculateMostLatePersonRankings($attendancesByDate, 10, $allMembers);
        $mostLatePersonData = $mostLatePersonRankings->take(1);

        $mostAbsentPersonRankings = $this->calculateMostAbsentPersonRankings($attendancesByDate, $allMembers, $startOfMonth, $endOfMonth, 10);
        $mostAbsentPersonData = $mostAbsentPersonRankings->take(1);

        // Group attendances by user_id and date for the attendance calendar
        $attendanceByUserDate = $attendances->groupBy(function ($attendance) {
            return $attendance->user_id . '_' . $attendance->checkin_time->toDateString();
        });

        // Get members who did not attend at all during the month
        $absentMembers = $allMembers->filter(function ($member) use ($attendances) {
            return !$attendances->contains('user_id', $member->id);
        });

        // Calculate total duration for each member
        $memberDurations = $allMembers->map(function ($member) use ($attendances) {
            $totalDuration = $attendances->where('user_id', $member->id)->sum('duration_at_location');
            return [
                'user' => $member,
                'total_duration' => $totalDuration,
            ];
        });

        // Sort members by total duration (descending)
        $sortedMemberDurations = $memberDurations->sortByDesc('total_duration')->values();

        // Sort members by name (A-Z)
        // Sort members by total duration (descending), then by check-ins (descending), then by name
        $sortedMembers = $allMembers->sort(function ($a, $b) use ($attendances) {
            $durA = $attendances->where('user_id', $a->id)->sum('duration_at_location');
            $durB = $attendances->where('user_id', $b->id)->sum('duration_at_location');

            if ($durA !== $durB) {
                return $durB <=> $durA; // Descending Duration
            }

            $countA = $attendances->where('user_id', $a->id)->count();
            $countB = $attendances->where('user_id', $b->id)->count();
            
            if ($countA !== $countB) {
                return $countB <=> $countA; // Descending Check-ins
            }

            return strcmp($a->name, $b->name);
        })->values();

        // Generate PDF with updated data
        $pdf = PDF::loadView('lecturer.attendance.pdf', [
            'team' => $team,
            'month' => $month,
            'attendances' => $attendances,
            'attendanceByUserDate' => $attendanceByUserDate,
            'members' => $sortedMembers,
            'morningPersonData' => $morningPersonData,
            'lastPersonData' => $lastPersonData,
            'mostLatePersonData' => $mostLatePersonData,
            'mostAbsentPersonData' => $mostAbsentPersonData,
            'absentMembers' => $absentMembers,
            'sortedMemberDurations' => $sortedMemberDurations,
            'leader' => $leader,
            'managers' => $managers,
            'showLeadersManagers' => $showLeadersManagers,
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream('attendance-report-' . $team->team_unique_id . '-' . Carbon::parse($month)->format('F-Y') . '.pdf');
    }

    public function downloadCsv(Request $request, $teamUniqueId)
    {
        // Get the team by unique team ID
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();

        // Get the leader
        $leader = $team->leader ? User::find($team->leader->id) : null;

        // Get the managers
        $managers = $team->managers()->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }])->get();

        // Get the members from the team_members pivot table (EXCLUDING leader and managers)
        $members = $team->members()
            ->whereNotIn('users.id', array_merge([$team->leader_id], $managers->pluck('id')->toArray()))
            ->get();

        // Check if the filter to show leaders & managers is enabled
        $showLeadersManagers = $request->get('show_leaders_managers', false);

        // Start with just the default members
        $allMembers = $members;

        // If the user has chosen to include them, merge Leader + Managers into $allMembers
        if ($showLeadersManagers) {
            $allMembers = $allMembers->merge([$leader])->merge($managers);
        }

        // Get the selected month or current month if no filter is provided
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        // Parse the start and end of the month
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        // Get attendance data for the selected month (for display)
        $attendances = Attendance::with('user', 'attendanceLocation')
            ->whereIn('user_id', $allMembers->pluck('id'))
            ->whereBetween('checkin_time', [$startOfMonth, $endOfMonth])
            ->orderBy('checkin_time', 'asc')
            ->get();

        // Group attendances by date (for rankings - based on filter)
        $attendancesByDate = $attendances->groupBy(function ($attendance) {
            return $attendance->checkin_time->toDateString();
        });

        // Calculate rankings based on filtered members ($allMembers)
        $morningPersonRankings = $this->calculateMorningPersonRankings($attendancesByDate, 10, $allMembers);
        $morningPersonData = $morningPersonRankings->take(1);

        $lastPersonRankings = $this->calculateLastPersonRankings($attendancesByDate, 10, $allMembers);
        $lastPersonData = $lastPersonRankings->take(1);

        $mostLatePersonRankings = $this->calculateMostLatePersonRankings($attendancesByDate, 10, $allMembers);
        $mostLatePersonData = $mostLatePersonRankings->take(1);

        $mostAbsentPersonRankings = $this->calculateMostAbsentPersonRankings($attendancesByDate, $allMembers, $startOfMonth, $endOfMonth, 10);
        $mostAbsentPersonData = $mostAbsentPersonRankings->take(1);

        // Group attendances by user_id and date for the attendance calendar
        $attendanceByUserDate = $attendances->groupBy(function ($attendance) {
            return $attendance->user_id . '_' . $attendance->checkin_time->toDateString();
        });

        // Get members who did not attend at all during the month
        $absentMembers = $allMembers->filter(function ($member) use ($attendances) {
            return !$attendances->contains('user_id', $member->id);
        });

        // Calculate total duration for each member
        $memberDurations = $allMembers->map(function ($member) use ($attendances) {
            $totalDuration = $attendances->where('user_id', $member->id)->sum('duration_at_location');
            return [
                'user' => $member,
                'total_duration' => $totalDuration,
            ];
        });

        // Sort members by total duration (descending)
        $sortedMemberDurations = $memberDurations->sortByDesc('total_duration')->values();

        // Sort members by name (A-Z)
        // Sort members by total duration (descending), then by check-ins (descending), then by name
        $sortedMembers = $allMembers->sort(function ($a, $b) use ($attendances) {
            $durA = $attendances->where('user_id', $a->id)->sum('duration_at_location');
            $durB = $attendances->where('user_id', $b->id)->sum('duration_at_location');

            if ($durA !== $durB) {
                return $durB <=> $durA; // Descending Duration
            }

            $countA = $attendances->where('user_id', $a->id)->count();
            $countB = $attendances->where('user_id', $b->id)->count();
            
            if ($countA !== $countB) {
                return $countB <=> $countA; // Descending Check-ins
            }

            return strcmp($a->name, $b->name);
        })->values();

        // Prepare CSV data
        $csvData = [];

        // Add headers
        $csvData[] = [
            'Name', 'Date', 'Location', 'Check-in Time', 'Check-out Time', 'Duration'
        ];

        // Add attendance data
        foreach ($attendances as $attendance) {
            $csvData[] = [
                $attendance->user->name,
                $attendance->checkin_time->toDateString(),
                $attendance->attendanceLocation->name ?? 'Not recorded',
                $attendance->checkin_time->format('H:i'),
                $attendance->checkout_time ? $attendance->checkout_time->format('H:i') : 'Not recorded',
                $attendance->duration_at_location,
            ];
        }

        // Add Summary Section
        $csvData[] = [];
        $csvData[] = ['===== SUMMARY ====='];
        if ($showLeadersManagers) {
            $csvData[] = ['Filter: Including Leader & Managers'];
        } else {
            $csvData[] = ['Filter: Excluding Leader & Managers'];
        }
        $csvData[] = [];

        // Add Morning Person data
        $csvData[] = ['Top Morning Person(s)'];
        if ($morningPersonData->isEmpty() || $morningPersonData->first()['count'] == 0) {
            $csvData[] = ['No data available'];
        } else {
            foreach ($morningPersonData as $mp) {
                $csvData[] = [
                    $mp['user']->name,
                    'Times as Morning Person',
                    $mp['count'],
                ];
            }
        }

        // Add Last Person data
        $csvData[] = [];
        $csvData[] = ['Top Last Person(s)'];
        if ($lastPersonData->isEmpty() || $lastPersonData->first()['count'] == 0) {
            $csvData[] = ['No data available'];
        } else {
            foreach ($lastPersonData as $lp) {
                $csvData[] = [
                    $lp['user']->name,
                    'Times as Last Person',
                    $lp['count'],
                ];
            }
        }

        // Add Most Late Person data
        $csvData[] = [];
        $csvData[] = ['Most Late Person(s) (After 08:30)'];
        if ($mostLatePersonData->isEmpty() || $mostLatePersonData->first()['count'] == 0) {
            $csvData[] = ['No data available'];
        } else {
            foreach ($mostLatePersonData as $late) {
                $csvData[] = [
                    $late['user']->name,
                    'Times Late',
                    $late['count'],
                ];
            }
        }

        // Add Most Absent Person data
        $csvData[] = [];
        $csvData[] = ['Most Absent Person(s)'];
        if ($mostAbsentPersonData->isEmpty() || $mostAbsentPersonData->first()['count'] == 0) {
            $csvData[] = ['No data available'];
        } else {
            foreach ($mostAbsentPersonData as $absent) {
                $csvData[] = [
                    $absent['user']->name,
                    'Days Absent',
                    $absent['count'],
                ];
            }
        }

        // Add absent members
        $csvData[] = [];
        $csvData[] = ['Absent Members'];
        foreach ($absentMembers as $absentMember) {
            $csvData[] = [
                $absentMember->name,
                'Did not attend this month',
            ];
        }

        // Add total duration
        $csvData[] = [];
        $csvData[] = ['Total Attendance Duration'];
        foreach ($sortedMemberDurations as $duration) {
            $csvData[] = [
                $duration['user']->name,
                'Total Duration',
                $duration['total_duration'],
            ];
        }

        // Generate CSV file
        $filename = 'attendance-report-' . $team->team_unique_id . '-' . Carbon::parse($month)->format('F-Y') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getAttendanceByDate($teamUniqueId, $date)
    {
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();

        // Explicitly select columns with table names to avoid ambiguity
        $users = $team->members()->select(['users.id', 'users.name', 'users.avatar'])->get();

        if(request('show_leaders_managers', false)) {
            // Tambahkan leader dan managers
            $users = $users->merge([
                $team->leader->only(['id', 'name', 'avatar']), // Ambil data leader dengan key spesifik
                $team->managers->map->only(['id', 'name', 'avatar']) // Ambil data managers dengan key spesifik
            ])->flatten();
        }

        $userIds = $users->pluck('id')->toArray();
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        // Ambil data attendance
        $attendances = Attendance::with(['attendanceLocation'])
            ->whereIn('user_id', $userIds)
            ->whereBetween('checkin_time', [$startOfDay, $endOfDay])
            ->get()
            ->groupBy('user_id');

        $attendanceData = [];
        $presentMembers = 0;
        $forgotCheckoutMembers = 0;

        foreach ($users as $user) {
            $userId = $user->id;
            $status = 'Absent';
            $firstCheckin = 'N/A';
            $lastCheckout = 'N/A';

            if (isset($attendances[$userId])) {
                $sessions = $attendances[$userId];
                $presentMembers++;
                $status = 'Present';

                $firstCheckin = $sessions->sortBy('checkin_time')->first()->checkin_time->format('H:i');

                $lastCheckoutSession = $sessions->filter(function ($session) {
                    return $session->checkout_time !== null;
                })->sortByDesc('checkout_time')->first();

                if ($lastCheckoutSession) {
                    $lastCheckout = $lastCheckoutSession->checkout_time->format('H:i');
                } else {
                    $lastCheckout = 'N/A';
                    $forgotCheckoutMembers++;
                    $status = 'forgot_checkout';
                }
            }

            $attendanceData[] = [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_avatar' => $user->avatar,
                'first_checkin' => $firstCheckin,
                'last_checkout' => $lastCheckout,
                'status' => $status,
            ];
        }

        $totalMembers = count($users);
        $absentMembers = $totalMembers - $presentMembers;

        return response()->json([
            'date' => Carbon::parse($date)->format('j F Y'),
            'attendances' => $attendanceData,
            'summary' => [
                'total_members' => $totalMembers,
                'present_members' => $presentMembers,
                'absent_members' => $absentMembers,
                'forgot_checkout_members' => $forgotCheckoutMembers,
            ]
        ]);
    }

    /**
     * Fetches public holidays from libur.deno.dev API.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function getPublicHolidays(int $year, int $month): array
    {
        try {
            $response = Http::get('https://libur.deno.dev/api', [
                'year' => $year,
                'month' => $month,
            ]);

            if ($response->successful() && !empty($response->json())) {
                // Convert array of objects to associative array [date => name]
                return collect($response->json())->pluck('name', 'date')->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch public holidays', ['error' => $e->getMessage()]);
        }
        return []; // Return empty array if failed
    }

    public function custom($teamUniqueId)
    {
        // 1) Get the Team, Leader, Managers
        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();
        $leader = User::with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])->find($team->leader_id);
        $managers = $team->managers()->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])->get();

        // 2) By default, exclude the leader and managers from the member list
        $members = $team->members()
            ->with(['leaderboards' => function($q) { $q->where('category', 'top_levels'); }, 'pointSummary'])
            ->whereNotIn('users.id', array_merge([$team->leader_id], $managers->pluck('id')->toArray()))
            ->get();

        // 3) Check if the filter to show leaders & managers is enabled
        $showLeadersManagers = request('show_leaders_managers', false);

        // 4) All team members (for rankings — always includes leader & managers)
        $allTeamMembers = $members->merge([$leader])->merge($managers);

        // 5) Members for table display
        $allMembers = $showLeadersManagers ? $allTeamMembers : $members;

        // 6) Grab the date range filter
        $rangeType = request('range_type', 'custom');
        $startDateParam = request('start_date');
        $endDateParam = request('end_date');

        $now = Carbon::now();
        $endDate = $now->copy()->endOfDay();
        
        switch ($rangeType) {
            case '60_days':
                $startDate = $now->copy()->subDays(60)->startOfDay();
                break;
            case '90_days':
                $startDate = $now->copy()->subDays(90)->startOfDay();
                break;
            case '6_months':
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                break;
            case '1_year':
                $startDate = $now->copy()->subYear()->startOfDay();
                break;
            case 'custom':
            default:
                $startDate = $startDateParam ? Carbon::parse($startDateParam)->startOfDay() : $now->copy()->startOfMonth();
                $endDate = $endDateParam ? Carbon::parse($endDateParam)->endOfDay() : $now->copy()->endOfMonth();
                break;
        }

        // Ensure dates are not too far apart to prevent memory exhaustion (e.g., limit to 366 days max)
        if ($startDate->diffInDays($endDate) > 366) {
             $startDate = $endDate->copy()->subDays(365)->startOfDay();
        }

        // 7) Single attendance query for ALL team members (superset)
        $allTeamAttendances = Attendance::with('user', 'attendanceLocation')
            ->whereIn('user_id', $allTeamMembers->pluck('id'))
            ->whereBetween('checkin_time', [$startDate, $endDate])
            ->orderBy('checkin_time', 'asc')
            ->get();

        // 8) Filter for table display members
        $displayMemberIds = $allMembers->pluck('id')->flip();
        $attendances = $allTeamAttendances->filter(function ($att) use ($displayMemberIds) {
            return isset($displayMemberIds[$att->user_id]);
        })->values();

        // 9) Group attendance by date
        $allTeamAttendancesByDate = $allTeamAttendances->groupBy(function ($attendance) {
            return $attendance->checkin_time->toDateString();
        });

        // Rankings
        $morningPersonRankings = $this->calculateMorningPersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $morningPersonData = $morningPersonRankings->take(1);

        $lastPersonRankings = $this->calculateLastPersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $lastPersonData = $lastPersonRankings->take(1);

        $mostLatePersonRankings = $this->calculateMostLatePersonRankings($allTeamAttendancesByDate, 10, $allTeamMembers);
        $mostLatePersonData = $mostLatePersonRankings->take(1);

        $mostAbsentPersonRankings = $this->calculateMostAbsentPersonRankings($allTeamAttendancesByDate, $allTeamMembers, $startDate, $endDate, 10);
        $mostAbsentPersonData = $mostAbsentPersonRankings->take(1);

        // 10) Group attendances by user_id & date
        $attendanceByUserDate = $attendances->groupBy(function ($attendance) {
            return $attendance->user_id . '_' . $attendance->checkin_time->toDateString();
        });

        // 11) Identify members who did not attend at all
        $attendedUserIds = $attendances->pluck('user_id')->unique()->flip();
        $absentMembers = $allMembers->filter(function ($member) use ($attendedUserIds) {
            return !isset($attendedUserIds[$member->id]);
        });

        // 12) Pre-compute duration & count per member for sorting
        $attendancesByUser = $attendances->groupBy('user_id');
        $memberStats = [];
        foreach ($allMembers as $member) {
            $userAttendances = $attendancesByUser->get($member->id, collect());
            $memberStats[$member->id] = [
                'duration' => $userAttendances->sum('duration_at_location'),
                'count' => $userAttendances->count(),
            ];
        }

        $memberDurations = $allMembers->map(function ($member) use ($memberStats) {
            return [
                'user'           => $member,
                'total_duration' => $memberStats[$member->id]['duration'] ?? 0,
            ];
        });
        $sortedMemberDurations = $memberDurations->sortByDesc('total_duration')->values();

        $sortedMembers = $allMembers->sort(function ($a, $b) use ($memberStats) {
            $durA = $memberStats[$a->id]['duration'] ?? 0;
            $durB = $memberStats[$b->id]['duration'] ?? 0;

            if ($durA !== $durB) {
                return $durB <=> $durA;
            }

            $countA = $memberStats[$a->id]['count'] ?? 0;
            $countB = $memberStats[$b->id]['count'] ?? 0;
            
            if ($countA !== $countB) {
                return $countB <=> $countA;
            }

            return strcmp($a->name, $b->name);
        })->values();

        return view('lecturer.attendance.custom', [
            'team'                   => $team,
            'leader'                 => $leader,
            'managers'               => $managers,
            'members'                => $sortedMembers,
            'attendances'            => $attendances,
            'attendanceByUserDate'   => $attendanceByUserDate,
            'morningPersonData'      => $morningPersonData,
            'morningPersonRankings'  => $morningPersonRankings,
            'lastPersonData'         => $lastPersonData,
            'lastPersonRankings'     => $lastPersonRankings,
            'mostLatePersonData'     => $mostLatePersonData,
            'mostLatePersonRankings' => $mostLatePersonRankings,
            'mostAbsentPersonData'   => $mostAbsentPersonData,
            'mostAbsentPersonRankings' => $mostAbsentPersonRankings,
            'absentMembers'          => $absentMembers,
            'sortedMemberDurations'  => $sortedMemberDurations,
            'startDate'              => $startDate,
            'endDate'                => $endDate,
            'rangeType'              => $rangeType,
            'showLeadersManagers'    => $showLeadersManagers,
        ]);
    }

}
