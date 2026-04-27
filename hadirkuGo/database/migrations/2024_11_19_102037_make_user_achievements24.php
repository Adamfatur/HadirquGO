<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserAchievements24 extends Migration
{
    public function up()
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null'); // Relasi ke tabel teams (opsional)
            $table->foreignId('achievement_id')->constrained('achievements')->onDelete('cascade'); // Relasi ke tabel achievements
            $table->timestamp('achieved_at')->default(now()); // Kapan pencapaian didapat
            $table->timestamps(); // Tanggal pembuatan dan pembaruan
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_achievements');
    }
}

