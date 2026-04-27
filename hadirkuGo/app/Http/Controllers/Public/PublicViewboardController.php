<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PublicViewboardController extends Controller
{
    /**
     * Mendapatkan rentang waktu berdasarkan periode.
     *
     * @param string $period
     * @return array
     */
    protected function getDateRange($period)
    {
        $now = Carbon::now();
        switch ($period) {
            case 'weekly':
                return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
            case 'monthly':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'yearly':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            default:
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
        }
    }

    /**
     * Mendapatkan data ranking berdasarkan durasi dan sesi.
     *
     * @param array $dateRange
     * @return \Illuminate\Support\Collection
     */
    protected function getRankings($dateRange)
    {
        return Attendance::whereBetween('checkin_time', $dateRange)
            ->whereHas('user') // Pastikan relasi user ada
            ->select(
                'user_id',
                DB::raw('COUNT(*) as session_count'), // Jumlah sesi
                DB::raw('SUM(duration_at_location) as total_duration') // Total durasi
            )
            ->groupBy('user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'avatar'); // Ambil nama dan avatar pengguna
            }])
            ->get()
            ->sortByDesc(function ($item) {
                return [$item->total_duration, $item->session_count]; // Urutkan durasi dan sesi
            })
            ->values(); // Reset keys setelah sorting
    }

    /**
     * Mendapatkan data viewboard publik.
     *
     * @return array
     */
    protected function getPublicViewboardData()
    {
        $dateRange = $this->getDateRange('daily'); // Gunakan rentang waktu harian
        $rankings = $this->getRankings($dateRange);

        return [
            'rankings' => $rankings,
        ];
    }

    /**
     * Menampilkan data viewboard publik dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function api()
    {
        $data = $this->getPublicViewboardData();
        return response()->json($data);
    }

    /**
     * Menampilkan viewboard publik.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = $this->getPublicViewboardData();
        return view('public.viewboard', compact('data'));
    }
}