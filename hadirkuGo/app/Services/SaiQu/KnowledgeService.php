<?php

namespace App\Services\SaiQu;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Team;
use App\Models\UserPointSummary;
use App\Models\UserLeaderboard;
use App\Models\Level;
use App\Models\Achievement;
use App\Models\Product;
use App\Models\Quiz;
use App\Models\Challenge;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class KnowledgeService
{
    /**
     * Build relevant context based on the user's query.
     */
    public static function getRelevantData(string $query, ?User $user = null): string
    {
        $lower = mb_strtolower($query);
        $context = [];

        $context[] = self::getSystemInfo();

        if ($user) {
            $context[] = self::getUserContext($user);
        }

        // Topic matching — can match multiple topics
        if (self::match($lower, ['absen', 'hadir', 'checkin', 'checkout', 'jam', 'waktu', 'hari ini', 'kemarin'])) {
            $context[] = self::getAttendanceContext($user, $lower);
        }

        if (self::match($lower, ['poin', 'point', 'skor', 'score', 'jarak', 'banding', 'selisih'])) {
            $context[] = self::getPointsContext($user, $lower);
        }

        if (self::match($lower, ['level', 'tingkat'])) {
            $context[] = self::getLevelContext($user);
        }

        if (self::match($lower, ['rank', 'ranking', 'peringkat', 'leaderboard', 'top', 'juara', 'tertinggi', 'terbanyak', 'nomor 1', 'no 1', 'siapa'])) {
            $context[] = self::getLeaderboardContext($user, $lower);
        }

        if (self::match($lower, ['tim', 'team', 'anggota', 'member', 'kelompok', 'gabung', 'mana', 'dimana'])) {
            $context[] = self::getTeamContext($user);
        }

        if (self::match($lower, ['achievement', 'pencapaian', 'badge', 'medali'])) {
            $context[] = self::getAchievementContext();
        }

        if (self::match($lower, ['quiz', 'kuis', 'soal', 'ujian', 'superquiz'])) {
            $context[] = self::getQuizContext();
        }

        if (self::match($lower, ['reward', 'hadiah', 'redeem', 'tukar', 'produk', 'product'])) {
            $context[] = self::getRewardContext();
        }

        if (self::match($lower, ['challenge', 'tantangan'])) {
            $context[] = self::getChallengeContext();
        }

        if (self::match($lower, ['statistik', 'laporan', 'total', 'jumlah', 'berapa banyak'])) {
            $context[] = self::getStatisticsContext();
        }

        if (self::match($lower, ['fitur', 'feature', 'cara', 'bagaimana', 'fungsi', 'menu', 'bisa apa'])) {
            $context[] = self::getFeatureGuide();
        }

        return implode("\n\n", array_filter($context));
    }

    protected static function match(string $query, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (mb_strpos($query, $kw) !== false) return true;
        }
        return false;
    }

    protected static function getSystemInfo(): string
    {
        return "SYSTEM: HadirkuGO — platform kehadiran digital berbasis QR Code. Fitur: Absensi QR, Tim, Poin, Level, Leaderboard, Achievement, Quiz, Reward, Challenge.";
    }

    protected static function getUserContext(?User $user): string
    {
        if (!$user) return '';
        $summary = $user->pointSummary;
        $level = $user->userLevel?->level;
        $roles = $user->roles->pluck('name')->implode(', ');

        return "USER: Nama={$user->display_name}, Role={$roles}, "
             . "Total Poin=" . ($summary->total_points ?? 0) . ", "
             . "Current Poin=" . ($summary->current_points ?? 0) . ", "
             . "Level=" . ($level->name ?? 'Belum ada') . ".";
    }

    protected static function getAttendanceContext(?User $user, string $query = ''): string
    {
        $lines = ["ATTENDANCE DATA:"];

        if ($user) {
            // Today's attendance
            $today = Carbon::today();
            $todayAttendances = Attendance::where('user_id', $user->id)
                ->whereDate('checkin_time', $today)
                ->orderBy('checkin_time', 'asc')
                ->with('attendanceLocation')
                ->limit(10)
                ->get();

            if ($todayAttendances->isNotEmpty()) {
                $lines[] = "Absensi hari ini ({$today->format('d M Y')}):";
                foreach ($todayAttendances as $att) {
                    $loc = $att->attendanceLocation->name ?? 'Unknown';
                    $checkin = $att->checkin_time ? $att->checkin_time->format('H:i') : '-';
                    $checkout = $att->checkout_time ? $att->checkout_time->format('H:i') : 'Belum checkout';
                    $duration = $att->total_daily_duration ? round($att->total_daily_duration / 60, 1) . ' jam' : '-';
                    $lines[] = "- Check-in: {$checkin}, Check-out: {$checkout}, Lokasi: {$loc}, Durasi: {$duration}";
                }
            } else {
                $lines[] = "- Kamu belum absen hari ini.";
            }

            // Active attendance right now
            $active = Attendance::where('user_id', $user->id)->where('is_active', true)->first();
            if ($active) {
                $loc = $active->attendanceLocation->name ?? 'Unknown';
                $since = $active->checkin_time ? $active->checkin_time->format('H:i') : '-';
                $elapsed = $active->checkin_time ? $active->checkin_time->diffInMinutes(now()) : 0;
                $lines[] = "- SEDANG AKTIF: Check-in sejak {$since} di {$loc} ({$elapsed} menit berlalu)";
            }

            // Yesterday (if asked)
            if (self::match($query, ['kemarin', 'yesterday'])) {
                $yesterday = Carbon::yesterday();
                $yesterdayAtt = Attendance::where('user_id', $user->id)
                    ->whereDate('checkin_time', $yesterday)
                    ->orderBy('checkin_time', 'asc')
                    ->with('attendanceLocation')
                    ->limit(5)
                    ->get();
                if ($yesterdayAtt->isNotEmpty()) {
                    $lines[] = "Absensi kemarin ({$yesterday->format('d M Y')}):";
                    foreach ($yesterdayAtt as $att) {
                        $loc = $att->attendanceLocation->name ?? 'Unknown';
                        $checkin = $att->checkin_time ? $att->checkin_time->format('H:i') : '-';
                        $checkout = $att->checkout_time ? $att->checkout_time->format('H:i') : '-';
                        $lines[] = "- Check-in: {$checkin}, Check-out: {$checkout}, Lokasi: {$loc}";
                    }
                }
            }

            $total = Attendance::where('user_id', $user->id)->count();
            $thisMonth = Attendance::where('user_id', $user->id)
                ->whereMonth('checkin_time', now()->month)
                ->whereYear('checkin_time', now()->year)
                ->count();
            $lines[] = "- Total absensi kamu: {$total}, Bulan ini: {$thisMonth}";
        }

        return implode("\n", $lines);
    }

    protected static function getPointsContext(?User $user, string $query = ''): string
    {
        if (!$user) return '';
        $summary = $user->pointSummary;
        if (!$summary) return "POINTS: Belum ada data poin.";

        $lines = ["POINTS DATA:"];
        $lines[] = "- Poin kamu: Total={$summary->total_points}, Current={$summary->current_points}";

        // If comparing with someone
        if (self::match($query, ['jarak', 'banding', 'selisih', 'dengan', 'sama', 'vs'])) {
            // Try to find mentioned user name
            $top5 = UserPointSummary::with('user')
                ->orderByDesc('total_points')
                ->limit(5)
                ->get();
            $lines[] = "Top 5 poin tertinggi:";
            foreach ($top5 as $i => $ups) {
                $name = $ups->user->display_name ?? 'Unknown';
                $diff = $summary->total_points - $ups->total_points;
                $diffStr = $diff >= 0 ? "+{$diff}" : "{$diff}";
                $lines[] = "- #" . ($i + 1) . " {$name}: {$ups->total_points} poin (selisih: {$diffStr})";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getLevelContext(?User $user): string
    {
        $levels = Cache::remember('saiqu_levels', 3600, function () {
            return Level::orderBy('minimum_points')->get(['name', 'minimum_points', 'maximum_points']);
        });

        $lines = ["LEVEL SYSTEM:"];
        foreach ($levels as $lv) {
            $lines[] = "- {$lv->name}: {$lv->minimum_points}-{$lv->maximum_points} poin";
        }

        if ($user && $user->userLevel) {
            $lines[] = "Level kamu: " . ($user->userLevel->level->name ?? 'Unknown');
        }

        return implode("\n", $lines);
    }

    protected static function getLeaderboardContext(?User $user, string $query = ''): string
    {
        $lines = ["LEADERBOARD DATA:"];

        // Top 10 by points
        $topPoints = UserLeaderboard::with('user')
            ->where('category', 'top_points')
            ->orderBy('current_rank', 'asc')
            ->limit(10)
            ->get();

        if ($topPoints->isNotEmpty()) {
            $lines[] = "Top 10 Poin Tertinggi:";
            foreach ($topPoints as $entry) {
                $name = $entry->user->display_name ?? 'Unknown';
                $title = $entry->title ? " ({$entry->title})" : '';
                $lines[] = "- #{$entry->current_rank} {$name}: {$entry->score} poin{$title}";
            }
        }

        // If user asks about a specific person, search by name
        $nameSearch = self::extractPersonName($query);
        if ($nameSearch) {
            $foundUsers = User::where('name', 'like', "%{$nameSearch}%")
                ->limit(3)
                ->get();

            foreach ($foundUsers as $found) {
                $foundSummary = $found->pointSummary;
                $foundRank = UserLeaderboard::where('category', 'top_points')
                    ->where('user_id', $found->id)
                    ->first();
                $foundLevel = $found->userLevel?->level;

                $rankStr = $foundRank ? "#{$foundRank->current_rank}" : 'Tidak di leaderboard';
                $poinStr = $foundSummary ? $foundSummary->total_points : 0;
                $levelStr = $foundLevel ? $foundLevel->name : 'Unknown';
                $titleStr = $foundRank && $foundRank->title ? " ({$foundRank->title})" : '';

                $lines[] = "INFO USER '{$found->display_name}': Ranking={$rankStr}, Poin={$poinStr}, Level={$levelStr}{$titleStr}";
            }
        }

        // User's own rank
        if ($user) {
            $myRank = UserLeaderboard::where('category', 'top_points')
                ->where('user_id', $user->id)
                ->first();
            if ($myRank) {
                $lines[] = "Ranking kamu: #{$myRank->current_rank} (skor: {$myRank->score})";
            } else {
                // Calculate approximate rank from point summary
                $myPts = $user->pointSummary->total_points ?? 0;
                $approxRank = UserPointSummary::where('total_points', '>', $myPts)->count() + 1;
                $lines[] = "Ranking kamu (estimasi): #{$approxRank} ({$myPts} poin)";
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Try to extract a person's name from the query.
     * Looks for patterns like "prof untung", "si aulia", or just proper nouns.
     */
    protected static function extractPersonName(string $query): ?string
    {
        // Remove common question words
        $cleaned = preg_replace('/\b(siapa|berapa|apa|dimana|kapan|bagaimana|prof|professor|pak|bu|ibu|bapak|si|nya|itu|yang|di|ke|dari|dengan|untuk|dan|atau|ini|aku|saya|kamu|dia|mereka)\b/i', '', $query);
        $cleaned = preg_replace('/\b(peringkat|ranking|rank|point|poin|level|top|nomor|no|mau|tau|tahu|info|informasi|tentang)\b/i', '', $cleaned);
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));

        // If remaining text is 2+ chars and looks like a name, return it
        if (mb_strlen($cleaned) >= 2 && !preg_match('/^\d+$/', $cleaned)) {
            return $cleaned;
        }

        return null;
    }

    protected static function getTeamContext(?User $user): string
    {
        $lines = ["TEAM DATA:"];
        $lines[] = "Total tim: " . Team::count();

        if ($user) {
            $teamsLed = $user->teamsLed()->get();
            $teamsJoined = $user->teamsJoined()->get();
            $allTeams = $teamsLed->merge($teamsJoined)->unique('id');

            if ($allTeams->isNotEmpty()) {
                foreach ($allTeams as $team) {
                    $mc = $team->members()->count();
                    $lines[] = "- Tim: {$team->name} (Anggota: {$mc})";
                }
            } else {
                $lines[] = "- Kamu belum bergabung tim.";
            }
        }

        return implode("\n", $lines);
    }

    protected static function getAchievementContext(): string
    {
        return Cache::remember('saiqu_achievements', 3600, function () {
            $items = Achievement::select('name', 'description')->limit(15)->get();
            $lines = ["ACHIEVEMENTS:"];
            foreach ($items as $a) {
                $lines[] = "- {$a->name}: {$a->description}";
            }
            return implode("\n", $lines);
        });
    }

    protected static function getQuizContext(): string
    {
        return "QUIZ: Total quiz tersedia: " . Quiz::count() . ".";
    }

    protected static function getRewardContext(): string
    {
        return Cache::remember('saiqu_rewards', 3600, function () {
            $products = Product::select('name', 'description')->where('stock_quantity', '>', 0)->limit(10)->get();
            $lines = ["REWARDS:"];
            foreach ($products as $p) {
                $lines[] = "- {$p->name}: {$p->description}";
            }
            return $products->isEmpty() ? "REWARDS: Belum ada produk tersedia." : implode("\n", $lines);
        });
    }

    protected static function getChallengeContext(): string
    {
        $active = Challenge::where('status', 'active')->count();
        return "CHALLENGES: Total=" . Challenge::count() . ", Aktif={$active}.";
    }

    protected static function getStatisticsContext(): string
    {
        return Cache::remember('saiqu_stats', 3600, function () {
            return "STATISTIK: Pengguna=" . User::count()
                 . ", Absensi=" . Attendance::count()
                 . ", Tim=" . Team::count() . ".";
        });
    }

    protected static function getFeatureGuide(): string
    {
        return "FITUR: Dashboard (ringkasan), Absensi QR (check-in/out), Tim (kelola tim), Poin (dari aktivitas), Level (naik dari poin), Leaderboard (ranking), Achievement (pencapaian), Quiz (kuis poin), Reward (tukar poin), Challenge (tantangan), Kalender (jadwal), Profil (pengaturan), Feedback (masukan).";
    }
}
