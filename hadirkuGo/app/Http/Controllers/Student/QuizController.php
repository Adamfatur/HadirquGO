<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuizAttempt;
use App\Models\QuizResult;
use App\Models\UserPoint;
use App\Models\UserPointSummary;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('questions')->get();
        $user = Auth::user();

        // Menandai mana kuis yang sudah dikerjakan hari ini
        foreach ($quizzes as $quiz) {
            $quiz->is_completed = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_unique_id', $quiz->unique_id)
                ->whereDate('attempt_date', Carbon::today())
                ->exists();

            if ($quiz->is_completed) {
                // Hitung detik sampai tengah malam
                $tomorrow = Carbon::tomorrow()->startOfDay();
                $quiz->retry_in = $tomorrow->diffInSeconds(Carbon::now());

                // Ambil attempt terakhir
                $lastAttempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_unique_id', $quiz->unique_id)
                    ->latest()
                    ->first();
                $quiz->last_attempt_unique_id = $lastAttempt ? $lastAttempt->unique_id : null;
            } else {
                $quiz->retry_in = null;
                $quiz->last_attempt_unique_id = null;
            }
        }

        // History poin kuis untuk user yang sedang login (urut terbaru -> terlama)
        $userPointsHistory = UserPoint::with('user')
            ->where('user_id', $user->id)
            ->where('description', 'like', 'Quiz Completed:%')
            ->orderBy('created_at', 'desc') // sudah benar: terbaru ke terlama
            ->get();

        // History user lain (max 20) diurutkan dari terbaru -> terlama
        // Exclude user sendiri (user_id <> $user->id) jika tidak ingin menampilkan data sendiri
        $otherUsersPointsHistory = UserPoint::with('user')
            ->where('description', 'like', 'Quiz Completed:%')
            ->where('user_id', '<>', $user->id)
            ->orderBy('created_at', 'desc') // Diurutkan berdasarkan waktu pembuatan, terbaru dahulu
            ->limit(20)
            ->get();

        return view('student.quizzes.index', compact(
            'quizzes',
            'userPointsHistory',
            'otherUsersPointsHistory'
        ));
    }

    public function show($uniqueId)
    {
        // Ambil quiz berdasarkan unique_id
        $quiz = Quiz::with('questions.options')
            ->where('unique_id', $uniqueId)
            ->firstOrFail();

        // Generate tanggal Jakarta hari ini
        $todayJakarta = Carbon::now('Asia/Jakarta')->today();

        // Cek apakah sudah ada attempt hari ini (berdasarkan waktu Jakarta)
        $existingAttempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_unique_id', $quiz->unique_id)
            ->whereDate('attempt_date', $todayJakarta)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.quizzes.index')
                ->with('error', 'Anda sudah mengerjakan kuis ini hari ini.');
        }

        // User belum pernah attempt hari ini, berarti boleh mulai quiz baru
        // -> set flag resetLocalStorage = true
        $resetLocalStorage = true;

        // Acak pertanyaan dan ambil 5
        $questions = $quiz->questions->shuffle()->take(5);

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pertanyaan tersedia.');
        }

        // Hitung poin per pertanyaan
        foreach ($questions as $question) {
            $question->points = $this->calculateQuestionPoints($question);
        }

        // Kirim variabel 'resetLocalStorage' ke Blade
        return view('student.quizzes.show', compact('quiz', 'questions', 'resetLocalStorage'));
    }

    /**
     * Ubah skema poin di sini agar 1 soal = 6 poin, sehingga
     * total 5 soal = 30 poin (jika semua benar).
     */
    private function calculateQuestionPoints($question)
    {
        // Masing-masing soal benar = 6 poin
        return 6;
    }

    public function store(Request $request, $uniqueId)
    {
        $quiz = Quiz::where('unique_id', $uniqueId)->firstOrFail();
        $user = Auth::user();

        // Generate tanggal Jakarta hari ini
        $todayJakarta = Carbon::now('Asia/Jakarta')->today();

        // Cek attempt hari ini berdasarkan waktu Jakarta
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_unique_id', $quiz->unique_id)
            ->whereDate('attempt_date', $todayJakarta)
            ->exists();

        if ($existingAttempt) {
            return redirect()->back()->with('error', 'Anda sudah mencoba kuis ini hari ini.');
        }

        // Proses jawaban
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_unique_id' => $quiz->unique_id,
            'attempt_date' => $todayJakarta,
            'score' => 0,
        ]);

        $score = 0;
        $answeredQuestionIds = array_keys($request->input('answers', []));
        $answeredQuestions = Question::whereIn('id', $answeredQuestionIds)->get();

        foreach ($answeredQuestions as $question) {
            $selectedOption = $request->input("answers.{$question->id}");
            $option = Option::where('question_id', $question->id)
                ->where('option_letter', $selectedOption)
                ->first();

            // Beri poin jika jawaban benar (sekarang 6 poin per soal)
            if ($option && $option->is_correct) {
                $score += 6;
            }

            // Simpan hasil
            QuizResult::create([
                'quiz_attempt_id' => $quizAttempt->id,
                'question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $option ? $option->is_correct : false,
            ]);
        }

        // Update skor attempt
        $quizAttempt->update(['score' => $score]);

        // Update poin user
        UserPoint::create([
            'user_id' => $user->id,
            'points' => $score,
            'description' => "Quiz Completed: {$quiz->title}",
        ]);

        // Update summary
        UserPointSummary::updateOrCreate(
            ['user_id' => $user->id],
            ['total_points' => \DB::raw("total_points + {$score}"), 'current_points' => \DB::raw("current_points + {$score}")]
        );

        return redirect()->route('student.quizzes.result', $quizAttempt->unique_id);
    }

    public function result($uniqueId)
    {
        $quizAttempt = QuizAttempt::with('results')
            ->where('unique_id', $uniqueId)
            ->firstOrFail();

        $correctCount = $quizAttempt->results()->where('is_correct', true)->count();

        return view('student.quizzes.result', compact('quizAttempt', 'correctCount'));
    }
}