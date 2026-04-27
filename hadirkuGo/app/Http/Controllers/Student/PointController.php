<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserPointSummary;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AttendanceLocation;
use App\Models\UserPoint;

class PointController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total points from user_point_summaries table
        $totalPoints = UserPointSummary::where('user_id', $user->id)->value('total_points') ?? 0;

        // Current level based on total points
        $userLevel = Level::where('minimum_points', '<=', $totalPoints)
            ->where(function ($query) use ($totalPoints) {
                $query->where('maximum_points', '>=', $totalPoints)
                    ->orWhereNull('maximum_points');
            })
            ->first();

        // Next level based on points
        $nextLevel = Level::where('minimum_points', '>', $totalPoints)
            ->orderBy('minimum_points')
            ->first();

        // Default level if none found
        if (!$userLevel) {
            $userLevel = (object) [
                'name' => 'Unranked',
                'minimum_points' => 0,
                'maximum_points' => 0,
                'description' => 'Keep earning points to unlock a level!',
                'image_url' => asset('images/default-level.png'),
            ];
        }

        // Fetch all levels for the "View All Levels" modal
        $allLevels = Level::orderBy('minimum_points', 'asc')->get();

        // Fetch points history (latest 100 transactions)
        $pointsHistory = UserPoint::where('user_id', $user->id)
            ->latest()
            ->take(100)
            ->with(['attendance']) // Eager load hanya attendance
            ->get()
            ->map(function ($point) {
                // Default location name
                $point->location_name = 'Unknown Location';

                if ($point->attendance) {
                    // Cari lokasi langsung di tabel attendance_locations
                    $location = AttendanceLocation::find($point->attendance->attendance_location_id);
                    if ($location) {
                        $point->location_name = $location->name;
                    }
                }

                return $point;
            });

        // Separate today's points
        $today = Carbon::today();
        $dailyPointsHistory = $pointsHistory->filter(function ($point) use ($today) {
            return $point->created_at->isSameDay($today);
        });

        // Points history excluding today
        $olderPointsHistory = $pointsHistory->diff($dailyPointsHistory);

        return view('student.points.index', compact(
            'totalPoints',
            'userLevel',
            'nextLevel',
            'dailyPointsHistory',
            'olderPointsHistory',
            'allLevels' // Tambahkan variabel $allLevels ke view
        ));
    }
}
