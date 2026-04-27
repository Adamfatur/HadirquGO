<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\RateLimiting\Limit;
use App\Services\SaiQu\QuestionValidator;
use App\Services\SaiQu\KnowledgeService;
use App\Services\SaiQu\GeminiService;
use App\Models\SaiquConversation;

class SaiQuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle incoming chat message via AJAX.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $message = trim($request->input('message'));

        // --- Rate Limiting ---
        if (!$this->checkRateLimit($user)) {
            return response()->json([
                'success' => false,
                'answer' => 'Kamu telah mencapai batas penggunaan SaiQu. Silakan coba lagi nanti.',
            ], 429);
        }

        // --- Question Validation ---
        if (!QuestionValidator::isSystemRelated($message)) {
            return response()->json([
                'success' => true,
                'answer' => 'Wah, itu di luar jangkauan aku nih 😄 Aku cuma bisa bantu soal HadirkuGO ya! Coba tanya tentang poin, absensi, level, atau fitur lainnya~',
            ]);
        }

        // --- Build Context (RAG) ---
        // Include recent history in query for better topic matching
        $context = '';
        try {
            $enrichedQuery = $this->enrichQueryWithHistory($message, $history);
            $context = KnowledgeService::getRelevantData($enrichedQuery, $user);
        } catch (\Exception $e) {
            \Log::warning('SaiQu context build failed', ['error' => $e->getMessage()]);
        }

        // --- Get Conversation History ---
        $history = [];
        try {
            $history = SaiquConversation::getHistory($user->id);
        } catch (\Exception $e) {
            // Table may not exist yet
        }

        // --- Call Gemini (this always returns a string, never throws) ---
        $gemini = new GeminiService();
        $answer = $gemini->chat($message, $context, $history);

        // --- Store Conversation ---
        try {
            SaiquConversation::storeMessage($user->id, 'user', $message);
            SaiquConversation::storeMessage($user->id, 'model', $answer);
            $this->trimHistory($user->id);
        } catch (\Exception $e) {
            // Table may not exist yet — still return the answer
        }

        return response()->json([
            'success' => true,
            'answer' => $answer,
        ]);
    }

    /**
     * Clear conversation history.
     */
    public function clearHistory()
    {
        try {
            SaiquConversation::clearHistory(Auth::id());
        } catch (\Exception $e) {
            // Table may not exist yet
        }

        // Invalidate suggestion cache so it refreshes
        Cache::forget('saiqu:suggestions:' . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Riwayat percakapan telah dihapus.',
        ]);
    }

    /**
     * Get personalized suggested questions for the current user.
     */
    public function suggestions()
    {
        $user = Auth::user();
        $suggestions = KnowledgeService::getSuggestedQuestions($user);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Check rate limits for the user.
     */
    protected function checkRateLimit($user): bool
    {
        $unlimitedEmails = config('saiqu.rate_limit.unlimited_emails', []);

        if (in_array($user->email, $unlimitedEmails)) {
            return true;
        }

        // Per-minute limit (anti-spam)
        $minuteKey = 'saiqu_minute:' . $user->id;
        if (RateLimiter::tooManyAttempts($minuteKey, config('saiqu.rate_limit.per_minute', 10))) {
            return false;
        }
        RateLimiter::hit($minuteKey, 60);

        // Per-day limit
        $dayKey = 'saiqu_day:' . $user->id;
        if (RateLimiter::tooManyAttempts($dayKey, config('saiqu.rate_limit.per_day', 50))) {
            return false;
        }
        RateLimiter::hit($dayKey, 86400);

        return true;
    }

    /**
     * Trim conversation history to keep only the latest N pairs.
     */
    protected function trimHistory(int $userId): void
    {
        $maxMessages = config('saiqu.max_history', 5) * 2;

        $count = SaiquConversation::where('user_id', $userId)->count();

        if ($count > $maxMessages) {
            $idsToKeep = SaiquConversation::where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->limit($maxMessages)
                ->pluck('id');

            SaiquConversation::where('user_id', $userId)
                ->whereNotIn('id', $idsToKeep)
                ->delete();
        }
    }

    /**
     * Enrich the current query with recent conversation history
     * so the context builder can resolve references like "dia", "orang itu", etc.
     * This helps topic matching find the right data.
     */
    protected function enrichQueryWithHistory(string $message, array $history): string
    {
        if (empty($history)) {
            return $message;
        }

        // Check if message contains pronouns/references that need context
        $hasReference = preg_match('/\b(dia|nya|mereka|orang itu|yang tadi|tersebut|itu|ini)\b/i', $message);
        $isShortQuery = mb_strlen(trim($message)) < 40;

        if (!$hasReference && !$isShortQuery) {
            return $message;
        }

        // Take last 4 messages (2 pairs) for context enrichment
        $recentHistory = array_slice($history, -4);
        $historyText = '';
        foreach ($recentHistory as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'SaiQu';
            $historyText .= "{$role}: {$msg['text']}\n";
        }

        return $message . "\n\n[KONTEKS PERCAKAPAN SEBELUMNYA:\n{$historyText}]";
    }
}
