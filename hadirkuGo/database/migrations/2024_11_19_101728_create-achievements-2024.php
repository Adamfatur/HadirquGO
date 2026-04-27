<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievements2024 extends Migration
{
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pencapaian
            $table->string('image')->nullable(); // Gambar pencapaian (opsional)
            $table->text('description')->nullable(); // Deskripsi pencapaian
            $table->timestamps(); // Tanggal pembuatan dan pembaruan
        });
    }

    public function down()
    {
        Schema::dropIfExists('achievements');
    }
}

