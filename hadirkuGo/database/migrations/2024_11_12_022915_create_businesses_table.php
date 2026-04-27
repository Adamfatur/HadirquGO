<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_unique_id')->unique();
            $table->unsignedBigInteger('owner_id'); // Referensi ke ID pengguna pemilik bisnis
            $table->string('contact_person');       // Nama contact person (diambil dari nama pemilik pada saat pembuatan)
            $table->string('contact_email');        // Email contact person (diambil dari email pemilik)
            $table->string('contact_phone')->nullable();
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
