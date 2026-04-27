<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserStatistic;
use App\Models\AttendanceLocation;

class StatisticsController extends Controller
{
    /**
     * Menampilkan halaman statistik pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data statistik pengguna
        $statistics = UserStatistic::where('user_id', $user->id)->first();

        // Jika statistik belum tersedia, tampilkan pesan
        if (!$statistics) {
            return view('lecturer.statistics.index')->with('message', 'Statistics data is not available yet. Please check back later.');
        }

        // Ambil data lokasi yang paling sering dan paling jarang dikunjungi
        $mostFrequentLocation = $statistics->mostFrequentLocation;
        $leastFrequentLocation = $statistics->leastFrequentLocation;

        // Ambil semua lokasi yang pernah dikunjungi
        $allVisitedLocations = [];
        if ($statistics->all_visited_locations) {
            $allVisitedLocations = AttendanceLocation::whereIn('id', $statistics->all_visited_locations)->get();
        }

        return view('lecturer.statistics.index', compact(
            'statistics',
            'mostFrequentLocation',
            'leastFrequentLocation',
            'allVisitedLocations'
        ));
    }
}
