<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeDurationsTable extends Migration
{
    public function up()
    {
        Schema::create('challenge_durations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');
            $table->integer('total_duration'); // Total durasi dalam menit
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenge_durations');
    }
}
