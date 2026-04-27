<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Show the attendance calendar.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCalendar(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        // Get current month and year
        $month = now()->month; // Current month
        $year = now()->year;  // Current year

        // Generate the first and last day of the current month
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Generate a range of dates for the current month
        $dates = [];
        foreach ($startOfMonth->toPeriod($endOfMonth) as $date) {
            $dates[] = $date->toDateString();
        }

        // Query attendance records for the user, grouping by day
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('checkin_time', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($attendance) {
                return $attendance->checkin_time->toDateString(); // Group by date (Y-m-d)
            });

        // Map each date to whether attendance was recorded on that day
        $attendanceStatus = [];
        foreach ($dates as $date) {
            $attendanceStatus[$date] = isset($attendances[$date]) ? '✔️' : '❌';
        }

        // Return the calendar view with the attendance status for the current month and year
        return view('student.calendar.index', compact('attendanceStatus', 'dates', 'month', 'year'));
    }


    /**
     * Fetch the attendance details for a specific date.
     *
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceDetails($date)
    {
        $user = auth()->user(); // Get the authenticated user

        // Fetch the attendance details for the specific user and date
        $attendances = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_time', $date)
            ->with('attendanceLocation')  // Eager load the attendance location relationship
            ->get();

        // If there are attendance records, return the necessary details
        if ($attendances->isNotEmpty()) {
            $details = $attendances->map(function ($attendance) {
                return [
                    'checkin_time' => $attendance->checkin_time->format('H:i'),  // Format check-in time
                    'checkout_time' => $attendance->checkout_time ? $attendance->checkout_time->format('H:i') : 'N/A',  // Format checkout time
                    'location_name' => $attendance->attendanceLocation ? $attendance->attendanceLocation->name : 'Unknown Location', // Location name
                ];
            });

            return response()->json([
                'status' => 'success',
                'details' => $details
            ]);
        }

        // If no attendance found for the date
        return response()->json([
            'status' => 'no_attendance',
            'message' => 'No attendance recorded for this day.'
        ]);
    }



}
