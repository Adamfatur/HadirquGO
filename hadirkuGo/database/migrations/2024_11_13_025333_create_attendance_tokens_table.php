<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTokensTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('token')->unique();
            $table->enum('type', ['checkin', 'checkout'])->default('checkin'); // Menyimpan jenis token
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_tokens');
    }
}
