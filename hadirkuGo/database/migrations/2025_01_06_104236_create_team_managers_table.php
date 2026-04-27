<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamManagersTable extends Migration
{
    public function up()
    {
        Schema::create('team_managers', function (Blueprint $table) {
            $table->id();
            // Kolom untuk relasi ke tabel teams
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            // Kolom untuk relasi ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Misalkan ingin menambahkan kolom role/permission lain, bisa di sini
            // $table->string('manager_role')->default('manager');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_managers');
    }
}
