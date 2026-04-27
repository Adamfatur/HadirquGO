<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRewardsTable extends Migration
{
    public function up()
    {
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel users
            $table->foreignId('reward_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel rewards
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->onDelete('cascade'); // Foreign key ke tabel attendances
            $table->timestamp('received_at')->useCurrent(); // Waktu hadiah diterima
            $table->timestamps(); // Kolom created_at dan updated_at

            // Tambahkan indeks untuk kolom attendance_id (opsional, untuk optimasi query)
            $table->index('attendance_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_rewards');
    }
}