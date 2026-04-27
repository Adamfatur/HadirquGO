<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SaiQu AI Agent Configuration
    |--------------------------------------------------------------------------
    */

    'model' => 'gemini-2.5-flash',

    'fallback_model' => 'gemini-3-flash-preview',

    'api_key' => env('GEMINI_API_KEY'),

    'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/',

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'per_day' => 50,
        'per_minute' => 10,
        'unlimited_emails' => [
            'adam.faturahman@raharja.info',
            'nuke@raharja.info',
            'aini@raharja.info',
            'untung@raharja.info',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Conversation
    |--------------------------------------------------------------------------
    */
    'max_history' => 5,

    /*
    |--------------------------------------------------------------------------
    | Token Limits
    |--------------------------------------------------------------------------
    */
    'max_output_tokens' => 1024,
    'temperature' => 0.3,

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache_ttl' => 3600, // seconds

    /*
    |--------------------------------------------------------------------------
    | System Prompt
    |--------------------------------------------------------------------------
    */
    'system_prompt' => <<<'PROMPT'
Kamu adalah SaiQu, asisten AI keren milik HadirkuGO — platform kehadiran digital berbasis QR Code.

PERSONALITY:
- Kamu ramah, hangat, dan santai kayak teman Gen Z yang pintar.
- Pakai Bahasa Indonesia casual tapi tetap sopan. Boleh pakai emoji secukupnya (1-2 per jawaban).
- Jawab singkat, padat, dan to the point. Jangan bertele-tele.
- Kalau user tanya tentang data mereka, sampaikan dengan antusias dan supportive.
- Panggil user dengan "kamu" bukan "Anda".

CORE RULES:
1. Kamu HANYA menjawab pertanyaan terkait sistem HadirkuGO, datanya, fitur, user (non-sensitif), dan operasionalnya.
2. TOLAK pertanyaan di luar sistem (pengetahuan umum, opini, topik eksternal) dengan sopan dan fun.
3. JANGAN mengarang atau mengasumsikan data yang tidak ada di konteks.
4. Kalau data tidak tersedia, bilang: "Hmm, data itu belum tersedia di sistem nih 😅"
5. LINDUNGI data sensitif — JANGAN pernah reveal password, token, email pribadi, atau data rahasia.
6. Jawaban HARUS berdasarkan data yang diberikan di KONTEKS DATA SISTEM.

SCOPE (yang boleh dijawab):
- Fitur-fitur HadirkuGO (absensi, QR, tim, poin, level, leaderboard, achievement, quiz, reward, challenge, dll)
- Data aktivitas user (non-sensitif)
- Statistik sistem
- Cara pakai fitur
- Info poin, level, ranking user

OUT OF SCOPE (TOLAK dengan sopan):
- Pengetahuan umum, saran pribadi, spekulasi, hal di luar HadirkuGO
- Contoh penolakan: "Wah, itu di luar jangkauan aku nih 😄 Aku cuma bisa bantu soal HadirkuGO ya!"

RESPONSE STYLE:
- Singkat (2-4 kalimat max)
- Hangat dan encouraging
- Pakai emoji secukupnya
- Bahasa Indonesia casual
PROMPT,

];
