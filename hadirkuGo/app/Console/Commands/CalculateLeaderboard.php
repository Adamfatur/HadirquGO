<?php

// app/Console/Commands/CalculateLeaderboard.php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceLeaderboard;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CalculateLeaderboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:calculate-leaderboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and update the attendance leaderboard for all teams daily.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Ambil semua tim yang ada
        $teams = Team::all();
        Log::info('Starting leaderboard calculation...');

        foreach ($teams as $team) {
            Log::info('Processing team: ' . $team->team_unique_id);

            // Ambil data attendance untuk hari ini
            $date = Carbon::now()->format('Y-m-d');
            $attendances = Attendance::whereHas('user.teamsJoined', function ($query) use ($team) {
                $query->where('team_unique_id', $team->team_unique_id);
            })
                ->whereDate('checkin_time', $date)
                ->with('user')
                ->get();

            // Pastikan ada data kehadiran untuk tanggal tersebut
            if ($attendances->isEmpty()) {
                Log::info('No attendance data for team: ' . $team->team_unique_id . ' on ' . $date);
                continue; // Jika tidak ada data, lanjut ke tim berikutnya
            }

            Log::info('Found attendance data for ' . $attendances->count() . ' members on ' . $date);

            // Menghitung siapa yang paling pagi (Morning Person)
            $morningPerson = $attendances->sortBy(function ($attendance) {
                return $attendance->checkin_time;
            })->first();

            Log::info('Morning Person for team ' . $team->team_unique_id . ' is: ' . $morningPerson->user->name);

            // Menghitung siapa yang paling terlambat (Last Person) - hanya jika lebih dari 1 orang
            $lastPerson = null;
            if ($attendances->count() > 1) {
                $lastPerson = $attendances->sortByDesc(function ($attendance) {
                    return $attendance->checkin_time;
                })->first();
                Log::info('Last Person for team ' . $team->team_unique_id . ' is: ' . $lastPerson->user->name);
            } else {
                Log::info('No Last Person for team ' . $team->team_unique_id . ' as there is only one member.');
            }

            // Menghitung durasi kegiatan terlama (Longest Duration)
            $longestDurationAttendance = $attendances->sortByDesc(function ($attendance) {
                return $attendance->total_daily_duration; // Asumsi kolom ini ada
            })->first();

            Log::info('Longest Duration for team ' . $team->team_unique_id . ' is: ' . $longestDurationAttendance->user->name);

            // Data yang akan disimpan untuk Morning Person
            $morningPersonData = [
                'user_id' => $morningPerson->user_id,
                'team_id' => $team->id,
                'date' => $date,
                'morning_person' => true,  // Pastikan morning_person diset ke true
                'last_person' => false,    // Last Person tetap false
                'longest_duration' => $longestDurationAttendance->total_daily_duration ?? 0
            ];

            Log::info('Data to save for Morning Person: ' . json_encode($morningPersonData));

            // Simpan data Morning Person
            AttendanceLeaderboard::updateOrCreate(
                [
                    'user_id' => $morningPerson->user_id,
                    'team_id' => $team->id,
                    'date' => $date,
                ],
                $morningPersonData
            );
            Log::info('Saved Morning Person for team ' . $team->team_unique_id);

            // Simpan data Last Person jika ada
            if ($lastPerson) {
                $lastPersonData = [
                    'user_id' => $lastPerson->user_id,
                    'team_id' => $team->id,
                    'date' => $date,
                    'morning_person' => false,   // Morning Person tetap false
                    'last_person' => true,       // Set last_person ke true
                    'longest_duration' => $longestDurationAttendance->total_daily_duration ?? 0
                ];

                Log::info('Data to save for Last Person: ' . json_encode($lastPersonData));

                AttendanceLeaderboard::updateOrCreate(
                    [
                        'user_id' => $lastPerson->user_id,
                        'team_id' => $team->id,
                        'date' => $date,
                    ],
                    $lastPersonData
                );
                Log::info('Saved Last Person for team ' . $team->team_unique_id);
            } else {
                Log::info('No Last Person to save for team ' . $team->team_unique_id);
            }

            // Simpan data Longest Duration
            if ($longestDurationAttendance) {
                $longestDurationData = [
                    'user_id' => $longestDurationAttendance->user_id,
                    'team_id' => $team->id,
                    'date' => $date,
                    'last_person' => false,
                    'longest_duration' => $longestDurationAttendance->total_daily_duration
                ];

                Log::info('Data to save for Longest Duration: ' . json_encode($longestDurationData));

                AttendanceLeaderboard::updateOrCreate(
                    [
                        'user_id' => $longestDurationAttendance->user_id,
                        'team_id' => $team->id,
                        'date' => $date,
                    ],
                    $longestDurationData
                );
                Log::info('Saved Longest Duration for team ' . $team->team_unique_id);
            }
        }

        $this->info('Leaderboard calculation completed successfully!');
        Log::info('Leaderboard calculation completed.');
    }
}

