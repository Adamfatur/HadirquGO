<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Validasi kepemilikan dan relasi antara Business, Quiz, dan (jika ada) Question.
     *
     * @param  \App\Models\Business  $business
     * @param  \App\Models\Quiz      $quiz
     * @param  \App\Models\Question|null $question
     * @return void
     */
    protected function authorizeBusinessQuiz(Business $business, Quiz $quiz, ?Question $question = null)
    {
        // Pastikan quiz milik business yang sesuai dan owner sama dengan user yang sedang login
        if ($business->owner_id !== Auth::id() || $quiz->business_id !== $business->id) {
            abort(403, 'Unauthorized action.');
        }

        // Jika question disediakan, pastikan question tersebut memang bagian dari quiz
        if ($question && $question->quiz_id !== $quiz->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Menampilkan detail soal beserta opsi jawabannya.
     */
    public function show($businessUniqueId, $quizId, $questionId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();
        $quiz     = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);

        $this->authorizeBusinessQuiz($business, $quiz, $question);

        // Ambil semua opsi jawaban untuk pertanyaan ini, diurutkan berdasarkan option_letter
        $options = $question->options()->orderBy('option_letter', 'asc')->get();

        return view('owner.questions.show', compact('business', 'quiz', 'question', 'options'));
    }

    /**
     * Menampilkan form untuk membuat soal baru.
     */
    public function create($businessUniqueId, $quizId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();
        $quiz     = Quiz::findOrFail($quizId);

        $this->authorizeBusinessQuiz($business, $quiz);

        return view('owner.questions.create', compact('business', 'quiz'));
    }

    /**
     * Menyimpan soal dan opsi jawabannya ke dalam basis data.
     */
    public function store(Request $request, $businessUniqueId, $quizId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();
        $quiz     = Quiz::findOrFail($quizId);

        $this->authorizeBusinessQuiz($business, $quiz);

        $request->validate([
            'question_text'           => 'required|string|max:1000',
            'options'                 => 'required|array|size:4',
            'options.*.option_text'   => 'required|string|max:255',
            'options.*.is_correct'    => 'sometimes|boolean',
            'options.*.option_letter' => 'required|string|max:1',
        ]);

        // Buat soal terlebih dahulu
        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
        ]);

        // Simpan setiap opsi jawaban untuk soal yang baru dibuat
        foreach ($request->options as $optData) {
            $question->options()->create([
                'option_letter' => $optData['option_letter'],
                'option_text'   => $optData['option_text'],
                'is_correct'    => $optData['is_correct'] ?? false,
            ]);
        }

        return redirect()
            ->route('owner.quizzes.show', [$business->business_unique_id, $quiz->id])
            ->with('success', 'Question and options added successfully.');
    }

    /**
     * Menampilkan form edit untuk soal yang sudah ada.
     */
    public function edit($businessUniqueId, $quizId, $questionId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();
        $quiz     = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);

        $this->authorizeBusinessQuiz($business, $quiz, $question);

        // Ambil opsi jawaban untuk ditampilkan pada form edit, diurutkan berdasarkan option_letter
        $options = $question->options()->orderBy('option_letter', 'asc')->get();

        return view('owner.questions.edit', compact('business', 'quiz', 'question', 'options'));
    }

    /**
     * Memperbarui soal dan opsi jawabannya.
     */
    public function update(Request $request, $businessUniqueId, $quizId, $questionId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();
        $quiz     = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);

        $this->authorizeBusinessQuiz($business, $quiz, $question);

        $request->validate([
            'question_text'           => 'required|string|max:1000',
            'options'                 => 'required|array|size:4',
            'options.*.option_text'   => 'required|string|max:255',
            'options.*.is_correct'    => 'sometimes|boolean',
            'options.*.option_letter' => 'required|string|max:1',
        ]);

        // Update teks pertanyaan
        $question->update([
            'question_text' => $request->question_text,
        ]);

        // Hapus opsi lama terlebih dahulu
        $question->options()->delete();

        // Buat ulang opsi jawaban dengan data yang baru
        foreach ($request->options as $optData) {
            $question->options()->create([
                'option_letter' => $optData['option_letter'],
                'option_text'   => $optData['option_text'],
                'is_correct'    => $optData['is_correct'] ?? false,
            ]);
        }

        return redirect()
            ->route('owner.quizzes.show', [$business->business_unique_id, $quiz->id])
            ->with('success', 'Question and options updated successfully.');
    }

    /**
     * Menghapus soal beserta opsi jawabannya.
     *
     * Catatan: Hanya record question yang dihapus. Karena pada migrasi tabel questions
     * terdapat aturan cascade pada tabel options, maka opsi terkait akan ikut terhapus.
     * Quiz tidak akan terhapus karena aturan cascade tidak berlaku dari child ke parent.
     */
    public function destroy($businessUniqueId, $quizId, $questionId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();
        $quiz     = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);

        $this->authorizeBusinessQuiz($business, $quiz, $question);

        // Hapus question (opsi-opsi yang terkait ikut terhapus karena foreign key cascade)
        $question->delete();

        return redirect()
            ->route('owner.quizzes.show', [$business->business_unique_id, $quiz->id])
            ->with('success', 'Question deleted successfully.');
    }
}