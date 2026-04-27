<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade'); // Link to business
            $table->string('unique_id')->unique(); // Unique ID for location
            $table->string('name'); // Name of the location
            $table->string('slug')->unique(); // URL-friendly slug
            $table->text('description')->nullable(); // Optional description
            $table->string('latitude')->nullable(); // Latitude for GPS
            $table->string('longitude')->nullable(); // Longitude for GPS
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_locations');
    }
}
