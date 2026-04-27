<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Attendance::where('user_id', $user->id)
            ->with('attendanceLocation')
            ->orderBy('checkin_time', 'desc');

        // Default to today's date
        $today = Carbon::now()->format('Y-m-d');

        // Check if the 'show_all' parameter is present
        if ($request->has('show_all')) {
            // Apply filters if provided
            if ($request->filled('start_date') && $request->filled('end_date')) {
                if ($request->start_date <= $request->end_date) {
                    $query->whereBetween('checkin_time', [
                        $request->start_date . ' 00:00:00',
                        $request->end_date . ' 23:59:59'
                    ]);
                }
            }

            // Filter by Location
            if ($request->filled('location')) {
                $query->whereHas('attendanceLocation', function ($locationQuery) use ($request) {
                    $locationQuery->where('name', 'like', '%' . $request->location . '%');
                });
            }

            // Search by Keyword
            if ($request->filled('keyword')) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->whereHas('attendanceLocation', function ($locationQuery) use ($request) {
                        $locationQuery->where('name', 'like', '%' . $request->keyword . '%');
                    })->orWhere('checkin_time', 'like', '%' . $request->keyword . '%')
                        ->orWhere('checkout_time', 'like', '%' . $request->keyword . '%');
                });
            }
        } else {
            // Default: Show only today's attendance
            $query->whereDate('checkin_time', $today);
        }

        // Paginate with query string to keep filters during pagination
        $attendances = $query->paginate(10)->withQueryString();

        // If no attendance today, get the last available attendance
        if ($attendances->isEmpty()) {
            $lastAttendance = Attendance::where('user_id', $user->id)
                ->orderBy('checkin_time', 'desc')
                ->first();  // Get the latest attendance
            // Pass both last attendance and an empty attendance collection
            return view('lecturer.attendance_history.index', compact('attendances', 'lastAttendance'));
        }

        // If there's attendance, just return the regular view
        return view('lecturer.attendance_history.index', compact('attendances'));
    }



    public function dailySummary()
    {
        $user = Auth::user();
        $today = Carbon::now();

        // Retrieve attendance data for the past week
        $weekData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $attendancesForDay = Attendance::where('user_id', $user->id)
                ->whereDate('checkin_time', $date)
                ->with('attendanceLocation')
                ->get();

            $dayTotalDuration = $attendancesForDay->sum('duration_at_location');
            $progress = $dayTotalDuration > 0 ? min(($dayTotalDuration / 90) * 100, 100) : 0; // Assuming 90 mins as full progress for example

            $weekData->push([
                'day' => $date->format('D'),
                'date' => $date->format('m/d'),
                'progress' => $progress,
            ]);
        }

        // Retrieve today's attendances
        $attendances = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_time', $today)
            ->with('attendanceLocation')
            ->get();

        // Calculate metrics for today
        $totalLocations = $attendances->unique('attendance_location_id')->count();
        $totalDuration = $attendances->sum('duration_at_location');
        $averageDuration = $totalLocations > 0 ? floor($totalDuration / $totalLocations) : 0;
        $mostVisitedLocation = $attendances->groupBy('attendance_location_id')
            ->sortByDesc(fn($location) => count($location))
            ->first()
            ->first()
            ->attendanceLocation->name ?? 'N/A';
        $longestDurationLocation = $attendances->sortByDesc('duration_at_location')->first();

        return view('lecturer.daily.daily_summary', compact(
            'weekData',
            'attendances',
            'totalLocations',
            'totalDuration',
            'averageDuration',
            'mostVisitedLocation',
            'longestDurationLocation'
        ));
    }

}