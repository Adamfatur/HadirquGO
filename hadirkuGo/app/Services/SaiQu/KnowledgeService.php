<?php

namespace App\Services\SaiQu;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Team;
use App\Models\UserPointSummary;
use App\Models\UserLeaderboard;
use App\Models\LocationLeaderboard;
use App\Models\TeamLeaderboard;
use App\Models\Level;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Product;
use App\Models\Quiz;
use App\Models\SuperQuiz;
use App\Models\Challenge;
use App\Models\DailyCheckin;
use App\Models\WeeklyRanking;
use App\Models\Reward;
use App\Models\UserReward;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KnowledgeService
{
    /**
     * Cache TTL from config (default 1 hour).
     */
    protected static function ttl(): int
    {
        return (int) config('saiqu.cache_ttl', 3600);
    }

    /**
     * Short cache for user-specific data (5 minutes).
     */
    protected static function userTtl(): int
    {
        return 300;
    }

    /**
     * Build relevant context based on the user's query.
     * Uses aggressive caching and smart topic matching.
     */
    public static function getRelevantData(string $query, ?User $user = null): string
    {
        $lower = mb_strtolower(trim($query));
        $context = [];

        // Always include system info (heavily cached)
        $context[] = self::getSystemInfo();

        // Always include user context if available (short cache)
        if ($user) {
            $context[] = self::getUserContext($user);
        }

        // Smart topic matching — multiple topics can match
        $topicMap = [
            'attendance'   => ['absen', 'hadir', 'checkin', 'checkout', 'check-in', 'check-out', 'jam', 'waktu', 'hari ini', 'kemarin', 'masuk', 'keluar', 'presensi', 'datang', 'pulang'],
            'points'       => ['poin', 'point', 'skor', 'score', 'jarak', 'banding', 'selisih', 'tesla'],
            'level'        => ['level', 'tingkat', 'exp', 'naik level', 'level up'],
            'leaderboard'  => ['rank', 'ranking', 'peringkat', 'leaderboard', 'top', 'juara', 'tertinggi', 'terbanyak', 'nomor 1', 'no 1', 'siapa', 'papan peringkat', 'posisi'],
            'team'         => ['tim', 'team', 'anggota', 'member', 'kelompok', 'gabung', 'pimpin', 'leader'],
            'achievement'  => ['achievement', 'pencapaian', 'badge', 'medali', 'lencana', 'unlock'],
            'quiz'         => ['quiz', 'kuis', 'soal', 'ujian', 'superquiz', 'super quiz', 'jawab'],
            'reward'       => ['reward', 'hadiah', 'redeem', 'tukar', 'produk', 'product', 'spin', 'gacha'],
            'challenge'    => ['challenge', 'tantangan', 'lawan', 'duel'],
            'checkin'      => ['daily', 'checkin harian', 'streak', 'berturut', 'konsisten'],
            'statistics'   => ['statistik', 'laporan', 'total', 'jumlah', 'berapa banyak', 'rekap', 'summary', 'ringkasan'],
            'feature'      => ['fitur', 'feature', 'cara', 'bagaimana', 'fungsi', 'menu', 'bisa apa', 'tutorial', 'panduan', 'help', 'bantuan'],
            'profile'      => ['profil', 'profile', 'nama', 'email', 'foto', 'avatar', 'biodata', 'ganti nama'],
            'location'     => ['lokasi', 'location', 'tempat', 'dimana', 'gedung', 'kampus'],
        ];

        $matchedTopics = [];
        foreach ($topicMap as $topic => $keywords) {
            if (self::match($lower, $keywords)) {
                $matchedTopics[] = $topic;
            }
        }

        // If no specific topic matched, provide general overview
        if (empty($matchedTopics)) {
            $matchedTopics = ['feature', 'statistics'];
        }

        // Build context for each matched topic
        foreach ($matchedTopics as $topic) {
            $ctx = match ($topic) {
                'attendance'  => self::getAttendanceContext($user, $lower),
                'points'      => self::getPointsContext($user, $lower),
                'level'       => self::getLevelContext($user),
                'leaderboard' => self::getLeaderboardContext($user, $lower),
                'team'        => self::getTeamContext($user),
                'achievement' => self::getAchievementContext($user),
                'quiz'        => self::getQuizContext($user),
                'reward'      => self::getRewardContext($user),
                'challenge'   => self::getChallengeContext($user),
                'checkin'     => self::getDailyCheckinContext($user),
                'statistics'  => self::getStatisticsContext(),
                'feature'     => self::getFeatureGuide(),
                'profile'     => self::getProfileContext($user),
                'location'    => self::getLocationContext(),
                default       => '',
            };
            if ($ctx) $context[] = $ctx;
        }

        return implode("\n\n", array_filter($context));
    }

    /**
     * Generate personalized suggested questions for a user.
     * Cached per user for 10 minutes.
     */
    public static function getSuggestedQuestions(?User $user): array
    {
        if (!$user) {
            return self::getDefaultSuggestions();
        }

        $cacheKey = "saiqu:suggestions:{$user->id}";
        return Cache::remember($cacheKey, 600, function () use ($user) {
            $suggestions = [];

            // Always include basic personal questions
            $suggestions[] = ['icon' => '💰', 'text' => 'Berapa poin saya sekarang?'];
            $suggestions[] = ['icon' => '⭐', 'text' => 'Apa level saya?'];

            // Check if user has active attendance
            $hasActive = Cache::remember("saiqu:active_att:{$user->id}", 120, function () use ($user) {
                return Attendance::where('user_id', $user->id)->where('is_active', true)->exists();
            });

            if ($hasActive) {
                $suggestions[] = ['icon' => '⏱️', 'text' => 'Sudah berapa lama saya absen hari ini?'];
            } else {
                $suggestions[] = ['icon' => '📋', 'text' => 'Apakah saya sudah absen hari ini?'];
            }

            // Check ranking position
            $myRank = Cache::remember("saiqu:rank:{$user->id}", self::userTtl(), function () use ($user) {
                return UserLeaderboard::where('category', 'top_points')
                    ->where('user_id', $user->id)
                    ->first();
            });

            if ($myRank && $myRank->current_rank <= 50) {
                $suggestions[] = ['icon' => '🏆', 'text' => 'Siapa yang di atas dan di bawah saya di leaderboard?'];
            } else {
                $suggestions[] = ['icon' => '📊', 'text' => 'Berapa ranking saya saat ini?'];
            }

            // Check teams
            $teamCount = Cache::remember("saiqu:teams:{$user->id}", self::userTtl(), function () use ($user) {
                return $user->teamsLed()->count() + $user->teamsJoined()->count();
            });

            if ($teamCount > 0) {
                $suggestions[] = ['icon' => '👥', 'text' => 'Info tim saya'];
            }

            // Check daily checkin streak
            $suggestions[] = ['icon' => '🔥', 'text' => 'Berapa streak checkin harian saya?'];

            // Check achievements
            $achievementCount = Cache::remember("saiqu:ach_count:{$user->id}", self::userTtl(), function () use ($user) {
                return UserAchievement::where('user_id', $user->id)->count();
            });

            if ($achievementCount > 0) {
                $suggestions[] = ['icon' => '🏅', 'text' => 'Pencapaian apa saja yang sudah saya raih?'];
            } else {
                $suggestions[] = ['icon' => '🏅', 'text' => 'Achievement apa saja yang tersedia?'];
            }

            // General useful questions
            $suggestions[] = ['icon' => '🔍', 'text' => 'Fitur apa saja yang ada di HadirkuGO?'];
            $suggestions[] = ['icon' => '🎯', 'text' => 'Siapa top 5 poin tertinggi?'];

            // Return max 6 suggestions, shuffled for variety but keep first 3 personal
            $personal = array_slice($suggestions, 0, 4);
            $general = array_slice($suggestions, 4);
            shuffle($general);
            $result = array_merge($personal, array_slice($general, 0, 2));

            return array_slice($result, 0, 6);
        });
    }

    protected static function getDefaultSuggestions(): array
    {
        return [
            ['icon' => '💰', 'text' => 'Berapa poin saya?'],
            ['icon' => '⭐', 'text' => 'Apa level saya?'],
            ['icon' => '📋', 'text' => 'Info absensi saya'],
            ['icon' => '🔍', 'text' => 'Fitur apa saja yang ada?'],
            ['icon' => '📊', 'text' => 'Siapa top 5 poin tertinggi?'],
            ['icon' => '🏅', 'text' => 'Achievement apa saja yang tersedia?'],
        ];
    }

    // =========================================================================
    // HELPER
    // =========================================================================

    protected static function match(string $query, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (mb_strpos($query, $kw) !== false) return true;
        }
        return false;
    }

    // =========================================================================
    // CONTEXT BUILDERS
    // =========================================================================

    protected static function getSystemInfo(): string
    {
        return Cache::remember('saiqu:system_info', self::ttl() * 24, function () {
            return "SYSTEM: HadirkuGO v2.0 — platform kehadiran digital berbasis QR Code. "
                 . "Fitur: Absensi QR, Tim, Poin Tesla, Level, Leaderboard (6 kategori, update per jam), "
                 . "Achievement, Quiz, Super Quiz, Reward (spin/gacha), Challenge, Daily Checkin, "
                 . "Feedback, SaiQu AI, Multi-bahasa (ID/EN), Journey Publik.";
        });
    }

    protected static function getUserContext(?User $user): string
    {
        if (!$user) return '';

        $cacheKey = "saiqu:user_ctx:{$user->id}";
        return Cache::remember($cacheKey, self::userTtl(), function () use ($user) {
            $summary = $user->pointSummary;
            $level = $user->userLevel?->level;
            $roles = $user->roles->pluck('name')->implode(', ');
            $achievementCount = UserAchievement::where('user_id', $user->id)->count();

            $rank = UserLeaderboard::where('category', 'top_points')
                ->where('user_id', $user->id)
                ->first();

            $rankStr = $rank ? "#{$rank->current_rank}" : 'Belum masuk leaderboard';
            $titleStr = $rank && $rank->title ? ", Gelar={$rank->title}" : '';
            $frameStr = $rank && $rank->frame_color ? ", Frame={$rank->frame_color}" : '';

            // Daily checkin streak
            $streak = DailyCheckin::where('user_id', $user->id)
                ->orderBy('checkin_date', 'desc')
                ->first();
            $streakStr = $streak ? ", Streak={$streak->current_streak} hari" : '';

            return "USER: Nama={$user->display_name}, Role={$roles}, "
                 . "Total Poin=" . ($summary->total_points ?? 0) . ", "
                 . "Current Poin=" . ($summary->current_points ?? 0) . ", "
                 . "Level=" . ($level->name ?? 'Belum ada') . ", "
                 . "Ranking={$rankStr}{$titleStr}{$frameStr}, "
                 . "Achievements={$achievementCount}"
                 . $streakStr . ".";
        });
    }

    protected static function getAttendanceContext(?User $user, string $query = ''): string
    {
        $lines = ["ATTENDANCE DATA:"];

        if ($user) {
            $cacheKey = "saiqu:att_ctx:{$user->id}:" . Carbon::today()->format('Ymd');
            $userAttData = Cache::remember($cacheKey, 120, function () use ($user) {
                $today = Carbon::today();
                $data = [];

                // Today's attendance
                $todayAtts = Attendance::where('user_id', $user->id)
                    ->whereDate('checkin_time', $today)
                    ->orderBy('checkin_time', 'asc')
                    ->with('attendanceLocation')
                    ->limit(10)
                    ->get();

                $data['today'] = $todayAtts->map(function ($att) {
                    return [
                        'location' => $att->attendanceLocation->name ?? 'Unknown',
                        'checkin' => $att->checkin_time ? $att->checkin_time->format('H:i') : '-',
                        'checkout' => $att->checkout_time ? $att->checkout_time->format('H:i') : 'Belum checkout',
                        'duration' => $att->total_daily_duration ? round($att->total_daily_duration / 60, 1) : null,
                        'is_active' => (bool) $att->is_active,
                    ];
                })->toArray();

                // Active attendance
                $active = Attendance::where('user_id', $user->id)->where('is_active', true)
                    ->with('attendanceLocation')->first();
                if ($active) {
                    $data['active'] = [
                        'location' => $active->attendanceLocation->name ?? 'Unknown',
                        'since' => $active->checkin_time ? $active->checkin_time->format('H:i') : '-',
                        'elapsed_min' => $active->checkin_time ? $active->checkin_time->diffInMinutes(now()) : 0,
                    ];
                }

                // Monthly stats
                $data['this_month'] = Attendance::where('user_id', $user->id)
                    ->whereMonth('checkin_time', now()->month)
                    ->whereYear('checkin_time', now()->year)
                    ->count();

                $data['total'] = Attendance::where('user_id', $user->id)->count();

                // Average duration this month
                $avgDuration = Attendance::where('user_id', $user->id)
                    ->whereMonth('checkin_time', now()->month)
                    ->whereYear('checkin_time', now()->year)
                    ->whereNotNull('checkout_time')
                    ->avg('total_daily_duration');
                $data['avg_duration_min'] = $avgDuration ? round($avgDuration / 60, 1) : null;

                // Missed checkouts this month
                $data['missed_checkouts'] = Attendance::where('user_id', $user->id)
                    ->whereMonth('checkin_time', now()->month)
                    ->whereYear('checkin_time', now()->year)
                    ->whereNull('checkout_time')
                    ->where('is_active', false)
                    ->count();

                return $data;
            });

            // Format today's attendance
            if (!empty($userAttData['today'])) {
                $lines[] = "Absensi hari ini (" . Carbon::today()->format('d M Y') . "):";
                foreach ($userAttData['today'] as $att) {
                    $durStr = $att['duration'] ? "{$att['duration']} jam" : '-';
                    $lines[] = "- Check-in: {$att['checkin']}, Check-out: {$att['checkout']}, Lokasi: {$att['location']}, Durasi: {$durStr}";
                }
            } else {
                $lines[] = "- Kamu belum absen hari ini.";
            }

            // Active attendance
            if (isset($userAttData['active'])) {
                $a = $userAttData['active'];
                $lines[] = "- SEDANG AKTIF: Check-in sejak {$a['since']} di {$a['location']} ({$a['elapsed_min']} menit berlalu)";
            }

            // Yesterday (if asked)
            if (self::match($query, ['kemarin', 'yesterday'])) {
                $yesterday = Carbon::yesterday();
                $yesterdayKey = "saiqu:att_yesterday:{$user->id}:" . $yesterday->format('Ymd');
                $yesterdayAtts = Cache::remember($yesterdayKey, self::userTtl(), function () use ($user, $yesterday) {
                    return Attendance::where('user_id', $user->id)
                        ->whereDate('checkin_time', $yesterday)
                        ->orderBy('checkin_time', 'asc')
                        ->with('attendanceLocation')
                        ->limit(5)
                        ->get()
                        ->map(fn ($att) => [
                            'location' => $att->attendanceLocation->name ?? 'Unknown',
                            'checkin' => $att->checkin_time ? $att->checkin_time->format('H:i') : '-',
                            'checkout' => $att->checkout_time ? $att->checkout_time->format('H:i') : '-',
                        ])->toArray();
                });

                if (!empty($yesterdayAtts)) {
                    $lines[] = "Absensi kemarin ({$yesterday->format('d M Y')}):";
                    foreach ($yesterdayAtts as $att) {
                        $lines[] = "- Check-in: {$att['checkin']}, Check-out: {$att['checkout']}, Lokasi: {$att['location']}";
                    }
                }
            }

            // Summary stats
            $lines[] = "- Total absensi kamu: {$userAttData['total']}, Bulan ini: {$userAttData['this_month']}";
            if ($userAttData['avg_duration_min']) {
                $lines[] = "- Rata-rata durasi bulan ini: {$userAttData['avg_duration_min']} jam";
            }
            if ($userAttData['missed_checkouts'] > 0) {
                $lines[] = "- Lupa checkout bulan ini: {$userAttData['missed_checkouts']} kali";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getPointsContext(?User $user, string $query = ''): string
    {
        if (!$user) return '';
        $summary = $user->pointSummary;
        if (!$summary) return "POINTS: Belum ada data poin.";

        $cacheKey = "saiqu:points_ctx:{$user->id}";
        return Cache::remember($cacheKey, self::userTtl(), function () use ($user, $summary, $query) {
            $lines = ["POINTS DATA:"];
            $lines[] = "- Poin kamu: Total={$summary->total_points}, Current={$summary->current_points}";

            // Points needed for next level
            $currentLevel = $user->userLevel?->level;
            if ($currentLevel && $currentLevel->maximum_points) {
                $needed = $currentLevel->maximum_points - $summary->total_points;
                if ($needed > 0) {
                    $lines[] = "- Poin untuk naik level: {$needed} poin lagi";
                }
            }

            // Top 10 for comparison
            $top10 = Cache::remember('saiqu:top10_points', 300, function () {
                return UserPointSummary::with('user')
                    ->orderByDesc('total_points')
                    ->limit(10)
                    ->get()
                    ->map(fn ($ups) => [
                        'name' => $ups->user->display_name ?? 'Unknown',
                        'points' => $ups->total_points,
                    ])->toArray();
            });

            if (self::match($query, ['jarak', 'banding', 'selisih', 'dengan', 'sama', 'vs', 'top', 'tertinggi'])) {
                $lines[] = "Top 10 poin tertinggi:";
                foreach ($top10 as $i => $entry) {
                    $diff = $summary->total_points - $entry['points'];
                    $diffStr = $diff >= 0 ? "+{$diff}" : "{$diff}";
                    $lines[] = "- #" . ($i + 1) . " {$entry['name']}: {$entry['points']} poin (selisih: {$diffStr})";
                }
            }

            // Comparison rival
            if ($user->comparison_user_id) {
                $rival = Cache::remember("saiqu:rival:{$user->comparison_user_id}", self::userTtl(), function () use ($user) {
                    $r = User::with('pointSummary')->find($user->comparison_user_id);
                    if (!$r) return null;
                    return [
                        'name' => $r->display_name,
                        'points' => $r->pointSummary->total_points ?? 0,
                    ];
                });
                if ($rival) {
                    $diff = $summary->total_points - $rival['points'];
                    $diffStr = $diff >= 0 ? "kamu unggul +{$diff}" : "kamu tertinggal " . abs($diff);
                    $lines[] = "- Rival kamu: {$rival['name']} ({$rival['points']} poin) — {$diffStr}";
                }
            }

            return implode("\n", $lines);
        });
    }

    protected static function getLevelContext(?User $user): string
    {
        $levels = Cache::remember('saiqu:levels', self::ttl(), function () {
            return Level::orderBy('minimum_points')->get(['name', 'minimum_points', 'maximum_points'])
                ->map(fn ($lv) => "{$lv->name}: {$lv->minimum_points}-{$lv->maximum_points} poin")
                ->toArray();
        });

        $lines = ["LEVEL SYSTEM:"];
        foreach ($levels as $lv) {
            $lines[] = "- {$lv}";
        }

        if ($user && $user->userLevel) {
            $currentLevel = $user->userLevel->level;
            $lines[] = "Level kamu: {$currentLevel->name}";
            $summary = $user->pointSummary;
            if ($summary && $currentLevel->maximum_points) {
                $progress = min(100, round(($summary->total_points / $currentLevel->maximum_points) * 100, 1));
                $needed = max(0, $currentLevel->maximum_points - $summary->total_points);
                $lines[] = "Progress: {$progress}% ({$needed} poin lagi untuk naik level)";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getLeaderboardContext(?User $user, string $query = ''): string
    {
        $lines = ["LEADERBOARD DATA:"];

        // Top 10 by points (cached)
        $topPoints = Cache::remember('saiqu:lb_top10', 300, function () {
            return UserLeaderboard::with('user')
                ->where('category', 'top_points')
                ->orderBy('current_rank', 'asc')
                ->limit(10)
                ->get()
                ->map(fn ($e) => [
                    'rank' => $e->current_rank,
                    'name' => $e->user->display_name ?? 'Unknown',
                    'score' => $e->score,
                    'title' => $e->title,
                    'prev_rank' => $e->previous_rank,
                ])->toArray();
        });

        if (!empty($topPoints)) {
            $lines[] = "Top 10 Poin Tertinggi:";
            foreach ($topPoints as $entry) {
                $title = $entry['title'] ? " ({$entry['title']})" : '';
                $change = '';
                if ($entry['prev_rank'] && $entry['prev_rank'] != $entry['rank']) {
                    $diff = $entry['prev_rank'] - $entry['rank'];
                    $change = $diff > 0 ? " ↑{$diff}" : " ↓" . abs($diff);
                }
                $lines[] = "- #{$entry['rank']} {$entry['name']}: {$entry['score']} poin{$title}{$change}";
            }
        }

        // If user asks about specific person
        $nameSearch = self::extractPersonName($query);
        if ($nameSearch) {
            $cacheKey = "saiqu:search:" . md5($nameSearch);
            $foundData = Cache::remember($cacheKey, self::userTtl(), function () use ($nameSearch) {
                return User::where('name', 'like', "%{$nameSearch}%")
                    ->limit(3)
                    ->get()
                    ->map(function ($found) {
                        $summary = $found->pointSummary;
                        $rank = UserLeaderboard::where('category', 'top_points')
                            ->where('user_id', $found->id)->first();
                        $level = $found->userLevel?->level;
                        return [
                            'name' => $found->display_name,
                            'rank' => $rank ? "#{$rank->current_rank}" : 'Tidak di leaderboard',
                            'points' => $summary ? $summary->total_points : 0,
                            'level' => $level ? $level->name : 'Unknown',
                            'title' => $rank && $rank->title ? $rank->title : null,
                        ];
                    })->toArray();
            });

            foreach ($foundData as $f) {
                $titleStr = $f['title'] ? " ({$f['title']})" : '';
                $lines[] = "INFO USER '{$f['name']}': Ranking={$f['rank']}, Poin={$f['points']}, Level={$f['level']}{$titleStr}";
            }
        }

        // User's own rank with neighbors
        if ($user) {
            $myRankData = Cache::remember("saiqu:my_rank:{$user->id}", self::userTtl(), function () use ($user) {
                $myRank = UserLeaderboard::where('category', 'top_points')
                    ->where('user_id', $user->id)->first();

                if ($myRank) {
                    $data = [
                        'rank' => $myRank->current_rank,
                        'score' => $myRank->score,
                        'title' => $myRank->title,
                        'above' => null,
                        'below' => null,
                    ];

                    // User above
                    if ($myRank->current_rank > 1) {
                        $above = UserLeaderboard::with('user')
                            ->where('category', 'top_points')
                            ->where('current_rank', $myRank->current_rank - 1)
                            ->first();
                        if ($above) {
                            $data['above'] = [
                                'name' => $above->user->display_name ?? 'Unknown',
                                'score' => $above->score,
                                'gap' => $above->score - $myRank->score,
                            ];
                        }
                    }

                    // User below
                    $below = UserLeaderboard::with('user')
                        ->where('category', 'top_points')
                        ->where('current_rank', $myRank->current_rank + 1)
                        ->first();
                    if ($below) {
                        $data['below'] = [
                            'name' => $below->user->display_name ?? 'Unknown',
                            'score' => $below->score,
                            'gap' => $myRank->score - $below->score,
                        ];
                    }

                    return $data;
                }

                // Not in leaderboard — estimate
                $myPts = $user->pointSummary->total_points ?? 0;
                $approxRank = UserPointSummary::where('total_points', '>', $myPts)->count() + 1;
                return ['rank' => $approxRank, 'score' => $myPts, 'title' => null, 'above' => null, 'below' => null, 'estimated' => true];
            });

            $estLabel = isset($myRankData['estimated']) ? ' (estimasi)' : '';
            $lines[] = "Ranking kamu{$estLabel}: #{$myRankData['rank']} (skor: {$myRankData['score']})";
            if ($myRankData['title']) {
                $lines[] = "Gelar kamu: {$myRankData['title']}";
            }
            if ($myRankData['above']) {
                $lines[] = "Di atas kamu: {$myRankData['above']['name']} (skor: {$myRankData['above']['score']}, selisih: {$myRankData['above']['gap']})";
            }
            if ($myRankData['below']) {
                $lines[] = "Di bawah kamu: {$myRankData['below']['name']} (skor: {$myRankData['below']['score']}, selisih: {$myRankData['below']['gap']})";
            }
        }

        return implode("\n", $lines);
    }

    protected static function extractPersonName(string $query): ?string
    {
        $cleaned = preg_replace('/\b(siapa|berapa|apa|dimana|kapan|bagaimana|prof|professor|pak|bu|ibu|bapak|si|nya|itu|yang|di|ke|dari|dengan|untuk|dan|atau|ini|aku|saya|kamu|dia|mereka)\b/i', '', $query);
        $cleaned = preg_replace('/\b(peringkat|ranking|rank|point|poin|level|top|nomor|no|mau|tau|tahu|info|informasi|tentang|berapa|score|skor)\b/i', '', $cleaned);
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));

        if (mb_strlen($cleaned) >= 2 && !preg_match('/^\d+$/', $cleaned)) {
            return $cleaned;
        }
        return null;
    }

    protected static function getTeamContext(?User $user): string
    {
        $lines = ["TEAM DATA:"];

        $totalTeams = Cache::remember('saiqu:total_teams', self::ttl(), function () {
            return Team::count();
        });
        $lines[] = "Total tim di sistem: {$totalTeams}";

        if ($user) {
            $teamData = Cache::remember("saiqu:user_teams:{$user->id}", self::userTtl(), function () use ($user) {
                $teamsLed = $user->teamsLed()->withCount('members')->get();
                $teamsJoined = $user->teamsJoined()->withCount('members')->get();
                return [
                    'led' => $teamsLed->map(fn ($t) => ['name' => $t->name, 'members' => $t->members_count])->toArray(),
                    'joined' => $teamsJoined->map(fn ($t) => ['name' => $t->name, 'members' => $t->members_count])->toArray(),
                ];
            });

            if (!empty($teamData['led'])) {
                $lines[] = "Tim yang kamu pimpin:";
                foreach ($teamData['led'] as $t) {
                    $lines[] = "- {$t['name']} ({$t['members']} anggota) — kamu sebagai Leader";
                }
            }
            if (!empty($teamData['joined'])) {
                $lines[] = "Tim yang kamu ikuti:";
                foreach ($teamData['joined'] as $t) {
                    $lines[] = "- {$t['name']} ({$t['members']} anggota)";
                }
            }
            if (empty($teamData['led']) && empty($teamData['joined'])) {
                $lines[] = "- Kamu belum bergabung tim.";
            }
        }

        // Top teams from leaderboard
        $topTeams = Cache::remember('saiqu:top_teams', 600, function () {
            return TeamLeaderboard::with('team')
                ->orderBy('current_rank', 'asc')
                ->limit(5)
                ->get()
                ->map(fn ($tl) => "#{$tl->current_rank} {$tl->team->name}: {$tl->score} poin")
                ->toArray();
        });

        if (!empty($topTeams)) {
            $lines[] = "Top 5 Tim:";
            foreach ($topTeams as $t) {
                $lines[] = "- {$t}";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getAchievementContext(?User $user): string
    {
        $allAchievements = Cache::remember('saiqu:achievements', self::ttl(), function () {
            return Achievement::select('id', 'name', 'description')->get()
                ->map(fn ($a) => ['id' => $a->id, 'name' => $a->name, 'desc' => $a->description])
                ->toArray();
        });

        $lines = ["ACHIEVEMENTS:"];
        $lines[] = "Total achievement tersedia: " . count($allAchievements);
        foreach ($allAchievements as $a) {
            $lines[] = "- {$a['name']}: {$a['desc']}";
        }

        if ($user) {
            $userAch = Cache::remember("saiqu:user_ach:{$user->id}", self::userTtl(), function () use ($user) {
                return UserAchievement::where('user_id', $user->id)
                    ->with('achievement')
                    ->get()
                    ->map(fn ($ua) => $ua->achievement->name ?? 'Unknown')
                    ->toArray();
            });

            if (!empty($userAch)) {
                $lines[] = "Achievement kamu (" . count($userAch) . "): " . implode(', ', $userAch);
            } else {
                $lines[] = "Kamu belum punya achievement.";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getQuizContext(?User $user): string
    {
        $quizData = Cache::remember('saiqu:quiz_info', self::ttl(), function () {
            return [
                'quiz_count' => Quiz::count(),
                'superquiz_count' => SuperQuiz::count(),
            ];
        });

        $lines = ["QUIZ:"];
        $lines[] = "- Quiz tersedia: {$quizData['quiz_count']}";
        $lines[] = "- Super Quiz tersedia: {$quizData['superquiz_count']}";

        if ($user) {
            $userQuiz = Cache::remember("saiqu:user_quiz:{$user->id}", self::userTtl(), function () use ($user) {
                $quizAttempts = DB::table('quiz_attempts')->where('user_id', $user->id)->count();
                $superQuizAttempts = DB::table('super_quiz_attempts')->where('user_id', $user->id)->count();
                return ['quiz' => $quizAttempts, 'superquiz' => $superQuizAttempts];
            });
            $lines[] = "- Quiz yang sudah kamu coba: {$userQuiz['quiz']}";
            $lines[] = "- Super Quiz yang sudah kamu coba: {$userQuiz['superquiz']}";
        }

        return implode("\n", $lines);
    }

    protected static function getRewardContext(?User $user): string
    {
        $products = Cache::remember('saiqu:rewards', self::ttl(), function () {
            return Product::select('name', 'description', 'stock_quantity')
                ->where('stock_quantity', '>', 0)
                ->limit(10)
                ->get()
                ->map(fn ($p) => "{$p->name}: {$p->description} (stok: {$p->stock_quantity})")
                ->toArray();
        });

        $lines = ["REWARDS:"];
        if (empty($products)) {
            $lines[] = "Belum ada produk tersedia untuk ditukar.";
        } else {
            foreach ($products as $p) {
                $lines[] = "- {$p}";
            }
        }

        if ($user) {
            $userRewards = Cache::remember("saiqu:user_rewards:{$user->id}", self::userTtl(), function () use ($user) {
                return UserReward::where('user_id', $user->id)
                    ->with('reward')
                    ->get()
                    ->map(fn ($ur) => $ur->reward->name ?? 'Unknown')
                    ->toArray();
            });
            if (!empty($userRewards)) {
                $lines[] = "Hadiah yang kamu dapat: " . implode(', ', $userRewards);
            }
        }

        return implode("\n", $lines);
    }

    protected static function getChallengeContext(?User $user): string
    {
        $challengeData = Cache::remember('saiqu:challenges', self::ttl(), function () {
            return [
                'total' => Challenge::count(),
                'active' => Challenge::where('status', 'active')->count(),
            ];
        });

        $lines = ["CHALLENGES: Total={$challengeData['total']}, Aktif={$challengeData['active']}."];

        if ($user) {
            $userChallenges = Cache::remember("saiqu:user_challenges:{$user->id}", self::userTtl(), function () use ($user) {
                $asChallenger = $user->challengesAsChallenger()->count();
                $asChallenged = $user->challengesAsChallenged()->count();
                $wins = $user->challengeResults()->count();
                return ['sent' => $asChallenger, 'received' => $asChallenged, 'wins' => $wins];
            });
            $lines[] = "Challenge kamu: Dikirim={$userChallenges['sent']}, Diterima={$userChallenges['received']}, Menang={$userChallenges['wins']}";
        }

        return implode("\n", $lines);
    }

    protected static function getDailyCheckinContext(?User $user): string
    {
        $lines = ["DAILY CHECKIN:"];

        if ($user) {
            $checkinData = Cache::remember("saiqu:daily_checkin:{$user->id}", self::userTtl(), function () use ($user) {
                $latest = DailyCheckin::where('user_id', $user->id)
                    ->orderBy('checkin_date', 'desc')
                    ->first();

                $totalDays = DailyCheckin::where('user_id', $user->id)->count();

                return [
                    'streak' => $latest ? $latest->current_streak : 0,
                    'last_date' => $latest ? $latest->checkin_date : null,
                    'total_days' => $totalDays,
                ];
            });

            $lines[] = "- Streak saat ini: {$checkinData['streak']} hari berturut-turut";
            $lines[] = "- Total hari checkin: {$checkinData['total_days']}";
            if ($checkinData['last_date']) {
                $lines[] = "- Terakhir checkin: {$checkinData['last_date']}";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getStatisticsContext(): string
    {
        return Cache::remember('saiqu:global_stats', self::ttl(), function () {
            $userCount = User::count();
            $attCount = Attendance::count();
            $teamCount = Team::count();
            $todayAtt = Attendance::whereDate('checkin_time', Carbon::today())->count();
            $activeNow = Attendance::where('is_active', true)->count();
            $quizCount = Quiz::count();
            $challengeCount = Challenge::count();

            return "STATISTIK SISTEM: "
                 . "Pengguna={$userCount}, "
                 . "Total Absensi={$attCount}, "
                 . "Absensi Hari Ini={$todayAtt}, "
                 . "Sedang Aktif={$activeNow}, "
                 . "Tim={$teamCount}, "
                 . "Quiz={$quizCount}, "
                 . "Challenge={$challengeCount}.";
        });
    }

    protected static function getFeatureGuide(): string
    {
        return Cache::remember('saiqu:feature_guide', self::ttl() * 24, function () {
            return "FITUR HADIRKUGO v2.0:\n"
                 . "1. Dashboard — Ringkasan aktivitas, EXP, level progress, live activity, highlights, top global podium, rank rivalry\n"
                 . "2. Absensi QR — Check-in/out via QR Code, GPS-based, animasi smooth + quotes motivasi\n"
                 . "3. Tim — Buat/gabung tim, kelola anggota, transfer leadership, team leaderboard\n"
                 . "4. Poin Tesla — Dapat dari absensi, quiz, challenge. Bisa ditukar reward\n"
                 . "5. Level — Naik otomatis dari poin. Ada progress bar dan info EXP\n"
                 . "6. Leaderboard — 6 kategori (Level, Sesi, Durasi, Lokasi, Poin, Tim), update per jam, Top 50 dapat frame & gelar\n"
                 . "7. Achievement — Badge/medali dari pencapaian tertentu\n"
                 . "8. Quiz & Super Quiz — Kuis interaktif untuk dapat poin\n"
                 . "9. Reward — Spin/gacha hadiah, tukar poin dengan produk\n"
                 . "10. Challenge — Tantang user lain, bandingkan performa\n"
                 . "11. Daily Checkin — Streak harian untuk konsistensi\n"
                 . "12. Kalender — Lihat jadwal dan riwayat kehadiran\n"
                 . "13. Profil — Edit biodata, ganti nama (1x), lihat statistik\n"
                 . "14. Feedback — Kirim masukan, like/unlike, status tracking\n"
                 . "15. Journey — Perjalanan publik yang bisa dibagikan\n"
                 . "16. SaiQu AI — Asisten AI untuk tanya jawab seputar sistem\n"
                 . "17. Multi-bahasa — Bahasa Indonesia & English";
        });
    }

    protected static function getProfileContext(?User $user): string
    {
        if (!$user) return '';

        return Cache::remember("saiqu:profile_ctx:{$user->id}", self::userTtl(), function () use ($user) {
            $bio = $user->biodata;
            $lines = ["PROFILE DATA:"];
            $lines[] = "- Nama: {$user->display_name}";
            $lines[] = "- Email: " . substr($user->email, 0, 3) . '***' . substr($user->email, strpos($user->email, '@'));
            $lines[] = "- Nama sudah diganti: " . ($user->name_changed ? 'Ya' : 'Belum');
            $lines[] = "- Role: " . ($user->roles->pluck('name')->implode(', ') ?: 'Belum ada');

            if ($bio) {
                $lines[] = "- Nickname: " . ($bio->nickname ?: 'Belum diatur');
                $lines[] = "- Tanggal lahir: " . ($bio->birth_date ?: 'Belum diatur');
            }

            return implode("\n", $lines);
        });
    }

    protected static function getLocationContext(): string
    {
        return Cache::remember('saiqu:locations', self::ttl(), function () {
            $topLocations = LocationLeaderboard::orderBy('current_rank', 'asc')
                ->limit(10)
                ->get();

            $lines = ["LOKASI:"];
            if ($topLocations->isEmpty()) {
                $lines[] = "Belum ada data lokasi.";
            } else {
                $lines[] = "Top 10 Lokasi Paling Sering Dikunjungi:";
                foreach ($topLocations as $loc) {
                    $lines[] = "- #{$loc->current_rank} {$loc->location_name}: {$loc->score} kunjungan";
                }
            }
            return implode("\n", $lines);
        });
    }
}
