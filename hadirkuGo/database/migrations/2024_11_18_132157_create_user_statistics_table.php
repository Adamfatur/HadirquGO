<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStatisticsTable extends Migration
{
    public function up()
    {
        Schema::create('user_statistics', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unique(); // Each user has one statistics record
            $table->time('average_checkin_time')->nullable();
            $table->unsignedBigInteger('most_frequent_location_id')->nullable();
            $table->text('all_visited_locations')->nullable(); // JSON encoded array of location IDs
            $table->time('average_checkout_time')->nullable();
            $table->integer('total_checkins')->default(0);
            $table->integer('total_checkouts')->default(0);
            $table->integer('longest_consecutive_attendance_streak')->default(0); // In days
            $table->integer('max_checkins_in_one_day')->default(0);
            $table->integer('total_attendance_sessions')->default(0);
            $table->unsignedBigInteger('least_frequent_location_id')->nullable();
            $table->integer('morning_person_count')->default(0);
            $table->integer('late_person_count')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('most_frequent_location_id')->references('id')->on('attendance_locations')->onDelete('set null');
            $table->foreign('least_frequent_location_id')->references('id')->on('attendance_locations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_statistics');
    }
}
