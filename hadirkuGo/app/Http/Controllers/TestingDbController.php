<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestingDbController extends Controller
{
    public function getUserPointSummary($userId)
    {
        // Catat waktu mulai dan penggunaan memori awal
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            // Ambil data user dengan relasi yang diperlukan
            $user = User::with([
                'userPoints' => function ($query) {
                    $query->latest()->limit(1); // Ambil point terakhir
                },
                'pointSummary'
            ])->findOrFail($userId);

            // Hitung total points jika pointSummary belum ada
            $totalPoints = $user->pointSummary
                ? $user->pointSummary->total_points
                : $user->userPoints()->sum('points');

            // Ambil level berdasarkan total points
            $currentLevel = Level::where('minimum_points', '<=', $totalPoints)
                ->where('maximum_points', '>=', $totalPoints)
                ->first();

            // Ambil data point terakhir
            $lastPoint = $user->userPoints->first();

            // Siapkan response
            $response = [
                'status' => 'success',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'points' => [
                        'total_points' => $totalPoints,
                        'last_point' => $lastPoint ? [
                            'points' => $lastPoint->points,
                            'description' => $lastPoint->description,
                            'received_at' => $lastPoint->created_at
                        ] : null,
                    ],
                    'level' => $currentLevel ? [
                        'id' => $currentLevel->id,
                        'name' => $currentLevel->name,
                        'minimum_points' => $currentLevel->minimum_points,
                        'maximum_points' => $currentLevel->maximum_points,
                        'current_points' => $totalPoints
                    ] : null
                ]
            ];

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

        // Hitung waktu eksekusi dan penggunaan memori
        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = $endTime - $startTime;
        $memoryUsed = ($endMemory - $startMemory) / 1024 / 1024; // Konversi ke MB

        // Tambahkan metadata performa
        $response['performance'] = [
            'execution_time' => number_format($executionTime, 4) . ' seconds',
            'memory_used' => number_format($memoryUsed, 4) . ' MB',
            'queries_executed' => DB::getQueryLog() ? count(DB::getQueryLog()) : 0
        ];

        return response()->json($response);
    }
}