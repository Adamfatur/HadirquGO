<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsTable extends Migration
{
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama hadiah (misal: "1000 Point", "500 Point", dll)
            $table->string('type')->default('point'); // Tipe hadiah (point, fisik, dll)
            $table->integer('quantity')->default(0); // Jumlah stok hadiah
            $table->decimal('probability', 5, 2)->default(0.00); // Probabilitas hadiah (dalam persen)
            $table->string('image')->nullable(); // Kolom untuk menyimpan URL gambar hadiah
            $table->text('deskripsi')->nullable(); // Kolom untuk menyimpan deskripsi hadiah
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rewards');
    }
}