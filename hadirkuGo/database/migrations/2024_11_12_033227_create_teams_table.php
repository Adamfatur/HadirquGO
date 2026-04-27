<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('team_unique_id')->unique();
            $table->string('name');
            $table->foreignId('business_id')->constrained()->onDelete('cascade'); // Relasi ke bisnis
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null'); // Leader adalah Lecturer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
}

