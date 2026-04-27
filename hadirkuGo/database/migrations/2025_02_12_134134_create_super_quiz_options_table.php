<?php

// database/migrations/xxxx_xx_xx_create_super_quiz_options_table.php
// database/migrations/xxxx_xx_xx_create_super_quiz_options_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperQuizOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('super_quiz_options', function (Blueprint $table) {
            $table->uuid('unique_id')->primary(); // UUID for the option
            $table->uuid('super_quiz_question_id'); // UUID for foreign key referencing super_quiz_questions
            $table->char('option_letter', 1); // A, B, C, D, etc.
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            // Explicitly reference 'unique_id' from 'super_quiz_questions' for the foreign key
            $table->foreign('super_quiz_question_id')
                ->references('unique_id') // Reference to 'unique_id' column in 'super_quiz_questions'
                ->on('super_quiz_questions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_quiz_options');
    }
}


