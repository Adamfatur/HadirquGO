<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class TestQuestionController extends Controller
{
    /**
     * Mengambil 20 pertanyaan acak beserta opsinya
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Mengambil 20 pertanyaan acak dalam format teks yang mudah dibaca
     *
     * @return \Illuminate\Http\Response
     */
    public function getRandomQuestionsText()
    {
        $randomQuestions = Question::with('options')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $output = '';
        foreach ($randomQuestions as $index => $question) {
            $output .= "Pertanyaan " . ($index + 1) . ": " . $question->question_text . "\n";
            $output .= "Jawaban:\n";

            foreach ($question->options as $option) {
                $isCorrect = $option->is_correct ? " (benar)" : "";
                $output .= $option->option_letter . ". " . $option->option_text . $isCorrect . "\n";
            }

            $output .= "\n\n";
        }

        return response($output, 200)->header('Content-Type', 'text/plain');
    }

}
