<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLeaderboard;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceLeaderboardController extends Controller
{
    /**
     * Menghitung dan menyimpan data Morning Person, Last Person, dan Longest Duration
     * untuk setiap tim pada setiap hari.
     */
    public function calculateLeaderboard(Request $request, $team_unique_id)
    {
        // Ambil data tim berdasarkan unique_id
        $team = Team::where('team_unique_id', $team_unique_id)->firstOrFail();

        // Ambil data tanggal yang dipilih, atau gunakan tanggal hari ini
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));

        // Query attendance berdasarkan user yang tergabung dalam tim tertentu pada tanggal yang dipilih
        $attendances = DB::table('attendances')
            ->join('team_members', 'attendances.user_id', '=', 'team_members.user_id')
            ->join('teams', 'team_members.team_id', '=', 'teams.id')
            ->where('teams.team_unique_id', $team->team_unique_id)
            ->whereDate('attendances.checkin_time', $date)
            ->select('attendances.*', 'attendances.user_id', 'attendances.checkin_time', 'attendances.total_daily_duration')
            ->get();

        // Pastikan ada data kehadiran untuk tanggal tersebut
        if ($attendances->isEmpty()) {
            return response()->json(['message' => 'No attendance records for this day.'], 404);
        }

        // Menghitung siapa yang paling pagi (Morning Person)
        $morningPerson = $attendances->sortBy(function ($attendance) {
            return $attendance->checkin_time;
        })->first();

        // Menghitung siapa yang paling terlambat (Last Person)
        $lastPerson = $attendances->sortByDesc(function ($attendance) {
            return $attendance->checkin_time;
        })->first();

        // Menghitung durasi kegiatan terlama (Longest Duration)
        $longestDurationAttendance = $attendances->sortByDesc(function ($attendance) {
            return $attendance->total_daily_duration; // Anggap total_daily_duration sudah ada di model Attendance
        })->first();

        // Simpan data ke tabel AttendanceLeaderboard
        AttendanceLeaderboard::updateOrCreate(
            [
                'user_id' => $morningPerson->user_id,
                'team_id' => $team->id,
                'date' => $date,
            ],
            [
                'morning_person' => true,
                'last_person' => false,
                'longest_duration' => $longestDurationAttendance->total_daily_duration ?? 0
            ]
        );

        AttendanceLeaderboard::updateOrCreate(
            [
                'user_id' => $lastPerson->user_id,
                'team_id' => $team->id,
                'date' => $date,
            ],
            [
                'morning_person' => false,
                'last_person' => true,
                'longest_duration' => $longestDurationAttendance->total_daily_duration ?? 0
            ]
        );

        // Jika ada durasi paling lama, simpan data untuk Longest Duration
        if ($longestDurationAttendance) {
            AttendanceLeaderboard::updateOrCreate(
                [
                    'user_id' => $longestDurationAttendance->user_id,
                    'team_id' => $team->id,
                    'date' => $date,
                ],
                [
                    'morning_person' => false,
                    'last_person' => false,
                    'longest_duration' => $longestDurationAttendance->total_daily_duration
                ]
            );
        }

        // Response sukses
        return response()->json([
            'message' => 'Leaderboard updated successfully!',
            'morning_person' => $morningPerson->user_id,
            'last_person' => $lastPerson->user_id,
            'longest_duration_user' => $longestDurationAttendance->user_id
        ]);
    }

    /**
     * Menampilkan leaderboard berdasarkan tim dan tanggal tertentu
     */
    public function showLeaderboard($team_unique_id, $date)
    {
        $team = Team::where('team_unique_id', $team_unique_id)->firstOrFail();

        // Ambil data leaderboard berdasarkan tim dan tanggal
        $leaderboards = DB::table('attendance_leaderboards')
            ->where('team_id', $team->id)
            ->where('date', $date)
            ->join('users', 'attendance_leaderboards.user_id', '=', 'users.id')
            ->select('attendance_leaderboards.*', 'users.name as user_name')
            ->get();

        if ($leaderboards->isEmpty()) {
            return response()->json(['message' => 'No leaderboard data available for this day.'], 404);
        }

        return response()->json($leaderboards);
    }
}
