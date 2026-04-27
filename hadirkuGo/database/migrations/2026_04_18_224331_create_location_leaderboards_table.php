<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // e.g., top_locations_weekly, etc.
            $table->foreignId('attendance_location_id')->constrained('attendance_locations')->onDelete('cascade');
            $table->decimal('score', 15, 2)->default(0); // visit_count
            $table->decimal('secondary_score', 15, 2)->nullable(); // total_duration
            $table->integer('current_rank')->index();
            $table->integer('previous_rank')->nullable();
            $table->timestamps();

            $table->unique(['category', 'attendance_location_id'], 'loc_lb_cat_loc_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_leaderboards');
    }
};
