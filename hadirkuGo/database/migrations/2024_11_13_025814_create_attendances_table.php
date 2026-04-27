<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->unsignedBigInteger('attendance_location_id')->nullable(); // Foreign key to attendance_locations
            $table->timestamp('checkin_time')->nullable(); // Check-in time
            $table->timestamp('checkout_time')->nullable(); // Check-out time
            $table->integer('duration_at_location')->default(0); // Duration at each location in minutes
            $table->integer('total_daily_duration')->default(0); // Cumulative daily duration, default 0
            $table->integer('points')->default(0); // Points related to attendance
            $table->string('type'); // Entry type, whether 'checkin' or 'checkout'
            $table->boolean('is_active')->default(true); // Indicates if the entry is active

            // Added a single column to store multiple locations
            $table->text('locations')->nullable(); // To store multiple location IDs as a string

            $table->timestamps();

            // Adding foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attendance_location_id')->references('id')->on('attendance_locations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
