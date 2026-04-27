<?php

// database/migrations/xxxx_xx_xx_create_super_quiz_questions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperQuizQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('super_quiz_questions', function (Blueprint $table) {
            $table->uuid('unique_id')->primary(); // UUID for the question
            $table->uuid('super_quiz_id'); // UUID for foreign key
            $table->text('question_text');
            $table->timestamps();

            // Explicitly reference 'unique_id' in 'super_quizzes' for the foreign key
            $table->foreign('super_quiz_id')
                ->references('unique_id') // Ensure 'unique_id' is used
                ->on('super_quizzes')    // Referring to 'super_quizzes'
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_quiz_questions');
    }
}



