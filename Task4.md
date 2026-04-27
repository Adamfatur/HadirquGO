# SaiQu AI Agent (Laravel) — Full Blueprint

## 1. Definisi Sistem

SaiQu adalah AI Agent terbatas domain (domain-restricted assistant) yang hanya menjawab pertanyaan terkait sistem internal.

### Prinsip Utama

* Tidak menjawab di luar sistem
* Tidak berimajinasi (no hallucination)
* Hanya berdasarkan data sistem
* Melindungi data sensitif

---

## 2. Model AI (WAJIB)

SaiQu HANYA menggunakan model berikut:

* Model: **gemini-3-flash-preview**
* Tidak diperbolehkan fallback ke model lain
* Semua request HARUS menggunakan model ini secara konsisten

Contoh konfigurasi:

```php
$model = 'gemini-3-flash-preview';
```

Alasan:

* Konsistensi output
* Kontrol biaya
* Stabilitas perilaku AI

---

## 3. System Prompt (WAJIB DIGUNAKAN)

```
You are SaiQu, an AI Agent designed ONLY to answer questions related to the internal system.

CORE RULES:
1. You ONLY answer questions related to the system, its data, features, users (non-sensitive), and operations.
2. You MUST refuse any unrelated questions (general knowledge, opinions, or external topics).
3. You MUST NOT generate or assume information that does not exist in the system.
4. If data is unavailable, respond with: "Informasi tidak tersedia dalam sistem."
5. You MUST protect sensitive data:
   - Do NOT reveal passwords, tokens, private emails, or confidential data.
6. Keep answers concise, accurate, and based ONLY on provided data.

SCOPE OF KNOWLEDGE:
- System features
- User activity (non-sensitive)
- System statistics
- Internal documentation
- Logs and structured data

OUT-OF-SCOPE (STRICTLY FORBIDDEN):
- General knowledge
- Personal advice
- Speculation
- Anything unrelated to system context

RESPONSE POLICY:
- If question is out of scope → say:
  "Pertanyaan di luar cakupan sistem SaiQu."
- If partially related → answer only relevant part

STYLE:
- Professional
- Clear
- Not verbose
```

---

## 3. Arsitektur Sistem

### Flow:

User → API Laravel → Validator → Queue → AI → Response

---

## 4. Rate Limiting

```php
RateLimiter::for('saiqu', function ($request) {
    $user = $request->user();

    if (in_array($user->email, [
        'adam.faturahman@raharja.info',
        'nuke@raharja.info',
        'aini@raharja.info',
        'untung@raharja.info'
    ])) {
        return Limit::none();
    }

    return Limit::perDay(50)->by($user->id);
});
```

### Anti Spam:

```php
Limit::perMinute(10);
```

---

## 5. Queue System

Gunakan Redis + Laravel Queue Worker

Tujuan:

* Hindari blocking
* Kontrol beban server
* Retry jika gagal

---

## 6. Context Builder (RAG)

JANGAN kirim seluruh database ke AI.

Gunakan:

* Filtering data relevan
* Keyword search / embedding

```php
$context = KnowledgeService::getRelevantData($query);
```

---

## 7. Validasi Pertanyaan

```php
if (!QuestionValidator::isSystemRelated($question)) {
    return response()->json([
        'answer' => 'Pertanyaan di luar cakupan sistem SaiQu.'
    ]);
}
```

---

## 8. Proteksi Data Sensitif

```php
unset($data['password']);
unset($data['token']);
```

---

## 9. Multi User Handling

* Session per user
* History maksimal 3–5 message

---

## 10. Optimasi

* Cache jawaban
* Batasi token
* Gunakan model kecil untuk query ringan

---

## 11. Sistem Belajar

Gunakan RAG (Retrieval-Augmented Generation), bukan training ulang.

Strategi:

* Index data penting saja
* Cache pertanyaan populer
* Analisis log

---

## 12. Potensi Risiko

* Hallucination
* Data leakage
* Overload server
* Biaya tinggi

---

## 13. Upgrade Lanjutan

* Intent classification
* Semantic search (embedding)
* Feedback loop

---

## 14. Kesimpulan

SaiQu harus:

* Ketat dalam scope
* Efisien dalam data
* Aman dalam informasi
* Stabil dalam performa

Ini bukan chatbot biasa, tapi sistem AI terkontrol.

Floating Icon AI ini harus selalu muncul di semua tempat!
