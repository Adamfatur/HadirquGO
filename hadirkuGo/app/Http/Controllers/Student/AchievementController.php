<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Attendance;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil semua pencapaian yang tersedia
        $allAchievements = Achievement::all();

        // Mengambil pencapaian yang telah diraih oleh user saat ini
        $unlockedAchievements = UserAchievement::with('achievement')
            ->where('user_id', $user->id)
            ->get();

        // Tanggal hari ini
        $today = now()->startOfDay();

        // **Daily MP Mission**
        $dailyMPWinner = UserAchievement::with('user')
            ->where('achievement_id', Achievement::where('name', 'Daily MP')->value('id'))
            ->whereDate('achieved_at', $today)
            ->first();

        $dailyMissionStatus['dailyMP'] = [
            'isAchieved' => $dailyMPWinner && $dailyMPWinner->user_id === $user->id,
            'isFailed' => $dailyMPWinner && $dailyMPWinner->user_id !== $user->id,
            'winnerName' => $dailyMPWinner ? $dailyMPWinner->user->name : null,
        ];

        // **Longest Activity Mission**
        $longestActivityWinner = UserAchievement::with('user')
            ->where('achievement_id', Achievement::where('name', 'Longest Activity')->value('id'))
            ->whereDate('achieved_at', $today)
            ->first();

        $userDuration = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_time', $today)
            ->sum('total_daily_duration');

        $longestDuration = $longestActivityWinner
            ? Attendance::where('user_id', $longestActivityWinner->user_id)
                ->whereDate('checkin_time', $today)
                ->sum('total_daily_duration')
            : 0;

        $dailyMissionStatus['longestActivity'] = [
            'isAchieved' => $longestActivityWinner && $longestActivityWinner->user_id === $user->id,
            'isFailed' => $longestActivityWinner && $longestActivityWinner->user_id !== $user->id,
            'winnerName' => $longestActivityWinner ? $longestActivityWinner->user->name : null,
            'winnerDuration' => $longestDuration,
            'progress' => $userDuration,
        ];

        // **Explorer Mission (Adventure Student)**
        $uniqueLocationsCount = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_time', $today)
            ->distinct('attendance_location_id') // Menghitung lokasi unik tanpa batasan waktu
            ->count('attendance_location_id'); // Pastikan menghitung lokasi unik

        $explorerWinner = UserAchievement::with('user')
            ->where('achievement_id', Achievement::where('name', 'Adventure Student')->value('id'))
            ->whereDate('achieved_at', $today)
            ->first();

        $dailyMissionStatus['visitLocations'] = [
            'isAchieved' => $uniqueLocationsCount >= 5, // Berhasil jika mengunjungi 5 lokasi unik
            'isFailed' => $explorerWinner && $explorerWinner->user_id !== $user->id,
            'locationsVisited' => $uniqueLocationsCount,
            'winnerName' => $explorerWinner ? $explorerWinner->user->name : null,
        ];

        return view('student.achievements.index', compact(
            'allAchievements',
            'unlockedAchievements',
            'dailyMissionStatus'
        ));
    }

    public function showDetails($achievementId)
    {
        $user = Auth::user();

        // Pastikan pencapaian itu valid
        $achievement = Achievement::findOrFail($achievementId);

        // Ambil semua data pencapaian user berdasarkan achievement_id
        $userAchievements = UserAchievement::where('user_id', $user->id)
            ->where('achievement_id', $achievementId)
            ->get();

        // Format data untuk response JSON
        $details = $userAchievements->map(function ($record) {
            return [
                'date' => $record->achieved_at->format('d M Y'),
                'time' => $record->achieved_at->format('H:i'),
            ];
        });

        return response()->json([
            'achievement' => [
                'name' => $achievement->name,
            ],
            'details' => $details,
        ]);
    }
}