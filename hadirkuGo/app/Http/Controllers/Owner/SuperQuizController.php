<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\SuperQuiz;
use App\Models\Business;
use App\Models\SuperQuizQuestion;
use App\Models\SuperQuizOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SuperQuizController extends Controller
{
    public function index($business_unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->first();

        if (!$business) {
            // If no business is found, you can redirect with an error message or show an error page
            return redirect()->route('owner.dashboard')->with('error', 'Business not found.');
        }

        // Ensure that superQuizzes is not null
        $superQuizzes = $business->superQuizzes; // This will return a collection, even if it's empty

        return view('owner.superquizzes.index', compact('superQuizzes', 'business'));
    }

    public function create($business_unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        return view('owner.superquizzes.create', compact('business'));
    }

    public function store(Request $request, $business_unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'max_score' => 'required|integer',
            'question_limit' => 'required|integer',
        ]);

        $superQuiz = $business->superQuizzes()->create([
            'title' => $request->title,
            'max_score' => $request->max_score,
            'question_limit' => $request->question_limit,
            'status' => 'active', // Default status
        ]);

        return redirect()->route('superquizzes.index', $business_unique_id)
            ->with('success', 'Super Quiz created successfully.');
    }

    public function show($business_unique_id, SuperQuiz $superQuiz)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        return view('owner.superquizzes.show', compact('superQuiz', 'business'));
    }

    public function edit($business_unique_id, SuperQuiz $superQuiz)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        return view('owner.superquizzes.edit', compact('superQuiz', 'business'));
    }

    public function update(Request $request, $business_unique_id, SuperQuiz $superQuiz)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'max_score' => 'required|integer',
            'question_limit' => 'required|integer',
        ]);

        $superQuiz->update([
            'title' => $request->title,
            'max_score' => $request->max_score,
            'question_limit' => $request->question_limit,
        ]);

        return redirect()->route('superquizzes.index', $business_unique_id)
            ->with('success', 'Super Quiz updated successfully.');
    }

    public function destroy($business_unique_id, SuperQuiz $superQuiz)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        $superQuiz->delete();

        return redirect()->route('superquizzes.index', $business_unique_id)
            ->with('success', 'Super Quiz deleted successfully.');
    }

    public function createQuestion($business_unique_id, $unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $superQuiz = $business->superQuizzes()->where('unique_id', $unique_id)->firstOrFail();

        return view('owner.superquizzes.create-question', compact('superQuiz', 'business'));
    }

    public function storeQuestion(Request $request, $business_unique_id, $unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $superQuiz = $business->superQuizzes()->where('unique_id', $unique_id)->firstOrFail();

        $request->validate([
            'question_text' => 'required|string|max:255',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|string',
        ]);

        // Store the question and options
        $question = $superQuiz->questions()->create([
            'question_text' => $request->question_text,
        ]);

        $question->options()->createMany([
            ['option_letter' => 'A', 'option_text' => $request->option_a, 'is_correct' => $request->correct_option == 'A'],
            ['option_letter' => 'B', 'option_text' => $request->option_b, 'is_correct' => $request->correct_option == 'B'],
            ['option_letter' => 'C', 'option_text' => $request->option_c, 'is_correct' => $request->correct_option == 'C'],
            ['option_letter' => 'D', 'option_text' => $request->option_d, 'is_correct' => $request->correct_option == 'D'],
        ]);

        return redirect()->route('superquizzes.show', [$business_unique_id, $superQuiz->unique_id])
            ->with('success', 'Question added successfully.');
    }

    /**
     * Display the form for editing the specified SuperQuizQuestion.
     *
     * @param  string  $business_unique_id
     * @param  string  $unique_id  (SuperQuiz unique_id)
     * @param  string  $question_id (SuperQuizQuestion unique_id)
     * @return \Illuminate\Http\Response
     */
    public function editQuestion($business_unique_id, $unique_id, $question_id) // Parameter fungsi disesuaikan dengan route
    {
        // Cari Business dan SuperQuiz (opsional, untuk validasi atau digunakan di view)
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $superQuiz = SuperQuiz::where('unique_id', $unique_id)->firstOrFail();

        // Ambil pertanyaan beserta opsi jawabannya menggunakan $question_id
        $question = SuperQuizQuestion::with('options')->findOrFail($question_id);

        // Pastikan pertanyaan ini benar-benar milik SuperQuiz ini (opsional, untuk validasi lebih lanjut)
        if ($question->super_quiz_id !== $unique_id) {
            abort(404, 'Question not found in this SuperQuiz.'); // Atau logika error handling lainnya
        }

        return view('owner.superquizzes.edit-question', compact('question', 'business_unique_id', 'unique_id')); // Atau return data JSON untuk API, tambahkan business_unique_id dan unique_id ke compact
    }

    /**
     * Update the specified SuperQuizQuestion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $business_unique_id
     * @param  string  $unique_id  (SuperQuiz unique_id)
     * @param  string  $question_id (SuperQuizQuestion unique_id)
     * @return \Illuminate\Http\Response
     */
    public function updateQuestion(Request $request, $business_unique_id, $unique_id, $question_id)
    {
        // 1. Ambil Data Bisnis dan SuperQuiz (Validasi dan Data View Opsional)
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $superQuiz = SuperQuiz::where('unique_id', $unique_id)->firstOrFail();

        // 2. Ambil Data Pertanyaan yang Akan Diedit
        $question = SuperQuizQuestion::findOrFail($question_id);

        // 3. Validasi Relasi Pertanyaan dan SuperQuiz (Opsional, Keamanan Tambahan)
        if ($question->super_quiz_id !== $unique_id) {
            abort(404, 'Pertanyaan tidak termasuk dalam SuperQuiz ini.');
        }

        // 4. Validasi Input dari Request
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string', // Teks pertanyaan wajib diisi dan berupa string
            'options' => ['required', 'array', function ($attribute, $value, $fail) { // Validasi untuk array options
                $correctOptionCount = 0; // Inisialisasi counter untuk opsi benar
                foreach ($value as $optionData) { // Loop setiap data opsi yang dikirim
                    if (isset($optionData['is_correct']) && $optionData['is_correct']) { // Cek jika 'is_correct' ada dan bernilai true
                        $correctOptionCount++; // Increment counter jika opsi benar
                    }
                }

                if ($correctOptionCount !== 1) { // Pastikan hanya ada tepat satu opsi benar
                    $fail('Harus ada tepat satu opsi yang benar yang dipilih.'); // Jika tidak tepat satu, validasi gagal dengan pesan error
                }
            }],
            'options.*.option_letter' => 'required|string|in:A,B,C,D', // Huruf opsi wajib, string, dan hanya A, B, C, D
            'options.*.option_text' => 'required|string', // Teks opsi wajib dan string
            'options.*.is_correct' => 'required|boolean', // Status benar opsi wajib dan boolean
        ]);

        // 5. Handle Jika Validasi Gagal
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput(); // Kembali ke halaman sebelumnya dengan error dan input
        }

        // 6. Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // 7. Update Data Pertanyaan
            $question->update([
                'question_text' => $request->question_text, // Update teks pertanyaan dengan data dari request
            ]);

            // 8. Loop dan Update/Buat Opsi Jawaban
            foreach ($request->options as $optionData) { // Loop setiap data opsi dari request
                $option = SuperQuizOption::where('super_quiz_question_id', $question_id) // Cari opsi berdasarkan question_id dan option_letter
                ->where('option_letter', $optionData['option_letter'])
                    ->first();

                if ($option) {
                    // 9. Update Opsi yang Sudah Ada
                    $option->update([
                        'option_text' => $optionData['option_text'], // Update teks opsi
                        'is_correct' => $optionData['is_correct'], // Update status benar opsi
                    ]);
                } else {
                    // 10. Buat Opsi Baru Jika Tidak Ditemukan
                    SuperQuizOption::create([
                        'super_quiz_question_id' => $question_id, // Set question_id untuk opsi baru
                        'option_letter' => $optionData['option_letter'], // Set huruf opsi
                        'option_text' => $optionData['option_text'], // Set teks opsi
                        'is_correct' => $optionData['is_correct'], // Set status benar opsi
                    ]);
                }
            }

            // 11. Commit Transaksi Jika Semua Berhasil
            DB::commit();

            // 12. Redirect ke Halaman Edit dengan Pesan Sukses
            return redirect()->route('questions.edit', [ // Redirect ke route questions.edit
                'business_unique_id' => $business_unique_id,
                'unique_id' => $unique_id,
                'question_id' => $question_id,
            ])->with('success', 'Soal berhasil diperbarui!'); // Kirim pesan sukses
            // Atau return response JSON sukses untuk API
            // return response()->json(['message' => 'Soal berhasil diperbarui!', 'data' => $question], 200);

        } catch (\Exception $e) {
            // 13. Rollback Transaksi Jika Terjadi Kesalahan
            DB::rollback();
            // 14. Tangani Error dan Redirect Kembali dengan Pesan Error
            return back()->with('error', 'Terjadi kesalahan saat memperbarui soal.')->withInput(); // Kembali ke halaman sebelumnya dengan pesan error
            // Atau return response JSON error untuk API
            // return response()->json(['message' => 'Terjadi kesalahan saat memperbarui soal.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroyQuestion($business_unique_id, $unique_id, $question_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $superQuiz = $business->superQuizzes()->where('unique_id', $unique_id)->firstOrFail();
        $question = $superQuiz->questions()->findOrFail($question_id);

        $question->delete();

        return redirect()->route('superquizzes.show', [$business_unique_id, $superQuiz->unique_id])
            ->with('success', 'Question deleted successfully.');
    }
}
