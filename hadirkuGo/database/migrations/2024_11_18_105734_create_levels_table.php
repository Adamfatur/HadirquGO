<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('minimum_points')->unsigned();
            $table->integer('maximum_points')->unsigned();
            $table->text('description')->nullable(); // Menambahkan kolom deskripsi
            $table->string('image_url')->nullable(); // Menambahkan kolom URL gambar
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
