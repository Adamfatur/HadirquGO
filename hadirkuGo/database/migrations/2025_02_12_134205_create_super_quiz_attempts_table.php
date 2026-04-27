<?php

// database/migrations/xxxx_xx_xx_create_super_quiz_attempts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('super_quiz_attempts', function (Blueprint $table) {
            $table->uuid('unique_id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Use 'user_id' referencing 'users.id'
            $table->uuid('super_quiz_id');
            $table->dateTime('attempt_date');
            $table->integer('score')->default(0);
            $table->enum('status', ['ongoing', 'completed', 'abandoned'])->default('ongoing');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('super_quiz_id')->references('unique_id')->on('super_quizzes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_quiz_attempts');
    }
}



