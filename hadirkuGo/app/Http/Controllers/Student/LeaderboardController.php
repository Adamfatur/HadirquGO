<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class LeaderboardController extends Controller
{
    /**
     * Show the leaderboard (merging all attendance into one global total for each user).
     */
    public function index(Request $request)
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user();

        // 2. Cari business ID yang terkait user ini (baik dari teamsJoined atau teamsLed)
        $businessIdsJoined = $user->teamsJoined()->pluck('business_id')->unique();
        $businessIdsLed    = $user->teamsLed()->pluck('business_id')->unique();
        $businessId        = $businessIdsJoined->merge($businessIdsLed)->first();

        // Jika user tidak punya business sama sekali, tampilkan error
        if (!$businessId) {
            return back()->withErrors(['error' => 'No business found for the user.']);
        }

        // 3. Tentukan periode (daily, weekly, monthly, dsb.)
        $period    = $request->input('period', 'weekly'); // default weekly
        $dateRange = $this->getDateRange($period);

        // 4. Ambil semua user di bisnis yang sama (dari tim bergabung/menjadi leader)
        //    Lalu petakan data user-nya
        $users = User::whereHas('teamsJoined', function ($query) use ($businessId) {
            $query->where('business_id', $businessId);
        })
            ->orWhereHas('teamsLed', function ($query) use ($businessId) {
                $query->where('business_id', $businessId);
            })
            ->get()
            ->map(function ($someUser) use ($dateRange) {
                // Tentukan rolenya (Student / Lecturer)
                $role = $someUser->hasRole('lecturer') ? 'Lecturer' : 'Student';

                // Hitung global achievement user (tidak peduli tim)
                $achievement = $this->calculateUserAchievement($someUser, $dateRange);

                return [
                    'user'                      => $someUser,
                    'role'                      => $role,
                    'team_name'                 => 'Multiple/All Teams', // Atau 'N/A'
                    'total_points'              => $achievement['total_points'],
                    'attendance_count'          => $achievement['attendance_count'],
                    'total_duration'            => $achievement['total_daily_duration'],
                    'formatted_total_duration'  => $this->formatDuration($achievement['total_daily_duration']),
                ];
            })
            ->filter(function ($userData) {
                // Tampilkan user meski total_points = 0
                return $userData['total_points'] >= 0;
            })
            // Sort descending by total_points, lalu attendance_count, lalu total_duration
            ->sort(function ($a, $b) {
                if ($a['total_points'] === $b['total_points']) {
                    if ($a['attendance_count'] === $b['attendance_count']) {
                        return $b['total_duration'] <=> $a['total_duration'];
                    }
                    return $b['attendance_count'] <=> $a['attendance_count'];
                }
                return $b['total_points'] <=> $a['total_points'];
            })
            ->values();

        return view('student.leaderboard.index', compact('users', 'period'));
    }

    /**
     * Show the team leaderboard (setiap tim mendapat akumulasi attendance
     * dari semua member, TANPA filter team_id).
     *
     * Artinya jika user A tergabung di Tim X dan Tim Y,
     * 1 jam / 1 sesi user A masuk ke dua tim tersebut.
     */
    public function teamRanking(Request $request)
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user();

        // 2. Cari business ID
        $businessIdsJoined = $user->teamsJoined()->pluck('business_id')->unique();
        $businessIdsLed    = $user->teamsLed()->pluck('business_id')->unique();
        $businessId        = $businessIdsJoined->merge($businessIdsLed)->first();

        if (!$businessId) {
            return back()->withErrors(['error' => 'No business found for the user.']);
        }

        // 3. Misal kita ambil data 3 bulan terakhir
        $threeMonthsAgo = now()->subMonths(3);

        // 4. Ambil semua tim di bisnis ini
        $teams = Team::where('business_id', $businessId)->get()
            ->map(function ($team) use ($threeMonthsAgo) {
                // Kita kumpulkan data KESELURUHAN attendance user
                // (yang tergabung di team ini) TAPI ignoring team_id.
                // Sehingga jika user bergabung di 2 tim, attendance-nya
                // masuk ke keduanya.

                // 1) Dapatkan ID user yg tergabung di tim ini
                $userIds = $team->members()->pluck('users.id');
                // 'members' = relasi belongsToMany di model Team (teams vs users)

                // 2) Hitung total (tanpa filter team_id)
                //    -> Contoh: Ambil semua Attendance user² ini
                //       di rentang 3 bulan terakhir
                $allAttendances = \App\Models\Attendance::whereIn('user_id', $userIds)
                    ->where('created_at', '>=', $threeMonthsAgo)
                    ->get();

                $totalPoints    = $allAttendances->sum('points');
                $attendanceCount = $allAttendances->count();
                $totalDurationInMinutes = $allAttendances
                    ->whereNotNull('total_daily_duration')
                    ->sum('total_daily_duration');

                return [
                    'team_name'                 => $team->name,
                    'total_points'              => $totalPoints,
                    'attendance_count'          => $attendanceCount,
                    'total_duration_in_minutes' => $totalDurationInMinutes,
                    'formatted_total_duration'  => $this->formatDuration($totalDurationInMinutes),
                ];
            })
            ->filter(function ($teamData) {
                // Tampilkan tim meski poinnya 0
                return $teamData['total_points'] >= 0;
            })
            ->sort(function ($a, $b) {
                // Urutkan: total_points desc, attendance_count desc, total_duration desc
                if ($a['total_points'] === $b['total_points']) {
                    if ($a['attendance_count'] === $b['attendance_count']) {
                        return $b['total_duration_in_minutes'] <=> $a['total_duration_in_minutes'];
                    }
                    return $b['attendance_count'] <=> $a['attendance_count'];
                }
                return $b['total_points'] <=> $a['total_points'];
            })
            ->values();

        return view('student.leaderboard.team', compact('teams'));
    }

    /**
     * Hitung "global" achievement user tanpa memandang tim_id sama sekali.
     */
    private function calculateUserAchievement(User $user, array $dateRange)
    {
        // dateRange adalah [start, end]
        $query = \App\Models\Attendance::where('user_id', $user->id)
            ->whereBetween('created_at', $dateRange);

        // Kumpulkan
        $totalPoints         = $query->sum('points');
        $attendanceCount     = $query->count();
        $totalDailyDuration  = $query->whereNotNull('total_daily_duration')->sum('total_daily_duration');

        return [
            'total_points'           => $totalPoints,
            'attendance_count'       => $attendanceCount,
            'total_daily_duration'   => $totalDailyDuration,
        ];
    }

    /**
     * Format durasi (menit) menjadi "X hours Y minutes".
     */
    private function formatDuration($totalDurationInMinutes)
    {
        $hours   = intdiv($totalDurationInMinutes, 60);
        $minutes = $totalDurationInMinutes % 60;

        return sprintf('%d hours %d minutes', $hours, $minutes);
    }

    /**
     * Mendapatkan rentang tanggal (start, end) sesuai periode:
     * - daily   : hari ini
     * - weekly  : senin minggu ini - minggu akhir
     * - monthly : awal bulan ini - akhir bulan ini
     * - yearly  : awal tahun ini - akhir tahun ini
     */
    private function getDateRange(string $period): array
    {
        $now = now();

        switch ($period) {
            case 'daily':
                return [
                    $now->copy()->startOfDay(),
                    $now->copy()->endOfDay(),
                ];

            case 'weekly':
                return [
                    $now->copy()->startOfWeek(Carbon::MONDAY),
                    $now->copy()->endOfWeek(Carbon::SUNDAY),
                ];

            case 'monthly':
                return [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth(),
                ];

            case 'yearly':
                return [
                    $now->copy()->startOfYear(),
                    $now->copy()->endOfYear(),
                ];

            default:
                // default: weekly
                return [
                    $now->copy()->startOfWeek(Carbon::MONDAY),
                    $now->copy()->endOfWeek(Carbon::SUNDAY),
                ];
        }
    }
}