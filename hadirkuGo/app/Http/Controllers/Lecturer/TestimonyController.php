<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Testimony;
use App\Models\Reward;
use App\Models\UserReward;
use App\Models\Attendance;
use App\Models\UserPoint;
use App\Models\UserPointSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestimonyController extends Controller
{
    /**
     * Menampilkan halaman success attendance dengan testimoni dan reward
     */
    public function success()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        if (!$user) {
            return $this->showTestingViewWithError($user, 'User not found.');
        }

        // Cek apakah user sudah memberikan testimoni
        $testimony = Testimony::where('user_id', $user->id)->first();

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

            // Periksa apakah durasi sesi memenuhi syarat (minimal 360 menit)
            if ($sessionDuration >= 360) {
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
                return $this->showTestingViewWithError($user, 'No rewards available in system.');
            }

            // Array untuk menyimpan reward yang dipilih
            $selectedRewards = [];

            // Berikan reward untuk setiap sesi yang memenuhi syarat
            foreach ($eligibleSessions as $session) {
                // Pilih reward berdasarkan probabilitas dan keadilan
                $selectedReward = $this->selectReward($user, $allRewards);

                // Jika tidak ada reward yang dipilih (fallback)
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

                // Pastikan reward memiliki poin yang valid
                if (is_null($selectedReward->points)) {
                    DB::rollBack();
                    return $this->showTestingViewWithError($user, 'Reward points are missing.');
                }

                // Tambahkan poin ke tabel user_points
                UserPoint::create([
                    'user_id'       => $user->id,
                    'points'        => $selectedReward->points, // Gunakan points dari reward
                    'description'   => 'Received reward: ' . $selectedReward->name,
                ]);

                // Update atau buat entri di user_point_summaries
                $pointSummary = UserPointSummary::firstOrNew(['user_id' => $user->id]);
                $pointSummary->total_points += $selectedReward->points; // Tambahkan ke total poin
                $pointSummary->current_points += $selectedReward->points; // Tambahkan ke poin saat ini
                $pointSummary->save();
            }

            // Commit transaksi database
            DB::commit();

            // Ambil semua data reward untuk ditampilkan di view
            $rewards = Reward::all();

            // Kirim data testimoni, selectedRewards, dan rewards ke view
            return view('lecturer.attendance.success', [
                'rewards' => $rewards,
                'selectedRewards' => $selectedRewards,
                'testimony' => $testimony,
                'quote' => \App\Models\Quote::inRandomOrder()->first(),
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

        // Urutkan reward dari yang terkecil ke terbesar untuk akumulasi probabilitas
        $sortedRewards = $allRewards->sortBy('probability');

        foreach ($sortedRewards as $reward) {
            $prob = $reward->probability;

            // JIKA user sudah pernah dapat hadiah besar, potong peluang hadiah besar lainnya drastis
            if ($hasRecentBigReward && $reward->points >= 100) {
                $prob = $prob * 0.05; // Potong peluang jadi hanya 5% dari aslinya
            }

            $cumulative += $prob;
            if ($randomNumber <= $cumulative) {
                $pickedReward = $reward;
                break;
            }
        }

        // 3. Fallback & Stock Check
        // Jika tidak ada yang terpilih (karena probabilitas tidak mencapai 100%) 
        // atau stok hadiah yang terpilih habis, berikan hadiah hiburan (poin terkecil)
        $consolationReward = $allRewards->where('points', '>', 0)->sortBy('points')->first();

        if (!$pickedReward || $pickedReward->quantity <= 0) {
            return $consolationReward;
        }

        return $pickedReward;
    }

    /**
     * Tampilkan halaman testing dengan pesan error
     */
    private function showTestingViewWithError($user, $errorMessage)
    {
        // Ambil semua data reward untuk ditampilkan di view
        $rewards = Reward::all();

        // Ambil testimoni terbaru user (jika ada)
        $testimony = $user ? $user->testimonies()->latest()->first() : null;

        // Kirim data reward, testimoni, dan pesan error ke view
        return view('lecturer.attendance.success', [
            'rewards' => $rewards,
            'testimony' => $testimony,
            'error' => $errorMessage,
            'quote' => \App\Models\Quote::inRandomOrder()->first(),
        ]);
    }

    /**
     * Menyimpan testimoni baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'testimony' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        // Simpan testimoni dengan user_id dari user yang sedang login
        $testimony = new Testimony();
        $testimony->user_id = Auth::id(); // Ambil ID user yang sedang login
        $testimony->testimony = $request->testimony;
        $testimony->rating = $request->rating;
        $testimony->save();

        return redirect()->route('lecturer.attendance.success')->with('success', 'Testimoni berhasil disimpan!');
    }

    /**
     * Menampilkan daftar testimoni
     */
    public function index()
    {
        $testimonies = Testimony::with('user')->get();
        return response()->json($testimonies);
    }

    /**
     * Menampilkan detail testimoni
     */
    public function show($id)
    {
        $testimony = Testimony::with('user')->findOrFail($id);
        return response()->json($testimony);
    }

    /**
     * Mengupdate testimoni
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'testimony' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        // Find the testimony to be updated
        $testimony = Testimony::findOrFail($id);

        // Update the testimony
        $testimony->testimony = $request->testimony;
        $testimony->rating = $request->rating;
        $testimony->save();

        // Redirect back with a success message
        return redirect()->route('lecturer.attendance.success')->with('success', 'Testimonial updated successfully!');
    }

    /**
     * Menghapus testimoni
     */
    public function destroy($id)
    {
        $testimony = Testimony::findOrFail($id);
        $testimony->delete();
        return response()->json(null, 204);
    }

    /**
     * Menampilkan testimoni berdasarkan user tertentu
     */
    public function getUserTestimonies($userId)
    {
        $user = User::findOrFail($userId);
        $testimonies = $user->testimonies;
        return response()->json($testimonies);
    }

}