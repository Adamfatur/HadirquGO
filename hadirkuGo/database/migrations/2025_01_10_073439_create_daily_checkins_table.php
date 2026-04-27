<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_checkins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID pengguna
            $table->date('checkin_date'); // Tanggal check-in
            $table->date('week_start_date'); // Tanggal awal minggu (untuk reset mingguan)
            $table->integer('points_earned')->default(0); // Poin yang diperoleh
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint untuk menghindari duplikasi check-in di hari yang sama
            $table->unique(['user_id', 'checkin_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_checkins');
    }
}