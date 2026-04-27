<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use App\Models\UserLevel;
use Illuminate\Support\Facades\DB;

class UserLevelController extends Controller
{
    /**
     * Periksa level pengguna berdasarkan total poin dan kembalikan hasil dalam format JSON.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserLevels(Request $request)
    {
        DB::beginTransaction();

        try {
            $levels = Level::orderBy('minimum_points', 'asc')->get();
            $leveledUpUsers = [];
            $assignedInitialLevelUsers = [];
            $congratulatoryMessages = [];

            User::with('pointSummary', 'userLevel.level')->chunk(100, function ($users) use ($levels, &$leveledUpUsers, &$assignedInitialLevelUsers, &$congratulatoryMessages) {
                foreach ($users as $user) {
                    if (!$user->pointSummary) {
                        continue;
                    }

                    $totalPoints = $user->pointSummary->total_points;

                    // Cari level yang sesuai berdasarkan total poin
                    $newLevel = $levels->first(function ($level) use ($totalPoints) {
                        return $totalPoints >= $level->minimum_points;
                    });

                    if (!$newLevel) {
                        continue;
                    }

                    $currentUserLevel = $user->userLevel;
                    $currentLevel = $currentUserLevel ? $currentUserLevel->level : null;

                    if ($currentLevel) {
                        // Jika level sebelumnya ada, cek apakah level baru berbeda
                        if ($currentLevel->id !== $newLevel->id) {
                            // Update level pengguna
                            $currentUserLevel->level_id = $newLevel->id;
                            $currentUserLevel->save();

                            // Cek apakah level baru bukan level I (misalnya Pioneer II, Pioneer III, dst.)
                            if (!preg_match('/\sI$/', $newLevel->name)) {
                                $congratulatoryMessages[] = "Selamat kepada {$user->name} telah naik level dari {$currentLevel->name} ke {$newLevel->name}!";
                            }

                            $leveledUpUsers[] = [
                                'user_id' => $user->id,
                                'name' => $user->name,
                                'old_level' => $currentLevel->name,
                                'new_level' => $newLevel->name,
                                'total_points' => $totalPoints,
                            ];
                        }
                    } else {
                        // Jika level sebelumnya tidak ada, assign level awal
                        $user->userLevel()->create([
                            'level_id' => $newLevel->id,
                        ]);

                        $assignedInitialLevelUsers[] = [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'assigned_level' => $newLevel->name,
                            'total_points' => $totalPoints,
                        ];
                    }
                }
            });

            DB::commit();

            return response()->json([
                'status' => 'success',
                'leveled_up_users' => $leveledUpUsers,
                'assigned_initial_level_users' => $assignedInitialLevelUsers,
                'congratulatory_messages' => $congratulatoryMessages,
                'total_leveled_up' => count($leveledUpUsers),
                'total_assigned_initial_level' => count($assignedInitialLevelUsers),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memeriksa level pengguna.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}