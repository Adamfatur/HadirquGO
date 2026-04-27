<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiodataTable extends Migration
{
    public function up()
    {
        Schema::create('biodatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->string('phone_number')->nullable(); // Bisa null
            $table->string('id_number')->nullable(); // Bisa null
            $table->string('other_id_number')->nullable(); // Bisa null
            $table->string('nickname')->nullable(); // Bisa null
            $table->text('about')->nullable(); // Bisa null
            $table->boolean('verified')->default(false); // Default false
            $table->string('degree_id')->nullable(); // Bisa null
            $table->date('birth_date')->nullable(); // Bisa null
            $table->timestamps(); // Tanggal created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('biodatas');
    }
}


