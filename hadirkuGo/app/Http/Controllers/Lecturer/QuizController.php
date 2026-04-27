<?php

namespace App\Http\Controllers\Lecturer;

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

        // ========== Tambahan: Ambil History Poin untuk Modal ==========

        // 1) History poin kuis untuk user yang sedang login (urut terbaru -> terlama)
        $userPointsHistory = UserPoint::with('user')
            ->where('user_id', $user->id)
            ->where('description', 'like', 'Quiz Completed:%')
            ->orderBy('created_at', 'desc') // terbaru ke terlama
            ->get();

        // 2) History (max 20) user lain, diurutkan dari terbaru -> terlama (created_at desc)
        //    Exclude user sendiri jika tidak ingin menampilkan data sendiri
        $otherUsersPointsHistory = UserPoint::with('user')
            ->where('description', 'like', 'Quiz Completed:%')
            ->where('user_id', '<>', $user->id)
            ->orderBy('created_at', 'desc') // juga diurutkan berdasarkan waktu, terbaru dulu
            ->limit(20)
            ->get();

        return view('lecturer.quizzes.index', compact(
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

        // Cek apakah sudah ada attempt hari ini (waktu Jakarta)
        $existingAttempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_unique_id', $quiz->unique_id)
            ->whereDate('attempt_date', $todayJakarta)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('lecturer.quizzes.index')
                ->with('error', 'You have already completed this quiz today.');
        }

        // User belum pernah attempt hari ini
        // -> set flag resetLocalStorage = true (untuk reset timer di front-end)
        $resetLocalStorage = true;

        // Acak pertanyaan dan ambil 5
        $questions = $quiz->questions->shuffle()->take(5);

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available.');
        }

        // Hitung poin per pertanyaan
        foreach ($questions as $question) {
            $question->points = $this->calculateQuestionPoints($question);
        }

        return view('lecturer.quizzes.show', compact('quiz', 'questions', 'resetLocalStorage'));
    }

    /**
     * Masing-masing soal benar = 6 poin,
     * total 5 soal = 30 poin (jika semua benar).
     */
    private function calculateQuestionPoints($question)
    {
        return 6;
    }

    public function store(Request $request, $uniqueId)
    {
        $quiz = Quiz::where('unique_id', $uniqueId)->firstOrFail();
        $user = Auth::user();

        // Generate tanggal Jakarta
        $todayJakarta = Carbon::now('Asia/Jakarta')->today();

        // Cek apakah sudah attempt hari ini (waktu Jakarta)
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_unique_id', $quiz->unique_id)
            ->whereDate('attempt_date', $todayJakarta)
            ->exists();

        if ($existingAttempt) {
            return redirect()->back()->with('error', 'You have already tried this quiz today.');
        }

        // Buat attempt
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_unique_id' => $quiz->unique_id,
            'attempt_date' => $todayJakarta,
            'score' => 0,
        ]);

        $score = 0;
        $answeredQuestionIds = array_keys($request->input('answers', []));
        $answeredQuestions = Question::whereIn('id', $answeredQuestionIds)->get();

        // Hitung skor
        foreach ($answeredQuestions as $question) {
            $selectedOption = $request->input("answers.{$question->id}");
            $option = Option::where('question_id', $question->id)
                ->where('option_letter', $selectedOption)
                ->first();

            // 6 poin jika benar
            if ($option && $option->is_correct) {
                $score += 6;
            }

            QuizResult::create([
                'quiz_attempt_id' => $quizAttempt->id,
                'question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $option ? $option->is_correct : false,
            ]);
        }

        // Update skor attempt
        $quizAttempt->update(['score' => $score]);

        // Update user point
        UserPoint::create([
            'user_id' => $user->id,
            'points' => $score,
            'description' => "Quiz Completed: {$quiz->title}",
        ]);

        // Update summary
        UserPointSummary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'total_points' => \DB::raw("total_points + {$score}"),
                'current_points' => \DB::raw("current_points + {$score}")
            ]
        );

        return redirect()->route('lecturer.quizzes.result', $quizAttempt->unique_id);
    }

    public function result($uniqueId)
    {
        $quizAttempt = QuizAttempt::with('results')
            ->where('unique_id', $uniqueId)
            ->firstOrFail();

        $correctCount = $quizAttempt->results()->where('is_correct', true)->count();

        return view('lecturer.quizzes.result', compact('quizAttempt', 'correctCount'));
    }
}