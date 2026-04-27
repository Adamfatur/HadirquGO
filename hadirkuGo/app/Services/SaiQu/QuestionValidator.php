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
        'qr', 'qrcode', 'scan',
        'tim', 'team', 'anggota', 'member', 'kelompok',
        'poin', 'point', 'skor', 'score',
        'level', 'rank', 'ranking', 'peringkat', 'leaderboard',
        'achievement', 'pencapaian', 'badge', 'medali',
        'quiz', 'kuis', 'soal', 'ujian', 'superquiz',
        'reward', 'hadiah', 'redeem', 'tukar', 'produk', 'product',
        'challenge', 'tantangan',
        'statistik', 'statistic', 'laporan', 'report',
        'dashboard', 'beranda',
        'profil', 'profile', 'akun', 'account',
        'bisnis', 'business', 'lokasi', 'location',
        'kalender', 'calendar', 'jadwal', 'schedule',
        'notifikasi', 'notification',
        'testimoni', 'testimony', 'feedback',
        'viewboard', 'banner',
        // Sistem umum
        'sistem', 'system', 'fitur', 'feature', 'aplikasi', 'app',
        'hadirkugo', 'hadirku', 'saiqu',
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

        // Default: reject
        return false;
    }
}
