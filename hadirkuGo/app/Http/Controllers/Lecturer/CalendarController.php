<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // 1. Tambahkan ini
use Illuminate\Support\Facades\Log;

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
        $user = auth()->user();

        // Ambil bulan dan tahun dari request, atau gunakan bulan dan tahun saat ini
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $dates = [];
        foreach ($startOfMonth->toPeriod($endOfMonth) as $date) {
            $dates[] = $date->toDateString();
        }

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('checkin_time', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($attendance) {
                return $attendance->checkin_time->toDateString();
            });

        $attendanceStatus = [];
        foreach ($dates as $date) {
            $attendanceStatus[$date] = isset($attendances[$date]) ? '✔️' : '❌';
        }

        // 3. Ambil data hari libur dan kirim ke view
        $publicHolidays = $this->getPublicHolidays($year, $month);

        return view('lecturer.calendar.index', compact('attendanceStatus', 'dates', 'month', 'year', 'publicHolidays'));
    }

    /**
     * 2. Buat fungsi baru untuk mengambil data hari libur.
     * Fetches public holidays from libur.deno.dev API.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function getPublicHolidays(int $year, int $month): array
    {
        try {
            $response = Http::get('https://libur.deno.dev/api', [
                'year' => $year,
                'month' => $month,
            ]);

            if ($response->successful() && !empty($response->json())) {
                // Mengubah array of objects menjadi array asosiatif [date => name]
                return collect($response->json())->pluck('name', 'date')->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch public holidays', ['error' => $e->getMessage()]);
        }
        return []; // Return array kosong jika gagal
    }


    /**
     * Fetch the attendance details for a specific date.
     *
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceDetails($date)
    {
        $user = auth()->user();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereDate('checkin_time', $date)
            ->with('attendanceLocation')
            ->get();

        if ($attendances->isNotEmpty()) {
            $details = $attendances->map(function ($attendance) {
                return [
                    'checkin_time' => $attendance->checkin_time->format('H:i'),
                    'checkout_time' => $attendance->checkout_time ? $attendance->checkout_time->format('H:i') : 'N/A',
                    'location_name' => $attendance->attendanceLocation ? $attendance->attendanceLocation->name : 'Unknown Location',
                ];
            });

            return response()->json([
                'status' => 'success',
                'details' => $details
            ]);
        }

        return response()->json([
            'status' => 'no_attendance',
            'message' => 'No attendance recorded for this day.'
        ]);
    }

}