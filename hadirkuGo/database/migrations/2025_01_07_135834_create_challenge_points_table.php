<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengePointsTable extends Migration
{
    public function up()
    {
        Schema::create('challenge_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');
            $table->integer('points'); // Poin yang ditambahkan atau dikurangi
            $table->enum('type', ['awarded', 'deducted']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_points');
    }
}
