<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeResultsTable extends Migration
{
    public function up()
    {
        Schema::create('challenge_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');
            $table->foreignId('winner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('loser_id')->constrained('users')->onDelete('cascade');
            $table->integer('points_awarded'); // Poin yang diberikan kepada pemenang
            $table->integer('points_deducted'); // Poin yang dikurangi dari yang kalah
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_results');
    }
}
