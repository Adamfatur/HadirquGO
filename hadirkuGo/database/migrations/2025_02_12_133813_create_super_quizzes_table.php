<?php

// database/migrations/xxxx_xx_xx_create_super_quizzes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperQuizzesTable extends Migration
{
    public function up()
    {
        Schema::create('super_quizzes', function (Blueprint $table) {
            $table->uuid('unique_id')->primary();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('max_score')->default(100); // Total max score for the Super Quiz
            $table->integer('question_limit')->default(10); // Total questions allowed
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_quizzes');
    }
}

