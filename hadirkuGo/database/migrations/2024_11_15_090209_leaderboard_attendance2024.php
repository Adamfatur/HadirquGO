<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeaderboardAttendance2024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_leaderboard', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User yang paling pagi/terlambat
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // Tim yang terkait
            $table->date('date'); // Tanggal kehadiran
            $table->boolean('morning_person')->default(false); // Apakah user ini Morning Person
            $table->boolean('last_person')->default(false); // Apakah user ini Last Person
            $table->decimal('longest_duration', 8, 2)->default(0); // Durasi kegiatan terlama dalam jam
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_leaderboard');
    }
}

