<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyRankingsTable extends Migration
{
    public function up()
    {
        Schema::create('weekly_rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->unsignedBigInteger('team_id')->nullable(); // Reference to teams table
            $table->integer('total_points')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->integer('total_hours')->default(0); // Total hours in minutes
            $table->date('week_start_date'); // Monday of the week
            $table->date('week_end_date');   // Sunday of the week

            $table->timestamps();

            // Unique constraint to prevent duplicate entries for the same user in the same week
            $table->unique(['user_id', 'week_start_date']);

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('weekly_rankings');
    }
}
