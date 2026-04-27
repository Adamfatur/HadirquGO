<?php

namespace App\Http\Controllers\Student;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\UserPoint;
use App\Models\Level;
use App\Models\UserLevel;
use App\Models\WeeklyRanking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\UserLeaderboard;
use App\Models\UserPointSummary;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil total points dan current points dari UserPointSummary
        $userPointSummary = $user->pointSummary;

        if ($userPointSummary) {
            $totalPoints = $userPointSummary->total_points;
            $currentPoints = $userPointSummary->current_points;
        } else {
            $totalPoints = 0; // Default jika tidak ada data
            $currentPoints = 0;
        }

        // -------------------------------
        // User Level
        // -------------------------------
        $userLevel = $user->userLevel;

        // Jika userLevel tidak ada, tentukan level berdasarkan totalPoints
        if (!$userLevel) {
            $currentLevel = Level::where('minimum_points', '<=', $totalPoints)
                ->where('maximum_points', '>=', $totalPoints)
                ->first();
        } else {
            $currentLevel = $userLevel->level;
        }

        // Jika tidak ada level yang sesuai, gunakan level default (Pioneer)
        if (!$currentLevel) {
            $currentLevel = Level::where('name', 'Pioneer')->first();
        }

        // Perbarui level pengguna jika diperlukan
        if (!$userLevel || $userLevel->level_id !== $currentLevel->id) {
            $user->userLevel()->updateOrCreate(
                ['user_id' => $user->id],
                ['level_id' => $currentLevel->id]
            );
        }

        // Next Level
        $nextLevel = Level::where('minimum_points', '>', $totalPoints)
            ->orderBy('minimum_points', 'asc')
            ->first();

        // -------------------------------
        // Attendance Data
        // -------------------------------
        // Total Check-ins
        $totalCheckIns = Attendance::where('user_id', $user->id)
            ->where('type', 'checkin')
            ->count();

        // Total Sessions (checkout count)
        $totalSessions = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->count();

        // Average Duration per Session
        $averageSessionDuration = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->avg('total_daily_duration') ?: 0;

        // Total Duration for Current Week
        $weeklyStart = Carbon::now()->startOfWeek();
        $weeklyEnd = Carbon::now()->endOfWeek();
        $totalWeeklyDuration = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->whereBetween('checkout_time', [$weeklyStart, $weeklyEnd])
            ->sum('total_daily_duration');

        // Total Duration for Today
        $today = Carbon::now()->format('Y-m-d');
        $totalTodayDuration = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->whereDate('checkout_time', $today)
            ->sum('total_daily_duration');

        // Total Weekly Sessions
        $totalWeeklySessions = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->whereBetween('checkout_time', [$weeklyStart, $weeklyEnd])
            ->count();

        // Average Duration per Session - Weekly
        $averageWeeklySessionDuration = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->whereBetween('checkout_time', [$weeklyStart, $weeklyEnd])
            ->avg('total_daily_duration') ?: 0;

        // Consistency Tracking
        $totalDailyCheckinsThisWeek = \App\Models\DailyCheckin::where('user_id', $user->id)
            ->where('week_start_date', $weeklyStart->format('Y-m-d'))
            ->count();

        // -------------------------------
        // Location Data
        // -------------------------------
        // Most Frequent Location
        $mostFrequentLocation = Attendance::where('user_id', $user->id)
            ->with('attendanceLocation')
            ->selectRaw('attendance_location_id, COUNT(*) as visit_count')
            ->groupBy('attendance_location_id')
            ->orderByDesc('visit_count')
            ->first();

        // Longest Duration at a Single Location
        $longestDuration = Attendance::where('user_id', $user->id)
            ->whereNotNull('duration_at_location')
            ->orderByDesc('duration_at_location')
            ->with('attendanceLocation')
            ->first();

        // -------------------------------
        // Active Attendance
        // -------------------------------
        $activeAttendance = Attendance::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeAttendance) {
            $checkInTime = $activeAttendance->checkin_time;
            $currentLocation = $activeAttendance->attendanceLocation->name ?? 'Unknown';
            $elapsedMinutes = $checkInTime->diffInMinutes(Carbon::now());
        } else {
            $checkInTime = null;
            $currentLocation = null;
            $elapsedMinutes = null;
        }

        // -------------------------------
        // Weekly Ranking
        // -------------------------------
        $weeklyRanking = WeeklyRanking::where('user_id', $user->id)
            ->where('week_start_date', $weeklyStart->toDateString())
            ->first();

        if ($weeklyRanking) {
            $rank = WeeklyRanking::where('week_start_date', $weeklyStart->toDateString())
                ->where(function($query) use ($weeklyRanking) {
                    $query->where('total_hours', '>', $weeklyRanking->total_hours)
                        ->orWhere(function($query) use ($weeklyRanking) {
                            $query->where('total_hours', '=', $weeklyRanking->total_hours)
                                ->where('total_sessions', '>', $weeklyRanking->total_sessions);
                        })
                        ->orWhere(function($query) use ($weeklyRanking) {
                            $query->where('total_hours', '=', $weeklyRanking->total_hours)
                                ->where('total_sessions', '=', $weeklyRanking->total_sessions)
                                ->where('total_points', '>', $weeklyRanking->total_points);
                        });
                })
                ->count();

            $rank += 1;
        } else {
            $rank = 'N/A'; // Jika belum ada data ranking
        }

        // -------------------------------
        // Fitur Baru: Morning Person, Last Checkin, dan Top Points Hari Ini
        // -------------------------------
        // Morning Person (User dengan Checkin Paling Pertama Hari Ini)
        $morningPerson = Attendance::whereIn('type', ['checkin', 'checkout'])
            ->whereDate('checkin_time', $today)
            ->orderBy('checkin_time', 'asc')
            ->with('user')
            ->first();

        // Subquery untuk mengambil checkin pertama setiap user di hari ini
        $firstCheckinsToday = Attendance::whereIn('type', ['checkin', 'checkout'])
            ->whereDate('checkin_time', $today)
            ->selectRaw('user_id, MIN(checkin_time) as first_checkin_time')
            ->groupBy('user_id');

        // Last Person (User dengan checkin pertama yang paling terakhir di hari ini)
        $lastCheckinUser = Attendance::joinSub($firstCheckinsToday, 'first_checkins', function ($join) {
            $join->on('attendances.user_id', '=', 'first_checkins.user_id')
                ->on('attendances.checkin_time', '=', 'first_checkins.first_checkin_time');
        })
            ->orderBy('attendances.checkin_time', 'desc')
            ->with('user')
            ->first();

        // User dengan Total Points Terbanyak Hari Ini
        $topPointsUser = UserPoint::whereDate('created_at', $today)
            ->selectRaw('user_id, SUM(points) as total_points')
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with('user')
            ->first();

        // -------------------------------
        // Fitur Baru: Data Kemarin (Yesterday)
        // -------------------------------
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // Morning Person Kemarin (User dengan Checkin Paling Pertama Kemarin)
        $yesterdayMorningPerson = Attendance::whereIn('type', ['checkin', 'checkout'])
            ->whereDate('checkin_time', $yesterday)
            ->orderBy('checkin_time', 'asc')
            ->with('user')
            ->first();

        // Subquery untuk mengambil checkin pertama setiap user di hari kemarin
        $firstCheckinsYesterday = Attendance::whereIn('type', ['checkin', 'checkout'])
            ->whereDate('checkin_time', $yesterday)
            ->selectRaw('user_id, MIN(checkin_time) as first_checkin_time')
            ->groupBy('user_id');

        // Last Person Kemarin (User dengan checkin pertama yang paling terakhir di hari kemarin)
        $yesterdayLastCheckinUser = Attendance::joinSub($firstCheckinsYesterday, 'first_checkins', function ($join) {
            $join->on('attendances.user_id', '=', 'first_checkins.user_id')
                ->on('attendances.checkin_time', '=', 'first_checkins.first_checkin_time');
        })
            ->orderBy('attendances.checkin_time', 'desc')
            ->with('user')
            ->first();

        // User dengan Total Durasi Terbanyak Kemarin
        $yesterdayTopDurationUser = Attendance::whereIn('type', ['checkin', 'checkout'])
            ->whereDate('checkout_time', $yesterday)
            ->selectRaw('user_id, SUM(total_daily_duration) as total_duration, MAX(checkout_time) as last_checkout_time')
            ->groupBy('user_id')
            ->orderByDesc('total_duration') // Urutkan berdasarkan total durasi secara descending
            ->orderByDesc('last_checkout_time') // Jika total durasi sama, urutkan berdasarkan checkout_time terakhir
            ->with('user')
            ->first();

        // -------------------------------
        // Ambil Data Juara 1, 2, dan 3 dari UserPointSummary
        // -------------------------------
        $topUsers = \App\Models\UserPointSummary::with('user')
            ->orderByDesc('total_points')
            ->take(3)
            ->get();

        // -------------------------------
// Banner Data (untuk Student - Berdasarkan Tim yang Diikuti)
// -------------------------------
        $activeBanners = collect(); // Inisialisasi koleksi banner

        $teamsJoined = $user->teamsJoined; // Ambil tim yang diikuti student

        if ($teamsJoined->isNotEmpty()) {
            foreach ($teamsJoined as $team) {
                $business = $team->business; // Dapatkan bisnis dari tim

                if ($business) {
                    $businessBanners = $business->banners() // Ambil banner dari bisnis
                    ->where('is_active', true)
                        ->get();

                    $activeBanners = $activeBanners->concat($businessBanners); // Gabungkan banner
                }
            }
        }


        // -------------------------------
        // -------------------------------
        // Leaderboard Context (Top Levels / Total Points)
        // -------------------------------
        // -------------------------------
        // Leaderboard Context & Rival Comparison
        // -------------------------------
        $leaderboardEntry = UserLeaderboard::where('category', 'top_levels')
            ->where('user_id', $user->id)
            ->first();

        $leaderboardContext = null;

        if ($leaderboardEntry) {
            $myRank = $leaderboardEntry->current_rank;
            $leaderboardContext = [
                'rank' => $myRank,
                'score' => $leaderboardEntry->score,
                'myEntry' => $leaderboardEntry,
                'above' => UserLeaderboard::with('user')
                    ->where('category', 'top_levels')
                    ->where('current_rank', $myRank - 1)
                    ->first(),
                'below' => UserLeaderboard::with('user')
                    ->where('category', 'top_levels')
                    ->where('current_rank', $myRank + 1)
                    ->first(),
            ];
        } else {
            $totalPts = $user->pointSummary->total_points ?? 0;
            $rank = UserPointSummary::where('total_points', '>', $totalPts)->count() + 1;
            
            $rank50 = UserLeaderboard::with('user')
                ->where('category', 'top_levels')
                ->where('current_rank', 50)
                ->first();
                
            $leaderboardContext = [
                'rank' => $rank,
                'score' => $totalPts,
                'myEntry' => null,
                'above' => $rank50,
                'below' => null,
            ];
        }

        // Rival Comparison & Smart Recommendations
        $rival = $user->comparisonUser;
        $rivalContext = null;
        $myPts = $user->pointSummary->total_points ?? 0;
        
        if ($rival) {
            $rivalPts = $rival->pointSummary->total_points ?? 0;
            $rivalContext = [
                'user' => $rival,
                'score' => $rivalPts,
                'diff' => $myPts - $rivalPts,
            ];
        }

        // Smart Recommendations:
        // 1. People with similar points (Close Rivals)
        $closeRivals = UserPointSummary::where('user_id', '!=', $user->id)
            ->whereBetween('total_points', [$myPts - 10000, $myPts + 10000])
            ->with('user')
            ->limit(4)
            ->get()
            ->pluck('user');

        // 2. Top Players (Elite Targets)
        $topTargets = UserLeaderboard::where('category', 'top_points')
            ->where('user_id', '!=', $user->id)
            ->orderBy('current_rank', 'asc')
            ->limit(3)
            ->with('user')
            ->get()
            ->pluck('user');

        // Merge and clean up
        $recommendedRivals = collect($closeRivals)->merge($topTargets)->unique('id')->take(6);

 
        return view("dashboard.student", compact("leaderboardContext", "rivalContext", "recommendedRivals",
            'totalPoints',
            'currentPoints',
            'currentLevel',
            'nextLevel',
            'totalCheckIns',
            'totalSessions',
            'totalWeeklySessions',
            'averageSessionDuration',
            'averageWeeklySessionDuration',
            'totalWeeklyDuration',
            'totalTodayDuration',
            'totalDailyCheckinsThisWeek',
            'mostFrequentLocation',
            'longestDuration',
            'checkInTime',
            'currentLocation',
            'elapsedMinutes',
            'activeAttendance',
            'rank',
            'morningPerson',
            'lastCheckinUser',
            'topPointsUser',
            'yesterdayMorningPerson',
            'yesterdayLastCheckinUser',
            'yesterdayTopDurationUser',
            'topUsers',
            'activeBanners'
        ));
    }


    public function fetchTeamActivities()
    {
        $user = Auth::user();

        // 1. Ambil tim yang user ikuti
        $teams = $user->teamsJoined; // Relasi ke tabel 'team_members' sudah didefinisikan di model
        $teamIds = $teams->pluck('id');
        if ($teamIds->isEmpty()) {
            return response()->json(['active_attendances' => [], 'recent_checkouts' => []]);
        }

        // 2. Kumpulkan ID seluruh member tim (user-user lain yang juga tergabung)
        $memberIds = \DB::table('team_members')
            ->whereIn('team_id', $teamIds)
            ->pluck('user_id')
            ->unique();

        // 3. Ambil leader tim
        $leaderIds = \DB::table('teams')
            ->whereIn('id', $teamIds)
            ->pluck('leader_id')
            ->unique();

        // Gabungkan anggota tim dan leader tim
        $allMemberIds = $memberIds->merge($leaderIds)->unique();

        if ($allMemberIds->isEmpty()) {
            return response()->json(['active_attendances' => [], 'recent_checkouts' => []]);
        }

        // 4. Ambil attendance aktif dari leader dan anggota tim
        $activeAttendances = \App\Models\Attendance::whereIn('user_id', $allMemberIds)
            ->where('is_active', true)
            ->with(['user.leaderboards' => function($q) { $q->where('category', 'top_points'); }, 'attendanceLocation'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // 5. Ambil recent checkouts dari leader dan anggota tim
        $recentCheckouts = \App\Models\Attendance::whereIn('user_id', $allMemberIds)
            ->where('type', 'checkout')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->with(['user.leaderboards' => function($q) { $q->where('category', 'top_points'); }, 'attendanceLocation'])
            ->get();

        // Return dalam bentuk JSON
        return response()->json([
            'active_attendances' => $activeAttendances,
            'recent_checkouts'   => $recentCheckouts,
        ]);
    }

    public function fetchNotifications()
    {
        $user = Auth::user();

        // 1. Cek apakah user tergabung dalam tim
        $teams = $user->teamsJoined; // Relasi ke tabel 'team_members' sudah didefinisikan di model
        $teamIds = $teams->pluck('id');

        if ($teamIds->isEmpty()) {
            // Jika tidak tergabung dalam tim, tampilkan notifikasi milik sendiri saja
            $notifications = \App\Models\Notification::where('user_id', $user->id)
                ->with(['user.leaderboards' => function($q) { $q->where('category', 'top_points'); }])
                ->orderBy('time', 'desc')
                ->take(30)
                ->get();
        } else {
            // Jika tergabung dalam tim, ambil notifikasi dari semua anggota tim
            $memberIds = \DB::table('team_members')
                ->whereIn('team_id', $teamIds)
                ->pluck('user_id')
                ->unique();

            $leaderIds = \DB::table('teams')
                ->whereIn('id', $teamIds)
                ->pluck('leader_id')
                ->unique();

            $allMemberIds = $memberIds->merge($leaderIds)->unique();

            $notifications = \App\Models\Notification::whereIn('user_id', $allMemberIds)
                ->with(['user.leaderboards' => function($q) { $q->where('category', 'top_points'); }])
                ->orderBy('time', 'desc')
                ->take(30)
                ->get();
        }

        // Format notifikasi untuk response
        $formattedNotifications = $notifications->map(function ($notification) {
            $rankData = $notification->user->leaderboards->first();
            return [
                'id'               => $notification->id,
                'message'          => $notification->message,
                'time'             => $notification->time->format('Y-m-d H:i:s'),
                'user_name'        => $notification->user->name,
                'user_avatar'      => $notification->user->avatar,
                'user_member_id'   => $notification->user->member_id,
                'user_rank'        => $rankData ? $rankData->current_rank : 999,
                'user_title'       => $rankData ? $rankData->title : null,
                'user_frame_color' => $rankData ? $rankData->frame_color : '#3b82f6',
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
        ]);
    }
    public function searchRivals(Request $request)
    {
        $query = $request->input("query");
        $users = User::where("id", "!=", Auth::id())
            ->where(function($q) use ($query) {
                $q->where("name", "like", "%{$query}%")
                  ->orWhere("email", "like", "%{$query}%");
            })
            ->limit(10)
            ->with("pointSummary")->get();

        return response()->json($users);
    }


    public function updateRival(Request $request)
    {
        $request->validate([
            'rival_id' => 'nullable|exists:users,id',
        ]);

        $user = Auth::user();
        if ($request->rival_id == $user->id) {
            return back()->with('error', 'You cannot be your own rival!');
        }

        $user->update(['comparison_user_id' => $request->rival_id]);

        return back()->with('success', 'Rival updated successfully!');
    }
}
