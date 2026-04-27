<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Helpers\RankHelper;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StudentEvaluationController extends Controller
{
    /**
     * Centralized configuration for all evaluation rules.
     */
    private array $config = [
        'standards' => [
            'checkin_time' => '08:30:00',
            'checkout_time' => '17:00:00',
            'work_minutes' => 480,
        ],
        'grading_thresholds' => [ // Rank Tiers
            'S' => 95, 'A' => 85, 'B' => 75, 'C' => 65, 'D' => 50, 'E' => 0,
        ],
        'initiative_points' => [ // Bonus Points
            'saturday' => 1,
            'sunday' => 2,
            'overtime_checkout' => 0.5,
        ],
        'ambition_weights' => [
            'extra_duration' => 0.5,
            'proactivity' => 0.5,
        ],
        'discipline_scores' => [
            'pioneer' => 100,
            'on_time' => 90,
            'present' => 75,
        ]
    ];

    /**
     * Evaluates a student's performance and displays the results.
     */
    public function evaluateByMemberId(Request $request, string $member_id): View
    {
        // --- STEP 1: DATA RETRIEVAL & PERIOD DETERMINATION ---
        $user = User::with(['pointSummary'])
            ->withCount(['challengesAsChallenger', 'testimonies'])
            ->where('member_id', $member_id)
            ->firstOrFail();

        $currentPeriod = $request->input('period', 'last_month');
        [$startDate, $endDate] = $this->determineDateRange($currentPeriod);

        $this->config['start_date'] = $startDate;
        $this->config['end_date'] = $endDate;

        $attendanceQuery = $user->attendances();
        $firstCheckinsQuery = Attendance::query();

        if ($startDate && $endDate) {
            $attendanceQuery->whereBetween('checkin_time', [$startDate->startOfDay(), $endDate->endOfDay()]);
            $firstCheckinsQuery->whereBetween('checkin_time', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $attendances = $attendanceQuery->orderBy('checkin_time', 'asc')->get();

        $firstCheckinsPerDay = $firstCheckinsQuery
            ->selectRaw('DATE(checkin_time) as attendance_date, MIN(checkin_time) as first_checkin')
            ->groupBy('attendance_date')
            ->pluck('first_checkin', 'attendance_date');

        // More efficient holiday fetching
        $publicHolidays = [];
        if ($startDate && $endDate) {
            if ($startDate->isSameMonth($endDate)) {
                $publicHolidays = $this->getPublicHolidays($startDate->year, $startDate->month);
            } else {
                $startYear = $startDate->year;
                $endYear = $endDate->year;
                foreach (range($startYear, $endYear) as $year) {
                    $publicHolidays = array_merge($publicHolidays, $this->getPublicHolidays($year));
                }
            }
        } else {
            $publicHolidays = $this->getPublicHolidays(now()->year);
        }
        $this->config['public_holidays'] = array_unique($publicHolidays);


        // --- STEP 2: CALCULATE ALL METRICS ---
        $monthlyStats = $this->calculateMonthlyStatistics($attendances);
        $initiative = $this->calculateInitiative($attendances);

        $evaluations = [
            'consistency' => $this->calculateConsistency($attendances),
            'discipline' => $this->calculateDiscipline($attendances, $firstCheckinsPerDay),
            'perseverance' => $this->calculatePerseverance($attendances),
            'ambition' => $this->calculateAmbition($user, $attendances),
            'initiative_and_commitment' => $initiative,
        ];

        // --- STEP 3: FINAL CALCULATION & GRADING ---
        $coreMetrics = collect($evaluations)->only(['consistency', 'discipline', 'perseverance', 'ambition']);
        $averageScore = $coreMetrics->avg('score_percentage');
        $totalPoints = $coreMetrics->sum('score_percentage') + $initiative['bonus_points'];

        $narrativeSummary = $this->generateNarrativeSummary($coreMetrics, $user, $monthlyStats, $initiative);

        // --- STEP 4: PREPARE COMPLETE DATA FOR THE VIEW ---
        $dailyLog = $attendances->map(function ($att) {
            return [
                'date' => $att->checkin_time->format('d M Y'),
                'day_name' => $att->checkin_time->format('l'),
                'checkin' => $att->checkin_time->format('H:i:s'),
                'checkout' => $att->checkout_time ? $att->checkout_time->format('H:i:s') : null,
                'is_workday' => $this->isWorkday($att->checkin_time),
            ];
        });

        $finalGrade = $this->getGradeFromScore($averageScore, true);
        
        // Generate local fallback summary based on score
        $localSummary = [
            'personality_summary' => "Based on the calculated metrics, {$user->name} has demonstrated a performance level of {$finalGrade['label']}.",
            'motivation_and_growth' => "To improve or maintain this score, focus on consistency in daily check-ins and maximizing active working hours.",
            'leadership_summary' => "{$user->name} achieved an average score of " . round($averageScore, 2) . "%. Continue monitoring their attendance trends to support their professional growth."
        ];

        // Add specific advice based on grade
        if (in_array($finalGrade['grade'], ['S', 'A'])) {
            $localSummary['personality_summary'] = "{$user->name} shows outstanding dedication and excellent time management. They are a highly reliable and proactive team member.";
            $localSummary['motivation_and_growth'] = "Maintain this fantastic momentum! Consider taking on leadership roles or mentoring peers to further elevate your impact.";
            $localSummary['leadership_summary'] = "An exemplary performance. {$user->name} is setting a high standard for the team with an average score of " . round($averageScore, 2) . "%.";
        } elseif (in_array($finalGrade['grade'], ['B', 'C'])) {
            $localSummary['personality_summary'] = "{$user->name} is a steady contributor with a solid foundation in discipline, though there are occasional fluctuations in consistency.";
            $localSummary['motivation_and_growth'] = "Focus on minimizing late check-ins or missed check-outs. Small daily improvements will quickly bump your score to the next tier.";
            $localSummary['leadership_summary'] = "A dependable performance. With a score of " . round($averageScore, 2) . "%, {$user->name} meets expectations but has clear room for optimization.";
        } else {
            $localSummary['personality_summary'] = "{$user->name}'s current metrics indicate significant challenges with attendance consistency and schedule adherence.";
            $localSummary['motivation_and_growth'] = "It's crucial to establish a stricter daily routine. Ensure you check in on time and complete your required hours to build a stronger performance record.";
            $localSummary['leadership_summary'] = "Attention required. A score of " . round($averageScore, 2) . "% suggests {$user->name} needs immediate support and clearer expectations regarding attendance policies.";
        }

        $isAiFailed = isset($narrativeSummary['error']);
        $finalNarrative = $isAiFailed ? $localSummary : $narrativeSummary;
        
        // Fetch User Rank from Database
        $userRankEntry = \App\Models\UserLeaderboard::where('user_id', $user->id)
            ->where('category', 'top_points')
            ->first();
            
        $userRank = $userRankEntry ? $userRankEntry->current_rank : '-';
        $userTitle = $userRankEntry ? $userRankEntry->title : null;
        $frameColor = $userRankEntry ? $userRankEntry->frame_color : '#3b82f6';

        // Fallback for users not yet in the leaderboard table
        if ($userRank === '-') {
            $totalPts = $user->pointSummary->total_points ?? 0;
            $userRank = \App\Models\UserPointSummary::where('total_points', '>', $totalPts)->count() + 1;
        }
        
        $totalUsers = \App\Models\UserPointSummary::count();
        $totalPointsExp = $user->pointSummary->total_points ?? 0;
        $levelNum = RankHelper::getLevelNumber($user);
        
        $levelModel = \App\Models\Level::where('minimum_points', '<=', $totalPointsExp)
            ->where('maximum_points', '>=', $totalPointsExp)
            ->first();
        $levelName = $levelModel ? $levelModel->name : 'Pioneer';

        $data = [
            'user_rank' => $userRank,
            'user_title' => $userTitle,
            'frame_color' => $frameColor,
            'total_users' => $totalUsers,
            'total_points_exp' => $totalPointsExp,
            'level_number' => $levelNum,
            'level_name' => $levelName,
            'user_info' => ['name' => $user->name, 'member_id' => $user->member_id, 'avatar' => $user->avatar, 'evaluation_period' => $this->formatPeriodLabel($startDate, $endDate)],
            'monthly_statistics' => $monthlyStats,
            'performance_evaluation' => $evaluations,
            'chart_data' => [
                'labels' => $coreMetrics->keys()->map(fn($key) => ucfirst(str_replace('_', ' ', $key))),
                'scores' => $coreMetrics->pluck('score_percentage')
            ],
            'final_summary' => [
                'total_points_earned' => round($totalPoints, 2),
                'final_average_score' => round($averageScore, 2),
                'final_grade' => $finalGrade,
                'narrative' => $finalNarrative,
                'ai_failed' => $isAiFailed,
                'ai_error_message' => $isAiFailed ? $narrativeSummary['error'] : null
            ],
            'daily_log' => $dailyLog,
        ];

        $isMultiMonth = $startDate && $endDate && !$startDate->isSameMonth($endDate);

        return view('lecturer.evaluation.evaluation', compact('data', 'currentPeriod', 'startDate', 'endDate', 'publicHolidays', 'isMultiMonth'));
    }

    // --- HELPER METHODS ---

    private function determineDateRange(string $period): array
    {
        switch ($period) {
            case 'this_month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'last_month':
                $date = now()->subMonth();
                return [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()];
            case '90_days':
                return [now()->subDays(90)->startOfDay(), now()->endOfDay()];
            case '6_months':
                return [now()->subMonths(6)->startOfDay(), now()->endOfDay()];
            case '1_year':
                return [now()->subYear()->startOfDay(), now()->endOfDay()];
            case 'all':
                return [null, null];
            default:
                // Custom range: "2025-01-01_to_2025-03-31"
                if (str_contains($period, '_to_')) {
                    [$start, $end] = explode('_to_', $period);
                    return [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()];
                }
                // Specific month: "2025-01"
                if (preg_match('/^\d{4}-\d{2}$/', $period)) {
                    $date = Carbon::createFromFormat('Y-m', $period);
                    return [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()];
                }
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
        }
    }

    private function formatPeriodLabel(?Carbon $startDate, ?Carbon $endDate): string
    {
        if ($startDate && $endDate) {
            if ($startDate->isSameMonth($endDate)) {
                return $startDate->format('F Y');
            }
            return "{$startDate->format('d M Y')} - {$endDate->format('d M Y')}";
        }
        return 'All Time';
    }

    private function getPublicHolidays(int $year, ?int $month = null): array
    {
        $queryParams = ['year' => $year];
        if ($month !== null) {
            $queryParams['month'] = $month;
        }

        try {
            $response = Http::get('https://libur.deno.dev/api', $queryParams);

            if ($response->successful() && !empty($response->json())) {
                return collect($response->json())->pluck('date')->toArray();
            }

            Log::warning('Failed to fetch public holidays from libur.deno.dev', [
                'params' => $queryParams,
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return [];

        } catch (\Exception $e) {
            Log::error('Exception when fetching public holidays from libur.deno.dev', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }


    private function isWorkday(Carbon $date): bool
    {
        return $date->isWeekday() && !in_array($date->toDateString(), $this->config['public_holidays']);
    }

    private function getGradeFromScore(float $score, bool $withLabel = false): string|array
    {
        $thresholds = $this->config['grading_thresholds'];
        $rank = match (true) {
            $score >= $thresholds['S'] => 'S',
            $score >= $thresholds['A'] => 'A',
            $score >= $thresholds['B'] => 'B',
            $score >= $thresholds['C'] => 'C',
            $score >= $thresholds['D'] => 'D',
            default => 'E',
        };
        $labels = ['S'=> 'Legendary', 'A' => 'Excellent', 'B' => 'Good', 'C' => 'Sufficient', 'D' => 'Needs Improvement', 'E' => 'Poor'];

        return $withLabel ? ['grade' => $rank, 'label' => $labels[$rank]] : $rank;
    }

    private function calculateMonthlyStatistics(Collection $attendances): array
    {
        if ($attendances->isEmpty()) {
            return array_fill_keys(['total_hadir_sebulan_hari', 'total_durasi_sebulan_menit', 'tidak_masuk_atau_lupa_checkout_hari', 'lupa_checkout_kali', 'durasi_terlama_menit', 'durasi_tercepat_menit', 'rata_rata_durasi_harian_menit', 'hadir_beruntun_terpanjang_hari'], 0);
        }

        $workDays = 0;
        if ($this->config['start_date']) {
            $calculationEndDate = $this->config['end_date']->isFuture() ? now() : $this->config['end_date'];
            $currentDay = $this->config['start_date']->copy();
            while ($currentDay->lte($calculationEndDate)) {
                if ($this->isWorkday($currentDay)) {
                    $workDays++;
                }
                $currentDay->addDay();
            }
        }

        $missedDays = $workDays > 0 ? ($workDays - $attendances->filter(fn($att) => $this->isWorkday($att->checkin_time))->count()) : 0;

        $maxStreak = 0;
        $currentStreak = 0;
        $previousDate = null;
        foreach ($attendances->map(fn($att) => $att->checkin_time->copy())->unique('toDateString')->sort() as $date) {
            if ($this->isWorkday($date)) {
                if ($previousDate && $previousDate->addDay()->isSameDay($date)) {
                    $currentStreak++;
                } else {
                    $currentStreak = 1;
                }
            }
            $maxStreak = max($maxStreak, $currentStreak);
            $previousDate = $date;
        }

        $completedSessions = $attendances->where('total_daily_duration', '>', 0);
        $forgotCheckoutCount = $attendances->count() - $completedSessions->count();

        return [
            'total_hadir_sebulan_hari' => $attendances->count(),
            'total_durasi_sebulan_menit' => $attendances->sum('total_daily_duration'),
            'tidak_masuk_atau_lupa_checkout_hari' => $missedDays,
            'lupa_checkout_kali' => $forgotCheckoutCount,
            'durasi_terlama_menit' => $completedSessions->max('total_daily_duration') ?? 0,
            'durasi_tercepat_menit' => $completedSessions->min('total_daily_duration') ?? 0,
            'rata_rata_durasi_harian_menit' => round($attendances->avg('total_daily_duration')),
            'hadir_beruntun_terpanjang_hari' => $maxStreak,
        ];
    }

    private function calculateConsistency(Collection $attendances): array
    {
        $workDays = 0;
        if ($this->config['start_date']) {
            $calculationEndDate = $this->config['end_date']->isFuture() ? now() : $this->config['end_date'];
            $currentDay = $this->config['start_date']->copy();
            while ($currentDay->lte($calculationEndDate)) {
                if ($this->isWorkday($currentDay)) {
                    $workDays++;
                }
                $currentDay->addDay();
            }
        }

        $daysAttendedOnWorkdays = $attendances->filter(fn($att) => $this->isWorkday($att->checkin_time))->count();
        $score = ($workDays > 0) ? ($daysAttendedOnWorkdays / $workDays) * 100 : 0;
        return ['score_percentage' => round($score, 2), 'grade' => $this->getGradeFromScore($score), 'days_attended_on_weekdays' => $daysAttendedOnWorkdays, 'total_workdays_in_period' => $workDays, 'description' => 'Measures attendance on scheduled workdays.'];
    }

    private function calculateDiscipline(Collection $attendances, Collection $firstCheckinsPerDay): array
    {
        if ($attendances->isEmpty()) return ['score_percentage' => 0, 'grade' => $this->getGradeFromScore(0), 'pioneer_days' => 0, 'on_time_days' => 0, 'present_days' => 0];

        $dailyScores = [];
        $pioneerCount = 0;
        $onTimeCount = 0;
        $presentCount = 0;
        $standardCheckinTime = $this->config['standards']['checkin_time'];
        $points = $this->config['discipline_scores'];

        foreach ($attendances as $attendance) {
            $checkinTime = $attendance->checkin_time;
            if (!$this->isWorkday($checkinTime)) continue;
            if (!$firstCheckinsPerDay->has($checkinTime->toDateString())) continue;

            $firstCheckinOfDay = Carbon::parse($firstCheckinsPerDay->get($checkinTime->toDateString()));

            if ($checkinTime->eq($firstCheckinOfDay)) {
                $dailyScores[] = $points['pioneer']; $pioneerCount++;
            } elseif ($checkinTime->format('H:i:s') <= $standardCheckinTime) {
                $dailyScores[] = $points['on_time']; $onTimeCount++;
            } else {
                $dailyScores[] = $points['present']; $presentCount++;
            }
        }

        $averageScore = count($dailyScores) > 0 ? collect($dailyScores)->avg() : 0;
        return [
            'score_percentage' => round($averageScore, 2),
            'grade' => $this->getGradeFromScore($averageScore),
            'pioneer_days' => $pioneerCount,
            'on_time_days' => $onTimeCount,
            'present_days' => $presentCount,
            'description' => "Pioneer: {$points['pioneer']}pts, On-Time: {$points['on_time']}pts, Present: {$points['present']}pts."
        ];
    }

    private function calculatePerseverance(Collection $attendances): array
    {
        if ($attendances->isEmpty()) return ['score_percentage' => 0, 'grade' => $this->getGradeFromScore(0), 'average_duration_minutes' => 0, 'standard_duration_minutes' => $this->config['standards']['work_minutes']];

        $avg = $attendances->avg('total_daily_duration');
        $score = ($this->config['standards']['work_minutes'] > 0) ? ($avg / $this->config['standards']['work_minutes']) * 100 : 0;
        return ['score_percentage' => round($score, 2), 'grade' => $this->getGradeFromScore($score), 'average_duration_minutes' => round($avg), 'standard_duration_minutes' => $this->config['standards']['work_minutes'], 'description' => 'Measures average duration vs. standard.'];
    }

    private function calculateAmbition(User $user, Collection $attendances): array
    {
        $extraDurationScore = 0;
        if ($attendances->isNotEmpty()) {
            $avgExtra = $attendances->sum(fn($att) => max(0, $att->total_daily_duration - $this->config['standards']['work_minutes'])) / $attendances->count();
            $extraDurationScore = min(100, ($avgExtra / 60) * 100);
        }
        $proactivityScore = min(100, ($user->challenges_as_challenger_count * 5) + (($user->pointSummary->total_points ?? 0) / 100));

        $score = ($extraDurationScore * $this->config['ambition_weights']['extra_duration']) + ($proactivityScore * $this->config['ambition_weights']['proactivity']);
        return ['score_percentage' => round($score), 'grade' => $this->getGradeFromScore($score), 'indicators' => ['exceeding_duration_score' => round($extraDurationScore), 'proactivity_score' => round($proactivityScore)], 'description' => 'A mix of extra work hours and proactive tasks.'];
    }

    private function calculateInitiative(Collection $attendances): array
    {
        $saturdays = $attendances->filter(fn($att) => $att->checkin_time->isSaturday())->count();
        $sundays = $attendances->filter(fn($att) => $att->checkin_time->isSunday())->count();

        $overtimeCheckoutCount = $attendances->filter(function ($att) {
            return !is_null($att->checkout_time) && $att->checkout_time->format('H:i:s') > $this->config['standards']['checkout_time'];
        })->count();

        $bonus = ($saturdays * $this->config['initiative_points']['saturday'])
            + ($sundays * $this->config['initiative_points']['sunday'])
            + ($overtimeCheckoutCount * $this->config['initiative_points']['overtime_checkout']);

        $score = min(100, $bonus * 10);
        return ['score_percentage' => $score, 'grade' => $this->getGradeFromScore($score), 'bonus_points' => $bonus, 'saturday_attendance_count' => $saturdays, 'sunday_attendance_count' => $sundays, 'overtime_checkout_count' => $overtimeCheckoutCount, 'description' => 'Bonus points for weekend and overtime work.'];
    }

    // --- GEMINI AI INTEGRATION (ENGLISH) ---

    private function generateNarrativeSummary(Collection $coreMetrics, User $user, array $monthlyStats, array $initiative): array
    {
        if ($coreMetrics->isEmpty() || $coreMetrics->every(fn($stat) => $stat['score_percentage'] == 0)) {
            return [
                'personality_summary' => "Insufficient data to create a performance profile for this period.",
                'motivation_and_growth' => "Increase attendance to begin performance evaluation and unlock your growth potential.",
                'leadership_summary' => "No attendance data has been recorded for analysis."
            ];
        }

        return $this->getGeminiEvaluation($coreMetrics, $user, $monthlyStats, $initiative);
    }

    private function getGeminiEvaluation(Collection $coreMetrics, User $user, array $monthlyStats, array $initiative): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            Log::error('GEMINI_API_KEY not set in .env file.');
            return ['error' => 'AI configuration not found.'];
        }

        $performanceData = [
            'student_name' => $user->name,
            'evaluation_period' => $this->formatPeriodLabel($this->config['start_date'], $this->config['end_date']),
            'core_metrics' => $coreMetrics->map(fn($metric, $key) => [
                'name' => ucfirst($key),
                'score' => $metric['score_percentage'],
                'grade' => $metric['grade']
            ])->values()->all(),
            'monthly_statistics' => $monthlyStats,
            'initiative_and_commitment' => $initiative,
        ];

        $prompt = "
    🎮 Welcome, Performance Analyst! You've just unlocked a new mission: decoding the hidden strengths and potential of a team member through their performance stats.

    📊 **Mission Briefing – Performance Data Incoming:**
    ```json
    " . json_encode($performanceData, JSON_PRETTY_PRINT) . "
    ```

    🧠 **Your Role:**
    You're not just any analyst—you’re also a master motivator! Your task is to read between the lines and craft a motivating profile that feels personal and inspiring.

    🎯 **Objective:**
    Reply in valid JSON format (no markdown wrappers like ```json or similar). Use clear, warm, and uplifting English. Your response must follow this exact structure:
    {
      \"personality_summary\": \"(string) Describe this player’s work personality based on their strongest and weakest stats. Make it feel like an RPG character profile—are they a 'calm strategist' or a 'tireless warrior'? Keep it positive and full of potential.\",
      \"motivation_and_growth\": \"(string) Give them a power-up! Provide actionable advice on how to level up their weakest skill using their strongest. Make it fun and encouraging, like giving them tips before a boss battle. Include 1–2 specific steps.\",
      \"leadership_summary\": \"(string) This is your report to the Guild Master (a.k.a. their team leader). Keep it professional but friendly. Highlight key strengths, growth edges, and overall potential for the quest ahead.\"
    }

    🛠️ Let’s make this feel like progress, not judgment. Ready? Analyze!
";

        try {
            // --- [MODIFIED] Using the model you explicitly requested ---
            $response = Http::withOptions(['timeout' => 30])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [

                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json',
                    ]
                ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed', ['response' => $response->body()]);
                return ['error' => 'Failed to contact the AI service. Please try again later.'];
            }

            $geminiText = $response->json('candidates.0.content.parts.0.text');
            if (empty($geminiText)) {
                Log::warning('Gemini response was empty.', ['response' => $response->body()]);
                return ['error' => 'The AI did not provide a valid response.'];
            }

            $parsedJson = json_decode($geminiText, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($parsedJson['personality_summary'])) {
                return [
                    'personality_summary'   => $parsedJson['personality_summary'] ?? 'Personality analysis unavailable.',
                    'motivation_and_growth' => $parsedJson['motivation_and_growth'] ?? 'Motivational advice unavailable.',
                    'leadership_summary'    => $parsedJson['leadership_summary'] ?? 'Leadership summary unavailable.',
                ];
            } else {
                Log::warning('Gemini response could not be parsed as JSON or was missing keys.', [
                    'response_text' => $geminiText,
                    'json_error' => json_last_error_msg()
                ]);
                return [
                    'personality_summary'   => "The AI responded, but in an unexpected format that could not be processed.",
                    'motivation_and_growth' => "This may be a temporary issue with the AI service. Please try again.",
                    'leadership_summary'    => "Raw response from AI: " . $geminiText,
                ];
            }

        } catch (\Exception $e) {
            Log::error('Exception during Gemini API call', ['message' => $e->getMessage()]);
            return ['error' => 'An internal error occurred while processing the request to the AI.'];
        }
    }
}