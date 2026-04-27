<?php

namespace App\Services\SaiQu;

class QuestionValidator
{
    /**
     * System-related keywords (Bahasa Indonesia + English).
     */
    protected static array $systemKeywords = [
        // Fitur sistem
        'absensi', 'attendance', 'kehadiran', 'hadir', 'checkin', 'checkout',
        'check-in', 'check-out', 'presensi', 'datang', 'pulang',
        'qr', 'qrcode', 'scan',
        'tim', 'team', 'anggota', 'member', 'kelompok',
        'poin', 'point', 'skor', 'score', 'tesla',
        'level', 'rank', 'ranking', 'peringkat', 'leaderboard', 'posisi',
        'achievement', 'pencapaian', 'badge', 'medali', 'lencana',
        'quiz', 'kuis', 'soal', 'ujian', 'superquiz', 'super quiz',
        'reward', 'hadiah', 'redeem', 'tukar', 'produk', 'product', 'spin', 'gacha',
        'challenge', 'tantangan', 'lawan', 'duel',
        'statistik', 'statistic', 'laporan', 'report', 'rekap',
        'dashboard', 'beranda',
        'profil', 'profile', 'akun', 'account', 'biodata',
        'bisnis', 'business', 'lokasi', 'location', 'tempat',
        'kalender', 'calendar', 'jadwal', 'schedule',
        'notifikasi', 'notification',
        'testimoni', 'testimony', 'feedback',
        'viewboard', 'banner',
        'streak', 'harian', 'daily', 'berturut', 'konsisten',
        'journey', 'perjalanan',
        // Sistem umum
        'sistem', 'system', 'fitur', 'feature', 'aplikasi', 'app',
        'hadirqugo', 'hadirku', 'saiqu',
        'cara', 'bagaimana', 'how', 'apa', 'what',
        'data', 'user', 'pengguna', 'mahasiswa', 'student',
        'dosen', 'lecturer', 'owner', 'admin',
        'login', 'masuk', 'logout', 'keluar',
        // Self-referencing / personal data queries
        'diriku', 'diri', 'saya', 'aku', 'gue', 'gw', 'ku',
        'informasi', 'info', 'tentang', 'milik',
        'nama', 'email', 'role', 'peran',
        'berapa', 'jumlah', 'total', 'banyak',
        'progress', 'kemajuan', 'aktivitas', 'activity',
        'riwayat', 'history', 'catatan', 'log',
        'bantuan', 'bantu', 'help', 'tolong',
        // Comparison / follow-up references
        'jarak', 'selisih', 'banding', 'dibanding', 'rival',
        'dia', 'nya', 'mereka', 'siapa', 'top',
        'unggul', 'tertinggal', 'kalah', 'menang',
    ];

    /**
     * Blocked topics.
     */
    protected static array $blockedPatterns = [
        '/\b(politik|agama|religion|gossip|gosip)\b/i',
        '/\b(resep|masak|cook|recipe)\b/i',
        '/\b(cuaca|weather|berita|news)\b/i',
        '/\b(saham|stock|crypto|bitcoin)\b/i',
        '/\b(ceritakan|dongeng|puisi|poem|story)\b/i',
    ];

    /**
     * Check if the question is related to the system.
     */
    public static function isSystemRelated(string $question): bool
    {
        $lower = mb_strtolower(trim($question));

        // Block obvious off-topic
        foreach (self::$blockedPatterns as $pattern) {
            if (preg_match($pattern, $lower)) {
                return false;
            }
        }

        // Check for system keywords
        foreach (self::$systemKeywords as $keyword) {
            if (mb_strpos($lower, $keyword) !== false) {
                return true;
            }
        }

        // Short greetings are allowed (halo, hi, etc.)
        if (mb_strlen($lower) <= 30 && preg_match('/^(halo|hai|hi|hello|hey|selamat|terima kasih|thanks|ok|oke)/i', $lower)) {
            return true;
        }

        // Short follow-up questions are likely related to previous conversation
        // e.g. "berapa?", "siapa?", "dimana?", "lalu?", "terus?"
        if (mb_strlen($lower) <= 50 && preg_match('/\?$/', $lower)) {
            return true;
        }

        // Default: reject
        return false;
    }
}
