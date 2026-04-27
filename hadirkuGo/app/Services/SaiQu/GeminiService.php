<?php

namespace App\Services\SaiQu;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected array $models;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->models = [
            config('saiqu.model', 'gemini-2.5-flash'),
            config('saiqu.fallback_model', 'gemini-3-flash-preview'),
            'gemini-2.5-flash-lite', // last resort — lightest, most stable
        ];
        $this->apiKey  = config('saiqu.api_key', '');
        $this->baseUrl = config('saiqu.api_url', 'https://generativelanguage.googleapis.com/v1beta/models/');
    }

    /**
     * Send a message to Gemini. Cascades through models on failure.
     */
    public function chat(string $userMessage, string $context = '', array $history = []): string
    {
        if (empty($this->apiKey)) {
            Log::error('SaiQu: GEMINI_API_KEY is not set');
            return 'Konfigurasi API belum lengkap. Hubungi administrator.';
        }

        $payload = $this->buildPayload($userMessage, $context, $history);
        $lastError = '';

        foreach ($this->models as $i => $model) {
            if ($i > 0) {
                Log::info("SaiQu: Trying fallback model #{$i}: {$model}");
            }

            $result = $this->callModel($model, $payload);

            if ($result['success']) {
                return $result['text'];
            }

            $lastError = $result['error'];

            // Only cascade on server-side / rate-limit errors
            if (!in_array($result['status'], [429, 500, 502, 503, 0])) {
                return $result['error']; // client error (403, 404, safety) — don't retry
            }
        }

        return $lastError ?: 'Semua server AI sedang sibuk. Coba lagi nanti ya! 🕐';
    }

    /**
     * Build the Gemini API payload.
     */
    protected function buildPayload(string $userMessage, string $context, array $history): array
    {
        $systemPrompt = config('saiqu.system_prompt', '');
        $contents = [];

        foreach ($history as $msg) {
            $contents[] = [
                'role'  => ($msg['role'] === 'user') ? 'user' : 'model',
                'parts' => [['text' => $msg['text']]],
            ];
        }

        $finalMessage = $userMessage;
        if (!empty($context)) {
            // Trim context to max ~2000 chars to keep payload light
            $trimmedContext = mb_substr($context, 0, 2000);
            $finalMessage = "KONTEKS DATA SISTEM:\n{$trimmedContext}\n\nPERTANYAAN USER:\n{$userMessage}";
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $finalMessage]],
        ];

        return [
            'contents' => $contents,
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'generationConfig' => [
                'temperature'     => (float) config('saiqu.temperature', 0.3),
                'maxOutputTokens' => (int) config('saiqu.max_output_tokens', 1024),
            ],
        ];
    }

    /**
     * Call a specific Gemini model. One retry on 429/503.
     */
    protected function callModel(string $model, array $payload): array
    {
        $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;

        for ($attempt = 0; $attempt <= 1; $attempt++) {
            try {
                $response = Http::timeout(15)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                $statusCode = $response->status();
                $body = $response->json();

                // Success
                if ($response->successful() && isset($body['candidates'][0]['content']['parts'][0]['text'])) {
                    return ['success' => true, 'text' => $body['candidates'][0]['content']['parts'][0]['text'], 'error' => '', 'status' => $statusCode];
                }

                // Retry once on 429 or 503
                if (in_array($statusCode, [429, 503]) && $attempt === 0) {
                    Log::warning("SaiQu: {$statusCode} on {$model}, retrying after 2s");
                    sleep(2);
                    continue;
                }

                $errorMsg = $body['error']['message'] ?? 'Unknown';
                $errorCode = $body['error']['code'] ?? $statusCode;
                Log::error("SaiQu API error on {$model}", ['status' => $statusCode, 'message' => $errorMsg]);

                // Safety blocks — don't cascade
                if (isset($body['candidates'][0]['finishReason']) && $body['candidates'][0]['finishReason'] === 'SAFETY') {
                    return ['success' => false, 'text' => '', 'status' => 999, 'error' => 'Pertanyaan tidak bisa dijawab karena alasan keamanan.'];
                }
                if (isset($body['promptFeedback']['blockReason'])) {
                    return ['success' => false, 'text' => '', 'status' => 999, 'error' => 'Pertanyaan diblokir filter keamanan. Coba ubah ya!'];
                }

                return ['success' => false, 'text' => '', 'status' => $statusCode, 'error' => $this->friendlyError($statusCode, $errorCode)];

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::error("SaiQu timeout on {$model}", ['msg' => $e->getMessage()]);
                return ['success' => false, 'text' => '', 'status' => 0, 'error' => 'Koneksi ke AI timeout. Coba lagi ya! ⏳'];

            } catch (\Exception $e) {
                Log::error("SaiQu exception on {$model}", ['msg' => $e->getMessage()]);
                return ['success' => false, 'text' => '', 'status' => 0, 'error' => 'Terjadi kesalahan. Coba lagi ya!'];
            }
        }

        return ['success' => false, 'text' => '', 'status' => 503, 'error' => 'Server AI sibuk. Coba lagi nanti ya! 🕐'];
    }

    protected function friendlyError(int $statusCode, $errorCode): string
    {
        return match ($statusCode) {
            404 => 'Model AI tidak ditemukan. Hubungi administrator.',
            403 => 'API key tidak valid. Hubungi administrator.',
            429 => 'Server AI lagi rame nih. Tunggu sebentar ya! 🕐',
            503 => 'Server AI lagi sibuk. Coba lagi sebentar ya! 🕐',
            default => "AI lagi error nih (kode {$errorCode}). Coba lagi ya!",
        };
    }
}
