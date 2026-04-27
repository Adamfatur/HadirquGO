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
        Schema::create('user_leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // e.g., top_levels, top_points, top_sessions_daily, etc.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 15, 2)->default(0);
            $table->decimal('secondary_score', 15, 2)->nullable(); // Optional secondary metric
            $table->integer('current_rank')->index();
            $table->integer('previous_rank')->nullable();
            $table->timestamps();

            $table->unique(['category', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_leaderboards');
    }
};
