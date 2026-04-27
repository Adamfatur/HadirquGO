<?php

namespace App\Http\Controllers\student;

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
        // Temukan user berdasarkan ID
        $user = User::find($userId);

        if (!$user) {
            return $this->showTestingViewWithError($user, 'User not found.');
        }

        // Ambil semua sesi yang sudah selesai (checkin-checkout) untuk user hari ini
        $sessionsToday = $user->attendances()
            ->whereDate('checkin_time', today()) // Filter berdasarkan hari ini
            ->whereNotNull('checkout_time') // Hanya sesi yang sudah selesai
            ->get();

        // Jika tidak ada sesi yang selesai hari ini
        if ($sessionsToday->isEmpty()) {
            return $this->showTestingViewWithError($user, 'No completed sessions found today.');
        }

        // Periksa setiap sesi dan hitung sesi yang memenuhi syarat
        $eligibleSessions = [];
        foreach ($sessionsToday as $session) {
            // Hitung durasi sesi dalam menit
            $sessionDuration = $session->checkin_time->diffInMinutes($session->checkout_time);

            // Periksa apakah durasi sesi memenuhi syarat (minimal 1 menit)
            if ($sessionDuration >= 1) {
                // Periksa apakah reward sudah diberikan untuk attendance_id ini
                $alreadyReceivedReward = UserReward::where('attendance_id', $session->id)->exists();
                if (!$alreadyReceivedReward) {
                    $eligibleSessions[] = $session;
                }
            }
        }

        // Jika tidak ada sesi yang memenuhi syarat
        if (empty($eligibleSessions)) {
            return $this->showTestingViewWithError($user, 'No eligible sessions found. You may have already received rewards for all sessions today.');
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil semua reward untuk perhitungan probabilitas yang adil
            $allRewards = Reward::all();

            if ($allRewards->isEmpty()) {
                return $this->showTestingViewWithError($user, 'No rewards available.');
            }

            // Array untuk menyimpan reward yang dipilih
            $selectedRewards = [];

            // Berikan reward untuk setiap sesi yang memenuhi syarat
            foreach ($eligibleSessions as $session) {
                // Pilih reward berdasarkan probabilitas dan keadilan
                $selectedReward = $this->selectReward($user, $allRewards);

                // Jika tidak ada reward yang dipilih
                if (!$selectedReward) {
                    DB::rollBack();
                    return $this->showTestingViewWithError($user, 'Failed to select a reward.');
                }

                // Kurangi stok reward jika masih ada
                if ($selectedReward->quantity > 0) {
                    $selectedReward->decrement('quantity');
                }

                // Simpan reward yang diterima oleh user
                UserReward::create([
                    'user_id'       => $user->id,
                    'reward_id'     => $selectedReward->id,
                    'received_at'   => now(),
                    'attendance_id' => $session->id, // Hubungkan reward dengan sesi
                ]);

                // Tambahkan reward yang dipilih ke array
                $selectedRewards[] = $selectedReward;
            }

            // Commit transaksi database
            DB::commit();

            // Ambil semua data reward untuk ditampilkan di view
            $rewards = Reward::all();

            // Ambil testimoni terbaru user (jika ada)
            $testimony = $user->testimonies()->latest()->first();

            // Kirim data reward, reward yang dipilih, dan testimoni ke view
            return view('student.attendance.testing', [
                'rewards' => $rewards,
                'selectedReward' => $selectedRewards[0], // Ambil reward pertama
                'testimony' => $testimony,
            ]);

        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi error
            DB::rollBack();

            return $this->showTestingViewWithError($user, 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Pilih reward berdasarkan probabilitas dengan logika keadilan (Fairness Logic)
     */
    private function selectReward($user, $allRewards)
    {
        // 1. Cek riwayat hadiah besar user dalam 7 hari terakhir (>= 100 poin)
        $hasRecentBigReward = UserReward::where('user_id', $user->id)
            ->whereHas('reward', function($q) {
                $q->where('points', '>=', 100);
            })
            ->where('received_at', '>=', now()->subDays(7))
            ->exists();

        // 2. Gunakan skala probabilitas tetap (100%) agar tidak bergantung stok
        $totalScale = 100;
        $randomNumber = mt_rand(0, $totalScale * 100) / 100;
        $cumulative = 0;
        $pickedReward = null;

        $sortedRewards = $allRewards->sortBy('probability');

        foreach ($sortedRewards as $reward) {
            $prob = $reward->probability;

            // JIKA user sudah pernah dapat hadiah besar, potong peluang hadiah besar lainnya drastis
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

        // Get the list of rewards received by the user with attendance information
        $rewards = $user->rewards()
            ->with(['reward', 'attendance']) // Load reward and attendance data
            ->get();

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

        // Get the latest reward received by the user with attendance information
        $lastReward = $user->rewards()
            ->with(['reward', 'attendance']) // Load reward and attendance data
            ->latest()
            ->first();

        // Get the user's latest testimony (if any)
        $testimony = $user->testimonies()->latest()->first();

        // Send data to the view
        return view('student.attendance.testing', [
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
        return view('student.attendance.testing', [
            'rewards' => $rewards,
            'testimony' => $testimony,
            'error' => $errorMessage,
        ]);
    }
}