<?php

namespace App\Http\Controllers\PublicPages;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeamAttendanceRecapController extends Controller
{
    public function show(Request $request, $teamUniqueId)
    {
        Carbon::setLocale('id');

        $team = Team::where('team_unique_id', $teamUniqueId)->firstOrFail();
        $range = $request->get('range', 'today');

        $today = Carbon::today();
        if ($range === 'week') {
            $startDate = $today->copy()->startOfWeek(Carbon::MONDAY);
            $endDate = $startDate->copy()->addDays(5); // Monday - Saturday
        } else {
            $range = 'today';
            $startDate = $today->copy();
            $endDate = $today->copy();
        }

        $dates = [];
        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $dates[] = $cursor->copy();
            $cursor->addDay();
        }

        $leader = $team->leader_id ? User::find($team->leader_id) : null;
        $managers = $team->managers()->get();
        $members = $team->members()->get();

        $users = collect([$leader])
            ->merge($managers)
            ->merge($members)
            ->filter()
            ->unique('id')
            ->values();

        $userIds = $users->pluck('id');

        $attendances = Attendance::whereIn('user_id', $userIds)
            ->whereNotNull('checkin_time')
            ->whereDate('checkin_time', '>=', $startDate->toDateString())
            ->whereDate('checkin_time', '<=', $endDate->toDateString())
            ->orderBy('checkin_time', 'asc')
            ->get();

        $attendanceGroups = $attendances->groupBy(function ($attendance) {
            return $attendance->user_id . '|' . $attendance->checkin_time->toDateString();
        });

        $recordsByDate = [];
        $summaries = [];

        foreach ($dates as $date) {
            $dateKey = $date->toDateString();
            $rows = [];
            $presentCount = 0;
            $pendingCheckout = 0;

            foreach ($users as $user) {
                $group = $attendanceGroups->get($user->id . '|' . $dateKey, collect());
                $checkin = $group->min('checkin_time');
                $checkout = $group->pluck('checkout_time')->filter()->max();

                $status = 'Tidak hadir';
                $durationMinutes = null;

                if ($checkin) {
                    $presentCount++;
                    if ($checkout) {
                        $status = 'Lengkap';
                        $durationMinutes = $checkin->diffInMinutes($checkout);
                    } else {
                        $status = 'Belum checkout';
                        $pendingCheckout++;
                    }
                }

                $rows[] = [
                    'user' => $user,
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'duration_minutes' => $durationMinutes,
                    'status' => $status,
                    'tags' => [],
                ];
            }

            // Sort rows: Present (Earliest to Latest) -> Absent
            usort($rows, function ($a, $b) {
                $aTime = $a['checkin'] ? $a['checkin']->timestamp : PHP_INT_MAX;
                $bTime = $b['checkin'] ? $b['checkin']->timestamp : PHP_INT_MAX;

                if ($aTime === $bTime) {
                    return strcasecmp($a['user']->name, $b['user']->name);
                }
                return $aTime <=> $bTime;
            });

            // Tag Morning Person and Late Person
            $presentIndices = [];
            foreach ($rows as $index => $row) {
                if ($row['checkin']) {
                    $presentIndices[] = $index;
                }
            }

            if (count($presentIndices) > 1) {
                $firstIndex = $presentIndices[0];
                $lastIndex = end($presentIndices);

                $rows[$firstIndex]['tags'][] = 'morning_person';
                $rows[$lastIndex]['tags'][] = 'late_person';
            }

            $recordsByDate[$dateKey] = $rows;
            $summaries[$dateKey] = [
                'present' => $presentCount,
                'total' => $users->count(),
                'pending_checkout' => $pendingCheckout,
            ];
        }

        return view('public.team-attendance-recap', [
            'team' => $team,
            'range' => $range,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dates' => $dates,
            'recordsByDate' => $recordsByDate,
            'summaries' => $summaries,
        ]);
    }
}
