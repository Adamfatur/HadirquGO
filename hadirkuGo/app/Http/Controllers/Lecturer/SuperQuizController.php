<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\SuperQuiz;
use App\Models\SuperQuizAttempt;
use App\Models\SuperQuizQuestion;
use App\Models\SuperQuizResult;
use App\Models\UserPoint;
use App\Models\UserPointSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperQuizController extends Controller
{
    private function updateUserPoints($userId, $points, $description)
    {
        if ($points > 0) {
            UserPoint::create([
                'user_id' => $userId,
                'points' => $points,
                'description' => $description,
            ]);

            $userPointSummary = UserPointSummary::firstOrCreate(['user_id' => $userId], ['total_points' => 0, 'current_points' => 0]);
            $userPointSummary->total_points += $points;
            $userPointSummary->current_points += $points;
            $userPointSummary->save();
        }
    }

    public function index()
    {
        $userId = Auth::id();

        $superQuizzes = SuperQuiz::where('status', 'active')->get()->map(function ($quiz) use ($userId) {
            $todayAttempt = SuperQuizAttempt::where('super_quiz_id', $quiz->unique_id)
                ->where('user_id', $userId)
                ->whereDate('attempt_date', Carbon::today())
                ->whereIn('status', ['completed', 'surrendered','failed'])
                ->exists();


            if ($todayAttempt) {
                $quiz->hasTakenToday = true;
            } else {
                $quiz->hasTakenToday = false;
            }

            return $quiz;
        });

        return view('lecturer.superquiz.index', compact('superQuizzes'));
    }

    public function viewResult(SuperQuiz $superQuiz)
    {
        $userId = Auth::id();
        $attempt = SuperQuizAttempt::where('super_quiz_id', $superQuiz->unique_id)
            ->where('user_id', $userId)
            ->whereDate('attempt_date', Carbon::today())
            ->first();

        if (!$attempt) {
            return redirect()->route('lecturer.superquiz.index')->with('error', 'You have not taken this quiz today.');
        }

        $results = SuperQuizResult::where('super_quiz_attempt_id', $attempt->unique_id)
            ->with('superQuizQuestion', 'superQuizOption')
            ->get();

        $score = $attempt->score;
        $correctAnswers = $results->where('is_correct', true)->count();
        return view('lecturer.superquiz.viewResult', compact('superQuiz', 'score', 'correctAnswers', 'results'));
    }

    public function show(SuperQuiz $superQuiz)
    {
        // Hapus sesi terkait kuis yang lama sebelum memulai kuis baru
        session()->forget([
            'super_quiz_attempt_id',
            'super_quiz_question_ids',
            'current_question_index',
            'quiz_score',
            'quiz_start_time',
            'question_start_time',
            'question_elapsed_time'
        ]);

        // Cek apakah sudah ada attempt kuis hari ini
        $todayAttempt = SuperQuizAttempt::where('super_quiz_id', $superQuiz->unique_id)
            ->where('user_id', Auth::id())
            ->whereDate('attempt_date', Carbon::today())
            ->exists();

        if ($todayAttempt) {
            return redirect()->route('lecturer.superquiz.index')
                ->with('error', 'You have already taken this quiz today. Please try again tomorrow.');
        }

        // Ambil soal dari kuis, maksimal 10 soal secara acak
        $questions = SuperQuizQuestion::where('super_quiz_id', $superQuiz->unique_id)
            ->inRandomOrder()
            ->with('options')
            ->limit(10)
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('lecturer.superquiz.index')
                ->with('error', 'This quiz has no questions yet.');
        }

        // Membuat record attempt baru untuk user
        $attempt = SuperQuizAttempt::create([
            'unique_id' => \Str::uuid()->toString(),
            'user_id' => Auth::id(),
            'super_quiz_id' => $superQuiz->unique_id,
            'attempt_date' => Carbon::now(),
            'score' => 0,
            'status' => 'ongoing',
        ]);

        // Menyimpan data session yang berhubungan dengan quiz
        session(['super_quiz_attempt_id' => $attempt->unique_id]);
        session(['super_quiz_question_ids' => $questions->pluck('unique_id')->toArray()]);
        session(['current_question_index' => 0]);
        session(['quiz_score' => 0]); // Initialize quiz_score to 0 in session
        session(['quiz_start_time' => time()]);
        session(['last_attempt_date' => Carbon::today()->toDateString()]); // Menyimpan tanggal terakhir attempt

        // Redirect ke soal pertama
        return redirect()->route('lecturer.superquiz.question', [
            'superQuiz' => $superQuiz->unique_id,
            'questionNumber' => 1,
        ]);
    }



    public function showQuestion(SuperQuiz $superQuiz, $questionNumber)
    {
        // Pastikan sesi masih valid
        $lastAttemptDate = session('last_attempt_date');
        $todayDate = Carbon::today()->toDateString();

        // Jika hari berganti, reset sesi
        if ($lastAttemptDate !== $todayDate) {
            session()->forget([
                'super_quiz_attempt_id',
                'super_quiz_question_ids',
                'current_question_index',
                'quiz_score',
                'quiz_start_time',
                'question_start_time',
                'question_elapsed_time',
            ]);
            return redirect()->route('lecturer.superquiz.index')
                ->with('error', 'The day has changed. Please restart the quiz.');
        }

        $questionIndex = session('current_question_index', 0);
        $questionIds = session('super_quiz_question_ids');
        $attemptUniqueId = session('super_quiz_attempt_id');

        if (!$questionIds || !isset($questionIds[$questionIndex])) {
            // Kuis selesai atau terjadi kesalahan
            return redirect()->route('lecturer.superquiz.index')
                ->with('success', 'Super Quiz selesai!');
        }

        $questionId = $questionIds[$questionIndex];
        $question = SuperQuizQuestion::with('options')->find($questionId);

        if (!$question) {
            return redirect()->route('lecturer.superquiz.index')
                ->with('error', 'Soal tidak ditemukan.');
        }

        $elapsedTime = time() - session('quiz_start_time');
        $timeLeft = max(0, 10 - ($elapsedTime - session('question_elapsed_time', 0)));

        // Hitung potensi skor berdasarkan quiz_score di sesi
        $potentialScore = session('quiz_score', 0);

        session(['question_start_time' => time()]); // Set waktu mulai soal saat ini di sesi

        return view('lecturer.superquiz.question', compact('superQuiz', 'question', 'questionNumber', 'timeLeft', 'potentialScore'));
    }



    public function showConfirmation(SuperQuiz $superQuiz, $questionNumber)
    {
        $quizScore = session('quiz_score', 0);

        // Calculate potential score - same as in showQuestion
        $potentialScore = session('quiz_score', 0);


        return view('lecturer.superquiz.confirmation', compact('superQuiz', 'questionNumber', 'quizScore', 'potentialScore')); // Pass potentialScore
    }


    public function submitAnswer(Request $request, SuperQuiz $superQuiz, $questionNumber)
    {
        $request->validate([
            'selected_option_id' => 'required|uuid',
        ]);

        $selectedOptionId = $request->input('selected_option_id');
        $questionIndex = session('current_question_index', 0);
        $questionIds = session('super_quiz_question_ids');
        $attemptUniqueId = session('super_quiz_attempt_id');
        $quizScore = session('quiz_score', 0);
        $userId = Auth::id();
        $questionStartTime = session('question_start_time');

        // Cek apakah waktu mulai soal ada di sesi
        if (!$questionStartTime) {
            Log::error('SuperQuiz Timeout Error: No question_start_time in session.'); // Log error jika start time hilang
            return redirect()->route('lecturer.superquiz.index')->with('error', 'Sesi kuis tidak valid. (No start time)');
        }

        // Hitung waktu yang telah berlalu untuk soal ini
        $elapsedTimeForQuestion = time() - $questionStartTime;

        // Cek apakah waktu sudah habis (lebih dari 10 detik)
        if ($elapsedTimeForQuestion >= 10) {
            // Jika waktu habis, set status kuis ke 'failed' dan kirim ke halaman kegagalan
            Log::info('SuperQuiz Timeout: Time expired for question ' . $questionNumber . ', Attempt ID: ' . $attemptUniqueId);
            $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
            $attempt->score = 0;
            $attempt->status = 'failed';
            $attempt->save();

            // Hapus sesi terkait soal
            session()->forget(['super_quiz_attempt_id', 'super_quiz_question_ids', 'current_question_index', 'quiz_score', 'quiz_start_time', 'question_start_time', 'question_elapsed_time']);

            Log::info('SuperQuiz Timeout: Session cleared, redirecting to failed page.');

            return redirect()->route('lecturer.superquiz.failed', [
                'superQuiz' => $superQuiz->unique_id,
                'questionNumber' => $questionNumber,
            ])->with('error', 'Waktu habis untuk soal ini.');
        }

        // Cek apakah soal ada dan jawabannya benar
        $questionId = $questionIds[$questionIndex];
        $question = SuperQuizQuestion::with('options')->find($questionId);

        if (!$question) {
            return redirect()->route('lecturer.superquiz.index')
                ->with('error', 'Soal tidak ditemukan.');
        }

        $correctOption = $question->options()->where('is_correct', true)->first();
        $isCorrectAnswer = ($selectedOptionId == $correctOption->unique_id);

        // Simpan hasil jawaban ke SuperQuizResult
        SuperQuizResult::create([
            'super_quiz_attempt_id' => $attemptUniqueId,
            'super_quiz_question_id' => $questionId,
            'super_quiz_option_id' => $selectedOptionId,
            'is_correct' => $isCorrectAnswer,
        ]);

        if ($isCorrectAnswer) {
            // Jawaban benar, tambah poin kuis
            session(['quiz_score' => $quizScore + 5]);

            $nextQuestionIndex = $questionIndex + 1;
            session(['current_question_index' => $nextQuestionIndex]);

            if ($nextQuestionIndex >= count($questionIds)) {
                // Jika semua soal sudah dijawab dengan benar
                $allCorrect = SuperQuizResult::where('super_quiz_attempt_id', $attemptUniqueId)
                    ->where('is_correct', false)
                    ->doesntExist();

                if ($allCorrect) {
                    // Jika semua jawaban benar, set status kuis selesai dengan nilai 100
                    $totalScore = 100;
                    $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
                    $attempt->score = $totalScore;
                    $attempt->status = 'completed';
                    $attempt->save();

                    // Update poin pengguna setelah menyelesaikan kuis
                    $this->updateUserPoints($userId, $totalScore, 'Super Quiz Completion Bonus: ' . $superQuiz->title);

                    // Hapus sesi kuis
                    session()->forget(['super_quiz_attempt_id', 'super_quiz_question_ids', 'current_question_index', 'quiz_score', 'quiz_start_time', 'question_start_time', 'question_elapsed_time']);

                    return redirect()->route('lecturer.superquiz.index')
                        ->with('success', 'Congratulations! You completed the Super Quiz and got ' . $totalScore . ' Tesla Points!');
                } else {
                    // Jika ada jawaban salah, set status kuis gagal
                    $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
                    $attempt->score = 0;
                    $attempt->status = 'failed';
                    $attempt->save();

                    // Hapus sesi kuis
                    session()->forget(['super_quiz_attempt_id', 'super_quiz_question_ids', 'current_question_index', 'quiz_score', 'quiz_start_time', 'question_start_time', 'question_elapsed_time']);

                    return redirect()->route('lecturer.superquiz.failed', [
                        'superQuiz' => $superQuiz->unique_id,
                        'questionNumber' => $questionNumber,
                    ]);
                }

            } else {
                session(['question_elapsed_time' => 0]); // Reset waktu soal jika lanjut ke soal berikutnya
                return redirect()->route('lecturer.superquiz.confirmation', [
                    'superQuiz' => $superQuiz->unique_id,
                    'questionNumber' => $questionNumber,
                ])->with(['correctAnswer' => true, 'pointsEarned' => 5]); // Mengirim poin yang diperoleh
            }
        } else {
            // Jawaban salah - kuis gagal
            $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
            $attempt->score = 0;
            $attempt->status = 'failed';
            $attempt->save();

            // Hapus sesi kuis
            session()->forget(['super_quiz_attempt_id', 'super_quiz_question_ids', 'current_question_index', 'quiz_score', 'quiz_start_time', 'question_start_time', 'question_elapsed_time']);

            return redirect()->route('lecturer.superquiz.failed', [
                'superQuiz' => $superQuiz->unique_id,
                'questionNumber' => $questionNumber,
            ]);
        }
    }

    public function showFailed(SuperQuiz $superQuiz, $questionNumber)
    {
        return view('lecturer.superquiz.failed', compact('superQuiz', 'questionNumber'));
    }



    public function surrenderQuiz(SuperQuiz $superQuiz)
    {
        $attemptUniqueId = session('super_quiz_attempt_id');
        $userId = Auth::id();

        $allCorrect = SuperQuizResult::where('super_quiz_attempt_id', $attemptUniqueId)->where('is_correct', false)->doesntExist();

        if ($allCorrect) {
            $quizScore = session('quiz_score', 0); // Get the session based quiz_score which now reflects correctly answered questions so far.
            $pointsToAward = max(0, min(100, $quizScore));

            $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
            $attempt->score = $pointsToAward;
            $attempt->status = 'surrendered';
            $attempt->save();


            if ($pointsToAward > 0) {
                $this->updateUserPoints($userId, $pointsToAward, 'Super Quiz Surrender Reward: ' . $superQuiz->title);
                $successMessage = 'Quiz surrendered. You earned ' . $pointsToAward . ' Tesla Points!';
            } else {
                $successMessage = 'Quiz surrendered. You earned 0 Tesla Points.';
            }
        } else {
            $pointsToAward = 0;
            $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
            $attempt->score = 0;
            $attempt->status = 'surrendered';
            $attempt->save();
            $successMessage = 'Quiz surrendered. You earned 0 Tesla Points because of incorrect answers.';
        }


        session()->forget(['super_quiz_attempt_id', 'super_quiz_question_ids', 'current_question_index', 'quiz_score', 'quiz_start_time', 'question_elapsed_time']);

        return redirect()->route('lecturer.superquiz.index')
            ->with('success', $successMessage);
    }

    public function checkAnswerTimeout(Request $request, SuperQuiz $superQuiz, $questionNumber)
    {
        $elapsedTime = time() - session('quiz_start_time');
        $questionElapsedTime = $elapsedTime - session('question_elapsed_time', 0);

        if ($questionElapsedTime >= 10) {
            session(['question_elapsed_time' => $elapsedTime]);
            return response()->json(['timeout' => true]);
        } else {
            return response()->json(['timeout' => false, 'timeLeft' => max(0, 10 - $questionElapsedTime)]);
        }
    }

    public function timeoutAttempt(SuperQuiz $superQuiz)
    {
        $attemptUniqueId = session('super_quiz_attempt_id');
        $userId = Auth::id();

        // Check if the attempt exists
        $attempt = SuperQuizAttempt::where('unique_id', $attemptUniqueId)->first();
        if (!$attempt) {
            return redirect()->route('lecturer.superquiz.index')->with('error', 'Attempt not found.');
        }

        // Check if time has expired
        $elapsedTime = time() - session('quiz_start_time');
        if ($elapsedTime >= 10) {
            // Update the attempt status to failed
            $attempt->status = 'failed';
            $attempt->score = 0;
            $attempt->save();

            // Clear session data to prevent continuing the quiz
            session()->forget(['super_quiz_attempt_id', 'super_quiz_question_ids', 'current_question_index', 'quiz_score', 'quiz_start_time', 'question_start_time', 'question_elapsed_time']);

            // Redirect to the quiz result page after time has expired
            return redirect()->route('lecturer.superquiz.viewResult', [$superQuiz->unique_id])->with('error', 'Time has expired for this question.');
        }

        // If somehow this is reached without time expiration, just redirect to result page
        return redirect()->route('lecturer.superquiz.viewResult', [$superQuiz->unique_id])->with('error', 'Time has expired for this question.');
    }


}