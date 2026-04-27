<?php

// database/migrations/xxxx_xx_xx_create_super_quiz_results_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperQuizResultsTable extends Migration
{
    public function up()
    {
        Schema::create('super_quiz_results', function (Blueprint $table) {
            $table->uuid('unique_id')->primary();
            $table->uuid('super_quiz_attempt_id'); // Reference to super_quiz_attempts
            $table->uuid('super_quiz_question_id'); // Reference to super_quiz_questions
            $table->uuid('super_quiz_option_id'); // Reference to super_quiz_options
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('super_quiz_attempt_id')->references('unique_id')->on('super_quiz_attempts')->onDelete('cascade');
            $table->foreign('super_quiz_question_id')->references('unique_id')->on('super_quiz_questions')->onDelete('cascade');
            $table->foreign('super_quiz_option_id')->references('unique_id')->on('super_quiz_options')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_quiz_results');
    }
}


